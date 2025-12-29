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
     * Expected CSV format with columns: member_no, amount, contribution_date (YYYY-MM-DD)
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

        // Read header row and map column indices
        $header = fgetcsv($handle, 0, ',');
        if (!$header || count($header) < 3) {
            fclose($handle);
            return back()->withErrors(['file' => 'CSV file must have a header row with columns: member_no, amount, contribution_date (registration_fee is optional)']);
        }

        // Normalize header (trim and lowercase for case-insensitive matching)
        $header = array_map(function($col) {
            return strtolower(trim($col));
        }, $header);

        // Find column indices
        $memberNoIndex = array_search('member_no', $header);
        $amountIndex = array_search('amount', $header);
        $dateIndex = array_search('contribution_date', $header);
        $registrationFeeIndex = array_search('registration_fee', $header); // Optional column

        // Validate required columns exist
        if ($memberNoIndex === false || $amountIndex === false || $dateIndex === false) {
            fclose($handle);
            $missing = [];
            if ($memberNoIndex === false) $missing[] = 'member_no';
            if ($amountIndex === false) $missing[] = 'amount';
            if ($dateIndex === false) $missing[] = 'contribution_date';
            return back()->withErrors(['file' => 'CSV file is missing required columns: ' . implode(', ', $missing)]);
        }

        $added = 0;
        $skipped = 0;
        $errors = [];

        // Get or create contributions account
        $account = Account::firstOrCreate(
            ['slug' => 'contributions'],
            ['name' => 'Contributions Account', 'balance' => 0]
        );

        $lineNumber = 1; // Header is line 1

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Validate row has enough columns
            if (count($row) <= max($memberNoIndex, $amountIndex, $dateIndex)) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Insufficient columns";
                continue;
            }

            // Extract values by column index
            $memberNo = trim($row[$memberNoIndex] ?? '');
            $amount = trim($row[$amountIndex] ?? '');
            $date = trim($row[$dateIndex] ?? '');

            // Validate member_no
            if (empty($memberNo)) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Missing member_no";
                continue;
            }

            $member = Member::where('member_no', $memberNo)->first();
            if (! $member) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Member with member_no '{$memberNo}' not found";
                continue;
            }

            // Handle registration_fee if present in CSV (optional column)
            if ($registrationFeeIndex !== false && isset($row[$registrationFeeIndex])) {
                $registrationFee = trim($row[$registrationFeeIndex]);
                if (!empty($registrationFee) && is_numeric($registrationFee)) {
                    $member->registration_fee = (float) $registrationFee;
                    $member->save();
                }
            }

            // Validate amount
            if (empty($amount) || !is_numeric($amount)) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Invalid amount '{$amount}'";
                continue;
            }

            $amount = (float) $amount;
            if ($amount <= 0) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Amount must be greater than 0";
                continue;
            }

            // Validate date (expecting YYYY-MM-DD format)
            if (empty($date)) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Missing contribution_date";
                continue;
            }

            try {
                // Try to parse date - accept YYYY-MM-DD format
                $contributionDate = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
                if ($contributionDate->format('Y-m-d') !== $date) {
                    throw new \Exception('Date format mismatch');
                }
                $contributionDate = $contributionDate->toDateString();
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Invalid date format '{$date}' (expected YYYY-MM-DD)";
                continue;
            }

            // Check if contribution already exists (optional: prevent duplicates)
            $exists = Contribution::where('member_id', $member->id)
                ->where('contribution_date', $contributionDate)
                ->where('amount', $amount)
                ->exists();

            if ($exists) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Contribution already exists for member {$memberNo} on {$contributionDate}";
                continue;
            }

            // Create contribution
            $ref = 'IMPORT-' . now()->format('YmdHis') . '-' . $member->id . '-' . $lineNumber;

            try {
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
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Error saving contribution - " . $e->getMessage();
            }
        }

        fclose($handle);

        $result = [
            'added' => $added,
            'skipped' => $skipped,
            'errors' => array_slice($errors, 0, 50) // Limit to first 50 errors
        ];

        return redirect()->route('contributions.index')
            ->with('import_result', $result);
    }
}

