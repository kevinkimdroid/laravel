<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Contribution;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Download outstanding balances report.
     * Shows only men with outstanding balances calculated as of the current month (not entire year).
     */
    public function outstandingBalances()
    {
        $members = Member::with('contributions')->get();
        // Calculate as of the current month (end of current month)
        $asOfDate = Carbon::now()->endOfMonth();
        
        $reportData = [];
        
        foreach ($members as $member) {
            // Get member's join date (first contribution date - this is their joining date)
            $memberStartDate = $member->getJoinDate();
            
            // Skip if join date is in the future (shouldn't happen, but safety check)
            if ($memberStartDate > $asOfDate) {
                continue;
            }
            
            // Calculate previous outstanding balance (from join date to end of LAST month)
            $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
            if ($endOfLastMonth < $memberStartDate) {
                $endOfLastMonth = $memberStartDate->copy()->subDay(); // No previous months
            }
            
            $previousExpected = 0;
            $currentDate = clone $memberStartDate;
            
            // Calculate expected from join date to end of last month (previous balance)
            while ($currentDate <= $endOfLastMonth) {
                $year = $currentDate->year;
                $expectedMonthly = ($year >= 2026) ? 300 : 250;
                $previousExpected += $expectedMonthly;
                $currentDate->addMonth();
            }
            
            // Get previous payments (up to last month)
            $previousPaid = $member->contributions()
                ->where('type', 'monthly_contribution')
                ->where('contribution_date', '>=', $memberStartDate)
                ->where('contribution_date', '<=', $endOfLastMonth)
                ->sum('amount');
            
            $previousOutstanding = max(0, $previousExpected - $previousPaid);
            
            // Calculate current month's expected (only current month)
            $currentMonth = Carbon::now()->startOfMonth();
            $currentMonthExpected = 0;
            if ($currentMonth >= $memberStartDate && $currentMonth <= $asOfDate) {
                $year = $currentMonth->year;
                $currentMonthExpected = ($year >= 2026) ? 300 : 250;
            }
            
            // Get current month's payments
            $currentMonthPaid = $member->contributions()
                ->where('type', 'monthly_contribution')
                ->where('contribution_date', '>=', $currentMonth)
                ->where('contribution_date', '<=', $asOfDate)
                ->sum('amount');
            
            $currentMonthOutstanding = max(0, $currentMonthExpected - $currentMonthPaid);
            
            // Total outstanding = previous outstanding + current month outstanding
            $outstanding = $previousOutstanding + $currentMonthOutstanding;
            
            // Total expected and paid for display
            $expectedTotal = $previousExpected + $currentMonthExpected;
            $totalPaid = $previousPaid + $currentMonthPaid;
            
            // Only include men with unpaid balances (outstanding > 0)
            if ($outstanding > 0) {
                // Calculate average expected monthly for months behind calculation
                $months = $memberStartDate->diffInMonths($asOfDate) + 1;
                $avgExpectedMonthly = $months > 0 ? $expectedTotal / $months : 250;
                
                $reportData[] = [
                    'member_no' => $member->member_no,
                    'name' => $member->name,
                    'initials' => $member->initials,
                    'expected_total' => $expectedTotal,
                    'total_paid' => $totalPaid,
                    'outstanding' => $outstanding,
                    'months_behind' => $avgExpectedMonthly > 0 ? ceil($outstanding / $avgExpectedMonthly) : 0,
                ];
            }
        }
        
        // Sort by outstanding balance (highest first)
        usort($reportData, function($a, $b) {
            return $b['outstanding'] <=> $a['outstanding'];
        });
        
        $filename = 'men_outstanding_balances_as_of_' . Carbon::now()->format('Y-m') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportData, $asOfDate) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'Member No',
                'Name',
                'Initials',
                'Expected Total (As of ' . $asOfDate->format('F Y') . ')',
                'Total Paid',
                'Outstanding Balance (As of ' . $asOfDate->format('F Y') . ')',
                'Months Behind'
            ]);
            
            // Add a note row explaining the calculation
            fputcsv($file, [
                '',
                '',
                '',
                'Note: Outstanding balances calculated from join date to end of ' . $asOfDate->format('F Y') . ' (current month)',
                '',
                '',
                ''
            ]);
            
            // Data rows
            foreach ($reportData as $row) {
                fputcsv($file, [
                    $row['member_no'],
                    $row['name'],
                    $row['initials'],
                    number_format($row['expected_total'], 2),
                    number_format($row['total_paid'], 2),
                    number_format($row['outstanding'], 2),
                    $row['months_behind']
                ]);
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Download best contributing members report.
     */
    public function bestContributors()
    {
        $members = Member::with('contributions')->get();
        
        $reportData = [];
        
        foreach ($members as $member) {
            // Get monthly contributions only (exclude registration fees for ranking)
            $monthlyContributions = $member->contributions()->where('type', 'monthly_contribution')->get();
            $totalPaid = $monthlyContributions->sum('amount');
            $contributionCount = $monthlyContributions->count();
            $lastContribution = $monthlyContributions->max('contribution_date');
            
            // Also get registration fee info
            $registrationFee = $member->contributions()->where('type', 'registration_fee')->sum('amount');
            $totalAllContributions = $member->contributions->sum('amount');
            
            if ($totalPaid > 0) {
                $reportData[] = [
                    'member_no' => $member->member_no,
                    'name' => $member->name,
                    'initials' => $member->initials,
                    'total_contributions' => $contributionCount,
                    'total_amount' => $totalPaid,
                    'registration_fee' => $registrationFee,
                    'total_all' => $totalAllContributions,
                    'average_amount' => $contributionCount > 0 ? $totalPaid / $contributionCount : 0,
                    'last_contribution' => $lastContribution ? Carbon::parse($lastContribution)->format('Y-m-d') : 'N/A',
                ];
            }
        }
        
        // Sort by total amount (highest first)
        usort($reportData, function($a, $b) {
            return $b['total_amount'] <=> $a['total_amount'];
        });
        
        $filename = 'best_contributors_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportData) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'Rank',
                'Member No',
                'Name',
                'Initials',
                'Monthly Contributions Count',
                'Monthly Contributions Total',
                'Registration Fee',
                'Total All Contributions',
                'Average Monthly Amount',
                'Last Contribution'
            ]);
            
            // Data rows
            $rank = 1;
            foreach ($reportData as $row) {
                fputcsv($file, [
                    $rank++,
                    $row['member_no'],
                    $row['name'],
                    $row['initials'],
                    $row['total_contributions'],
                    number_format($row['total_amount'], 2),
                    number_format($row['registration_fee'], 2),
                    number_format($row['total_all'], 2),
                    number_format($row['average_amount'], 2),
                    $row['last_contribution']
                ]);
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}

