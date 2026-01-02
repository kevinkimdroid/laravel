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
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $year = (int) $year; // Ensure it's an integer

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
            return redirect()->route('members.pending-approval');
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
            return redirect()->route('members.pending-approval');
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
            return redirect()->route('members.pending-approval');
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
     * Download CSV template file.
     */
    public function downloadTemplate()
    {
        $filename = 'contributions_import_template.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 (helps Excel display correctly)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row with monthly columns
            fputcsv($file, [
                'member_no', 
                'member_name',
                'initials', 
                'year', 
                'jan', 
                'feb', 
                'mar', 
                'apr', 
                'may', 
                'jun', 
                'jul', 
                'aug', 
                'sep', 
                'oct', 
                'nov', 
                'dec', 
                'registration_fee'
            ]);
            
            // Example rows
            fputcsv($file, ['M001', 'John Doe', 'JD', '2024', '250.00', '250.00', '250.00', '250.00', '250.00', '250.00', '250.00', '250.00', '250.00', '250.00', '250.00', '250.00', '1000.00']);
            fputcsv($file, ['M002', 'Sarah Miller', 'SM', '2024', '250.00', '250.00', '500.00', '250.00', '', '', '', '', '', '', '', '', '1000.00']);
            fputcsv($file, ['M003', 'Alex Brown', 'AB', '2024', '', '', '', '', '', '', '', '', '', '', '', '', '']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Handle CSV import for historic contributions.
     * Expected CSV format with columns: member_no, initials, year, jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec, registration_fee
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();

        // Try to detect and handle BOM (Byte Order Mark) and encoding issues
        $content = file_get_contents($path);
        
        // Remove BOM if present
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
            file_put_contents($path, $content);
        }

        // Try different delimiters (comma, semicolon, tab)
        $delimiters = [',', ';', "\t"];
        $handle = null;
        $header = null;
        $usedDelimiter = ',';

        foreach ($delimiters as $delimiter) {
            $handle = fopen($path, 'r');
            if (!$handle) {
                continue;
            }
            
            $testHeader = fgetcsv($handle, 0, $delimiter);
            fclose($handle);
            
            if ($testHeader && count($testHeader) > 3) {
                $header = $testHeader;
                $usedDelimiter = $delimiter;
                break;
            }
        }

        if (!$header || count($header) < 3) {
            return back()->withErrors(['file' => 'CSV file must have a header row. Please ensure your file is saved as CSV (comma-separated) format.']);
        }

        // Normalize header (trim, remove BOM, and lowercase for case-insensitive matching)
        $header = array_map(function($col) {
            // Remove BOM and other invisible characters
            $col = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col);
            $col = trim($col);
            $col = trim($col, "\xEF\xBB\xBF"); // Remove BOM if still present
            return strtolower($col);
        }, $header);

        // Reopen file with detected delimiter
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->withErrors(['file' => 'Unable to read uploaded file.']);
        }

        // Skip BOM if present
        $firstChar = fread($handle, 3);
        if ($firstChar !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Read header again with correct delimiter
        $header = fgetcsv($handle, 0, $usedDelimiter);
        $header = array_map(function($col) {
            $col = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col);
            $col = trim($col);
            $col = trim($col, "\xEF\xBB\xBF");
            return strtolower($col);
        }, $header);

        // Find column indices
        $memberNoIndex = array_search('member_no', $header);
        $memberNameIndex = array_search('member_name', $header);
        $initialsIndex = array_search('initials', $header);
        $yearIndex = array_search('year', $header);
        $monthIndices = [
            'jan' => array_search('jan', $header),
            'feb' => array_search('feb', $header),
            'mar' => array_search('mar', $header),
            'apr' => array_search('apr', $header),
            'may' => array_search('may', $header),
            'jun' => array_search('jun', $header),
            'jul' => array_search('jul', $header),
            'aug' => array_search('aug', $header),
            'sep' => array_search('sep', $header),
            'oct' => array_search('oct', $header),
            'nov' => array_search('nov', $header),
            'dec' => array_search('dec', $header),
        ];
        $registrationFeeIndex = array_search('registration_fee', $header); // Optional column

        // Validate required columns exist
        if ($memberNoIndex === false || $yearIndex === false) {
            fclose($handle);
            $missing = [];
            if ($memberNoIndex === false) $missing[] = 'member_no';
            if ($yearIndex === false) $missing[] = 'year';
            
            // Show what columns were actually found for debugging
            $foundColumns = implode(', ', array_filter($header, function($col) {
                return !empty($col);
            }));
            
            return back()->withErrors([
                'file' => 'CSV file is missing required columns: ' . implode(', ', $missing) . 
                         '. Found columns: ' . ($foundColumns ?: 'none') . 
                         '. Please ensure your CSV has a header row with: member_no, member_name (optional), initials (optional), year, jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec, registration_fee (optional)'
            ]);
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
        $monthNames = ['jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
                      'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12];

        while (($row = fgetcsv($handle, 0, $usedDelimiter)) !== false) {
            $lineNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Extract values by column index
            $memberNo = trim($row[$memberNoIndex] ?? '');
            $memberName = $memberNameIndex !== false ? trim($row[$memberNameIndex] ?? '') : '';
            $initials = $initialsIndex !== false ? trim($row[$initialsIndex] ?? '') : '';
            $year = trim($row[$yearIndex] ?? '');

            // Validate member_no
            if (empty($memberNo)) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Missing member_no";
                continue;
            }

            // Validate year
            if (empty($year) || !is_numeric($year)) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Invalid or missing year '{$year}'";
                continue;
            }

            $year = (int) $year;
            if ($year < 2000 || $year > (now()->year + 1)) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Year '{$year}' is out of valid range";
                continue;
            }

            $member = Member::where('member_no', $memberNo)->first();
            if (! $member) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: Member with member_no '{$memberNo}' not found";
                continue;
            }

            // Update member name if provided
            if (!empty($memberName)) {
                $member->name = $memberName;
            }

            // Update initials if provided
            if (!empty($initials)) {
                $member->initials = $initials;
            }

            // Save member updates if any changes were made
            if (!empty($memberName) || !empty($initials)) {
                $member->save();
            }

            // Handle registration_fee if present in CSV (optional column)
            if ($registrationFeeIndex !== false && isset($row[$registrationFeeIndex])) {
                $registrationFee = trim($row[$registrationFeeIndex]);
                if (!empty($registrationFee) && is_numeric($registrationFee)) {
                    $member->registration_fee = (float) $registrationFee;
                    $member->save();
                }
            }

            // Process monthly contributions
            $monthlyContributions = 0;
            foreach ($monthIndices as $monthName => $monthIndex) {
                if ($monthIndex === false) {
                    continue; // Skip if column not found
                }

                $monthAmount = trim($row[$monthIndex] ?? '');
                
                // Skip empty months
                if (empty($monthAmount)) {
                    continue;
                }

                // Validate amount
                if (!is_numeric($monthAmount)) {
                    $errors[] = "Line {$lineNumber}: Invalid amount '{$monthAmount}' for {$monthName}";
                    continue;
                }

                $amount = (float) $monthAmount;
                if ($amount <= 0) {
                    continue; // Skip zero or negative amounts
                }

                // Create date for this month (first day of the month)
                $monthNumber = $monthNames[$monthName];
                try {
                    $contributionDate = \Carbon\Carbon::create($year, $monthNumber, 15)->toDateString();
                } catch (\Exception $e) {
                    $errors[] = "Line {$lineNumber}: Invalid date for {$monthName} in year {$year}";
                    continue;
                }

                // Check if contribution already exists for this member/month/year
                $exists = Contribution::where('member_id', $member->id)
                    ->whereYear('contribution_date', $year)
                    ->whereMonth('contribution_date', $monthNumber)
                    ->exists();

                if ($exists) {
                    // Update existing contribution instead of skipping
                    $existing = Contribution::where('member_id', $member->id)
                        ->whereYear('contribution_date', $year)
                        ->whereMonth('contribution_date', $monthNumber)
                        ->first();
                    
                    if ($existing) {
                        $oldAmount = $existing->amount;
                        $diff = $amount - $oldAmount;
                        
                        $existing->update(['amount' => $amount]);
                        
                        if ($diff != 0) {
                            Transaction::create([
                                'account_id' => $account->id,
                                'income' => $diff > 0 ? $diff : 0,
                                'expense' => $diff < 0 ? abs($diff) : 0,
                                'transaction_date' => $contributionDate,
                            ]);
                            $account->increment('balance', $diff);
                        }
                        $monthlyContributions++;
                    }
                } else {
                    // Create new contribution
                    $ref = 'IMPORT-' . now()->format('YmdHis') . '-' . $member->id . '-' . $lineNumber . '-' . $monthName;

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
                        $monthlyContributions++;
                        $added++;
                    } catch (\Exception $e) {
                        $errors[] = "Line {$lineNumber}: Error saving contribution for {$monthName} - " . $e->getMessage();
                    }
                }
            }

            if ($monthlyContributions == 0) {
                $skipped++;
                $errors[] = "Line {$lineNumber}: No valid monthly contributions found for member {$memberNo}";
            }
        }

        fclose($handle);

        $result = [
            'added' => $added,
            'skipped' => $skipped,
            'errors' => array_slice($errors, 0, 50), // Limit to first 50 errors
        ];

        return redirect()->route('contributions.index')
            ->with('import_result', $result);
    }
}

