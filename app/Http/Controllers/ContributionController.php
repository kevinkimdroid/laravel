<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Member;
use App\Models\Account;
use App\Models\Transaction;

class ContributionController extends Controller
{
    /**
     * Display a listing of contributions.
     */
    public function index()
    {
        $year = now()->year;

        // Aggregate contributions per member per month for the selected year
        $aggregated = Contribution::selectRaw('member_id, YEAR(contribution_date) as year, MONTH(contribution_date) as month, SUM(amount) as total')
            ->whereYear('contribution_date', $year)
            ->groupBy('member_id', 'year', 'month')
            ->get();

        $members = Member::orderBy('name')->get();

        $rows = [];
        $monthlyKeys = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];

        foreach ($members as $member) {
            $months = array_fill_keys($monthlyKeys, 0);

            $memberAgg = $aggregated->where('member_id', $member->id);

            foreach ($memberAgg as $item) {
                $index = (int) $item->month; // 1..12
                $key = $monthlyKeys[$index - 1] ?? null;
                if ($key) {
                    $months[$key] = (float) $item->total;
                }
            }

            $totalPaid = array_sum($months);

            // Assume expected monthly contribution is 250
            $expectedPerMonth = 250;
            $expectedTotal = $expectedPerMonth * 12;

            $deficit = max(0, $expectedTotal - $totalPaid);

            // Aging in months: how many months behind based on deficit
            $aging = $expectedPerMonth > 0 ? (int) floor($deficit / $expectedPerMonth) : 0;

            // Simple initials from member name (e.g. "John Doe" => "JD")
            $initials = collect(explode(' ', $member->name))
                ->filter()
                ->map(fn ($part) => strtoupper(mb_substr($part, 0, 1)))
                ->implode('');

            $rows[] = [
                'member'   => $member,
                'initials' => $initials,
                'months'   => $months,
                'deficit'  => $deficit,
                'aging'    => $aging,
            ];
        }

        return view('contributions.index', [
            'year' => $year,
            'rows' => $rows,
        ]);
    }

    /**
     * Show the form for creating a new contribution.
     */
    public function create()
    {
        $members = Member::all();
        return view('contributions.create', compact('members'));
    }

    /**
     * Store a newly created contribution in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'nullable|numeric|min:0.01',
            'contribution_date' => 'required|date'
        ]);

        // Default monthly contribution is 250 if amount not provided
        $amount = $validated['amount'] ?? 250;

        // Ensure we have a "Contributions" account
        $account = Account::firstOrCreate(
            ['slug' => 'contributions'],
            ['name' => 'Contributions Account', 'balance' => 0]
        );

        $ref = 'CONTR-' . now()->format('YmdHis') . '-' . $request->member_id;

        // Create contribution
        $contribution = Contribution::create([
            'member_id' => $request->member_id,
            'amount' => $amount,
            'contribution_date' => $request->contribution_date,
            'transaction_ref' => $ref,
        ]);

        // Record income transaction and update account balance
        Transaction::create([
            'account_id' => $account->id,
            'income' => $amount,
            'expense' => 0,
            'transaction_date' => $request->contribution_date,
        ]);

        $account->increment('balance', $amount);

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution posted successfully!');
    }

    /**
     * Show the form for editing an existing contribution.
     */
    public function edit(Contribution $contribution)
    {
        $members = Member::all();
        return view('contributions.edit', compact('contribution', 'members'));
    }

    /**
     * Update an existing contribution in storage.
     */
    public function update(Request $request, Contribution $contribution)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'nullable|numeric|min:0.01',
            'contribution_date' => 'required|date'
        ]);

        $oldAmount = $contribution->amount;
        $amount = $validated['amount'] ?? 250;

        $contribution->update([
            'member_id' => $validated['member_id'],
            'amount' => $amount,
            'contribution_date' => $validated['contribution_date'],
        ]);

        // Adjust account balance for difference
        $account = Account::firstOrCreate(
            ['slug' => 'contributions'],
            ['name' => 'Contributions Account', 'balance' => 0]
        );

        $diff = $amount - $oldAmount;
        if ($diff != 0) {
            Transaction::create([
                'account_id' => $account->id,
                'income' => $diff > 0 ? $diff : 0,
                'expense' => $diff < 0 ? abs($diff) : 0,
                'transaction_date' => $validated['contribution_date'],
            ]);

            $account->increment('balance', $diff);
        }

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution updated successfully!');
    }

    /**
     * Remove a contribution from storage.
     */
    public function destroy(Contribution $contribution)
    {
        $amount = $contribution->amount;
        $contribution->delete();

        // Reduce contributions account balance
        $account = Account::firstOrCreate(
            ['slug' => 'contributions'],
            ['name' => 'Contributions Account', 'balance' => 0]
        );

        if ($amount > 0) {
            Transaction::create([
                'account_id' => $account->id,
                'income' => 0,
                'expense' => $amount,
                'transaction_date' => now(),
            ]);

            $account->decrement('balance', $amount);
        }

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution deleted successfully!');
    }

    /**
     * Member: view only their own contributions.
     */
    public function myContributions(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->member_id) {
            abort(403, 'No member profile linked to this user.');
        }

        $member = Member::findOrFail($user->member_id);

        $contributions = Contribution::where('member_id', $member->id)
            ->orderByDesc('contribution_date')
            ->get();

        $total = $contributions->sum('amount');

        return view('contributions.my', compact('member', 'contributions', 'total'));
    }

    /**
     * Member: show payment form for own contribution.
     */
    public function payForm(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->member_id) {
            abort(403, 'No member profile linked to this user.');
        }

        $member = Member::findOrFail($user->member_id);

        return view('contributions.pay', compact('member'));
    }

    /**
     * Member: record own contribution payment.
     * (Simulated online payment – records as contribution + transaction.)
     */
    public function pay(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->member_id) {
            abort(403, 'No member profile linked to this user.');
        }

        $validated = $request->validate([
            'amount' => 'nullable|numeric|min:0.01',
            'contribution_date' => 'required|date',
        ]);

        $amount = $validated['amount'] ?? 250;

        $account = Account::firstOrCreate(
            ['slug' => 'contributions'],
            ['name' => 'Contributions Account', 'balance' => 0]
        );

        $ref = 'SELF-' . now()->format('YmdHis') . '-' . $user->member_id;

        Contribution::create([
            'member_id' => $user->member_id,
            'amount' => $amount,
            'contribution_date' => $validated['contribution_date'],
            'transaction_ref' => $ref,
        ]);

        Transaction::create([
            'account_id' => $account->id,
            'income' => $amount,
            'expense' => 0,
            'transaction_date' => $validated['contribution_date'],
        ]);

        $account->increment('balance', $amount);

        return redirect()->route('member.contributions')
            ->with('success', 'Contribution recorded successfully.');
    }

    /**
     * Show CSV import form.
     */
    public function showImportForm()
    {
        return view('contributions.import');
    }

    /**
     * Handle CSV import for historic contributions.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();

        $handle = fopen($path, 'r');
        if (! $handle) {
            return back()->withErrors(['file' => 'Unable to read uploaded file.']);
        }

        // Assume header row: member_no, amount, contribution_date
        $header = fgetcsv($handle, 0, ',');

        $added = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (count($row) < 3) {
                $skipped++;
                continue;
            }

            [$memberNo, $amount, $date] = $row;

            $member = Member::where('member_no', trim($memberNo))->first();
            if (! $member) {
                $skipped++;
                continue;
            }

            $amount = is_numeric($amount) ? (float) $amount : 0;
            if ($amount <= 0) {
                $skipped++;
                continue;
            }

            // Basic date validation
            try {
                $contributionDate = \Carbon\Carbon::parse($date)->toDateString();
            } catch (\Exception $e) {
                $skipped++;
                continue;
            }

            // Reuse store logic helpers: contributions account & transactions
            $account = Account::firstOrCreate(
                ['slug' => 'contributions'],
                ['name' => 'Contributions Account', 'balance' => 0]
            );

            $ref = 'IMPORT-' . now()->format('YmdHis') . '-' . $member->id;

            Contribution::create([
                'member_id' => $member->id,
                'amount' => $amount,
                'contribution_date' => $contributionDate,
                'transaction_ref' => $ref,
            ]);

            Transaction::create([
                'account_id' => $account->id,
                'income' => $amount,
                'expense' => 0,
                'transaction_date' => $contributionDate,
            ]);

            $account->increment('balance', $amount);
            $added++;
        }

        fclose($handle);

        return redirect()->route('contributions.index')
            ->with('import_result', ['added' => $added, 'skipped' => $skipped]);
    }
}

