<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Contribution;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;


class MemberController extends Controller
{
    /**
     * Member dashboard.
     *
     * For now, just redirect to the members list.
     * You can later replace this with a dedicated member dashboard view.
     */
    public function dashboard()
    {
        return redirect()->route('members.index');
    }

    /**
     * Display a listing of the members.
     */
    public function index()
    {
        $members = Member::all();
        return view('members.index', compact('members'));
    }

    /**
 * Display member contribution statement
 */
public function statement(Member $member)
{
    $contributions = Contribution::where('member_id', $member->id)
        ->orderBy('contribution_date', 'asc')
        ->get();

    $total = $contributions->sum('amount');

    return view('members.statement', compact(
        'member',
        'contributions',
        'total'
    ));
}


    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        // Validate the request according to the migration structure
        $validated = $request->validate([
            'member_no' => 'required|unique:members,member_no|max:50',
            'name' => 'required|string|max:150',
            'initials' => 'required|string|max:50',
            'registration_amount_paid' => 'required|string|max:191',
            'registration_fee' => 'nullable|numeric|min:0',
            'paid_to_date' => 'required|string|max:191',
            'phone' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE',
        ]);

        // Set default registration_fee if not provided
        if (!isset($validated['registration_fee']) || empty($validated['registration_fee'])) {
            $validated['registration_fee'] = 1000;
        }

        // Create the member
        Member::create(array_merge($validated, [
            'status' => $validated['status'] ?? 'ACTIVE',
        ]));

        // Redirect back to index with success message
        return redirect()->route('members.index')
                         ->with('success', 'Member created successfully!');
    }

    /**
     * Show the form for editing an existing member.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update an existing member in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'member_no' => "required|unique:members,member_no,{$member->id}|max:50",
            'name' => 'required|string|max:150',
            'initials' => 'required|string|max:50',
            'registration_amount_paid' => 'required|string|max:191',
            'registration_fee' => 'nullable|numeric|min:0',
            'paid_to_date' => 'required|string|max:191',
            'phone' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE',
        ]);

        // Set default registration_fee if not provided
        if (!isset($validated['registration_fee']) || empty($validated['registration_fee'])) {
            $validated['registration_fee'] = $member->registration_fee ?? 1000;
        }

        $member->update($validated);

        return redirect()->route('members.index')
                         ->with('success', 'Member updated successfully!');
    }

    /**
     * Remove a member from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
                         ->with('success', 'Member deleted successfully!');
    }

    /**
     * Show the form for importing members from Excel.
     */
    public function showImportForm()
    {
        return view('members.import');
    }

    /**
     * Import members from Excel/CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ]);

        try {
            $importedCount = 0;
            $errors = [];
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            // Handle CSV files
            if (strtolower($extension) === 'csv') {
                $handle = fopen($file->getRealPath(), 'r');
                if ($handle === false) {
                    throw new \Exception('Could not open file');
                }
                
                // Read header row
                $headers = fgetcsv($handle);
                if ($headers === false) {
                    fclose($handle);
                    throw new \Exception('File is empty or invalid');
                }
                
                // Remove BOM if present
                if (!empty($headers[0]) && substr($headers[0], 0, 3) === "\xEF\xBB\xBF") {
                    $headers[0] = substr($headers[0], 3);
                }
                
                // Store original headers for error messages
                $originalHeaders = $headers;
                
                // Normalize headers (trim, lowercase, remove spaces and special chars)
                $normalizedHeaders = array_map(function($h) {
                    return strtolower(trim(preg_replace('/[^a-z0-9_]/', '_', $h)));
                }, $headers);
                
                // Find column indices (try multiple variations)
                $memberNoIndex = $this->findColumnIndex($normalizedHeaders, ['member_no', 'memberno', 'member_number', 'membernumber']);
                $nameIndex = $this->findColumnIndex($normalizedHeaders, ['name', 'member_name', 'fullname', 'full_name']);
                $initialsIndex = $this->findColumnIndex($normalizedHeaders, ['initials', 'initial']);
                $regAmountIndex = $this->findColumnIndex($normalizedHeaders, ['registration_amount_paid', 'registrationamountpaid', 'reg_amount', 'regamount']);
                $regFeeIndex = $this->findColumnIndex($normalizedHeaders, ['registration_fee', 'registrationfee', 'reg_fee', 'regfee']);
                $paidToDateIndex = $this->findColumnIndex($normalizedHeaders, ['paid_to_date', 'paidtodate', 'paid', 'paidtill']);
                $phoneIndex = $this->findColumnIndex($normalizedHeaders, ['phone', 'phone_number', 'phonenumber', 'mobile', 'tel']);
                $statusIndex = $this->findColumnIndex($normalizedHeaders, ['status', 'member_status']);
                
                if ($memberNoIndex === false || $nameIndex === false || $initialsIndex === false) {
                    fclose($handle);
                    $missing = [];
                    if ($memberNoIndex === false) $missing[] = 'member_no';
                    if ($nameIndex === false) $missing[] = 'name';
                    if ($initialsIndex === false) $missing[] = 'initials';
                    $foundColumns = implode(', ', array_filter($originalHeaders, function($h) { return !empty(trim($h)); }));
                    throw new \Exception('Required columns missing: ' . implode(', ', $missing) . '. Found columns in file: ' . ($foundColumns ?: 'none'));
                }
                
                $lineNumber = 1;
                while (($row = fgetcsv($handle)) !== false) {
                    $lineNumber++;
                    
                    if (count($row) < count($headers)) {
                        continue; // Skip incomplete rows
                    }
                    
                    try {
                        $memberData = [
                            'member_no' => trim($row[$memberNoIndex] ?? ''),
                            'name' => trim($row[$nameIndex] ?? ''),
                            'initials' => trim($row[$initialsIndex] ?? ''),
                            'registration_amount_paid' => $regAmountIndex !== false ? trim($row[$regAmountIndex] ?? '0') : '0',
                            'registration_fee' => $regFeeIndex !== false ? (trim($row[$regFeeIndex] ?? '') ?: 1000) : 1000,
                            'paid_to_date' => $paidToDateIndex !== false ? trim($row[$paidToDateIndex] ?? '0') : '0',
                            'phone' => $phoneIndex !== false ? trim($row[$phoneIndex] ?? '') : null,
                            'status' => $statusIndex !== false ? (trim($row[$statusIndex] ?? '') ?: 'ACTIVE') : 'ACTIVE',
                        ];
                        
                        // Validate required fields
                        if (empty($memberData['member_no']) || empty($memberData['name']) || empty($memberData['initials'])) {
                            $errors[] = "Line {$lineNumber}: Missing required fields";
                            continue;
                        }
                        
                        // Check if member already exists
                        $existingMember = Member::where('member_no', $memberData['member_no'])->first();
                        if ($existingMember) {
                            $errors[] = "Line {$lineNumber}: Member {$memberData['member_no']} already exists";
                            continue;
                        }
                        
                        // Create member
                        $member = Member::create($memberData);
                        $importedCount++;
                        
                        // Create user account if phone is provided
                        if ($member->phone) {
                            $existingUser = User::where('phone', $member->phone)->first();
                            if (!$existingUser) {
                                User::create([
                                    'name' => $member->name,
                                    'email' => $member->member_no . '@member.local',
                                    'phone' => $member->phone,
                                    'password' => Hash::make('password'),
                                    'role' => 'member',
                                    'member_id' => $member->id,
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Line {$lineNumber}: " . $e->getMessage();
                    }
                }
                
                fclose($handle);
            } else {
                // For XLSX/XLS files, provide a helpful error message
                throw new \Exception('Excel files (.xlsx, .xls) are not supported. Please save your file as CSV format and try again. You can open your Excel file and use "Save As" to save it as CSV.');
            }

            if (count($errors) > 0) {
                return redirect()->route('members.index')
                    ->with('warning', "Imported {$importedCount} members. Some rows had errors.")
                    ->with('import_errors', $errors);
            }

            return redirect()->route('members.index')
                ->with('success', "Successfully imported {$importedCount} members!");
        } catch (\Exception $e) {
            return redirect()->route('members.import.form')
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to find column index by trying multiple possible names
     */
    private function findColumnIndex($headers, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            $index = array_search(strtolower($name), $headers);
            if ($index !== false) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Download CSV template for member import.
     */
    public function downloadTemplate()
    {
        $filename = 'members_template.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 (helps Excel display correctly)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'member_no',
                'name',
                'initials',
                'registration_amount_paid',
                'registration_fee',
                'paid_to_date',
                'phone',
                'status'
            ]);
            
            // Example rows
            fputcsv($file, ['M001', 'John Doe', 'JD', '1000', '1000', '1000', '1234567890', 'ACTIVE']);
            fputcsv($file, ['M002', 'Jane Smith', 'JS', '1000', '1000', '1000', '0987654321', 'ACTIVE']);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
