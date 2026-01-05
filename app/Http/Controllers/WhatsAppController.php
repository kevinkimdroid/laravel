<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Contribution;
use Carbon\Carbon;

class WhatsAppController extends Controller
{
    /**
     * Show WhatsApp reminder page for all members with outstanding balances
     */
    public function index()
    {
        // Calculate up to end of current month (not full year)
        $endDate = Carbon::now()->endOfMonth();
        
        $membersWithOutstanding = [];
        
        foreach (Member::whereNotNull('phone')->get() as $member) {
            // Get member's join date (first contribution = joining date)
            $startDate = $member->getJoinDate();
            
            // Previous outstanding (from join date to end of last month)
            $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
            if ($endOfLastMonth < $startDate) {
                $endOfLastMonth = $startDate->copy()->subDay();
            }
            
            $previousExpected = 0;
            $currentDate = clone $startDate;
            while ($currentDate <= $endOfLastMonth) {
                $year = $currentDate->year;
                $expectedMonthly = ($year >= 2026) ? 300 : 250;
                $previousExpected += $expectedMonthly;
                $currentDate->addMonth();
            }
            
            $previousPaid = $member->contributions()
                ->where('type', 'monthly_contribution')
                ->where('contribution_date', '>=', $startDate)
                ->where('contribution_date', '<=', $endOfLastMonth)
                ->sum('amount');
            
            $previousOutstanding = max(0, $previousExpected - $previousPaid);
            
            // Current month's outstanding (only current month)
            $currentMonth = Carbon::now()->startOfMonth();
            $currentMonthExpected = 0;
            if ($currentMonth >= $startDate) {
                $year = $currentMonth->year;
                $currentMonthExpected = ($year >= 2026) ? 300 : 250;
            }
            
            $currentMonthPaid = $member->contributions()
                ->where('type', 'monthly_contribution')
                ->where('contribution_date', '>=', $currentMonth)
                ->where('contribution_date', '<=', $endDate)
                ->sum('amount');
            
            $currentMonthOutstanding = max(0, $currentMonthExpected - $currentMonthPaid);
            
            // Total outstanding = previous + current month
            $outstanding = $previousOutstanding + $currentMonthOutstanding;
            $expectedTotal = $previousExpected + $currentMonthExpected;
            $totalPaid = $previousPaid + $currentMonthPaid;
            
            if ($outstanding > 0) {
                $months = $startDate->diffInMonths($endDate) + 1;
                $avgExpectedMonthly = $months > 0 ? $expectedTotal / $months : 250;
                $monthsBehind = $avgExpectedMonthly > 0 ? ceil($outstanding / $avgExpectedMonthly) : 0;
                
                $membersWithOutstanding[] = [
                    'member' => $member,
                    'outstanding' => $outstanding,
                    'monthsBehind' => $monthsBehind,
                    'totalPaid' => $totalPaid,
                    'expectedTotal' => $expectedTotal,
                ];
            }
        }
        
        // Sort by outstanding balance (highest first)
        usort($membersWithOutstanding, function($a, $b) {
            return $b['outstanding'] <=> $a['outstanding'];
        });
        
        return view('whatsapp.index', compact('membersWithOutstanding'));
    }
    
    /**
     * Generate WhatsApp link for a specific member
     */
    public function generateLink(Member $member)
    {
        // Get member's join date (first contribution = joining date)
        $startDate = $member->getJoinDate();
        $endDate = Carbon::now()->endOfMonth();
        
        // Previous outstanding (from join date to end of last month)
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        if ($endOfLastMonth < $startDate) {
            $endOfLastMonth = $startDate->copy()->subDay();
        }
        
        $previousExpected = 0;
        $currentDate = clone $startDate;
        while ($currentDate <= $endOfLastMonth) {
            $year = $currentDate->year;
            $expectedMonthly = ($year >= 2026) ? 300 : 250;
            $previousExpected += $expectedMonthly;
            $currentDate->addMonth();
        }
        
        $previousPaid = $member->contributions()
            ->where('type', 'monthly_contribution')
            ->where('contribution_date', '>=', $startDate)
            ->where('contribution_date', '<=', $endOfLastMonth)
            ->sum('amount');
        
        $previousOutstanding = max(0, $previousExpected - $previousPaid);
        
        // Current month's outstanding (only current month)
        $currentMonth = Carbon::now()->startOfMonth();
        $currentMonthExpected = 0;
        if ($currentMonth >= $startDate) {
            $year = $currentMonth->year;
            $currentMonthExpected = ($year >= 2026) ? 300 : 250;
        }
        
        $currentMonthPaid = $member->contributions()
            ->where('type', 'monthly_contribution')
            ->where('contribution_date', '>=', $currentMonth)
            ->where('contribution_date', '<=', $endDate)
            ->sum('amount');
        
        $currentMonthOutstanding = max(0, $currentMonthExpected - $currentMonthPaid);
        
        // Total outstanding = previous + current month
        $outstanding = $previousOutstanding + $currentMonthOutstanding;
        
        if (!$member->phone) {
            return back()->with('error', 'Member does not have a phone number.');
        }
        
        if ($outstanding <= 0) {
            return back()->with('info', 'Member has no outstanding balance.');
        }
        
        // Generate WhatsApp message
        $message = "Hello {$member->name}, this is a reminder that you have an outstanding balance of KES " . number_format($outstanding, 2) . " for your monthly contributions. Please make a payment at your earliest convenience. Thank you!";
        
        // Clean phone number (remove non-numeric characters)
        $phoneNumber = preg_replace('/[^0-9]/', '', $member->phone);
        
        // Generate WhatsApp link
        $whatsappLink = "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
        
        return redirect($whatsappLink);
    }
    
    /**
     * Send bulk WhatsApp reminders (opens multiple WhatsApp windows)
     */
    public function sendBulkReminders(Request $request)
    {
        $memberIds = $request->input('member_ids', []);
        
        if (empty($memberIds)) {
            return back()->with('error', 'Please select at least one member.');
        }
        
        $members = Member::whereIn('id', $memberIds)->whereNotNull('phone')->get();
        // Calculate up to end of current month (not full year)
        $endDate = Carbon::now()->endOfMonth();
        
        $links = [];
        
        foreach ($members as $member) {
            // Get member's join date (first contribution = joining date)
            $startDate = $member->getJoinDate();
            
            // Previous outstanding (from join date to end of last month)
            $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
            if ($endOfLastMonth < $startDate) {
                $endOfLastMonth = $startDate->copy()->subDay();
            }
            
            $previousExpected = 0;
            $currentDate = clone $startDate;
            while ($currentDate <= $endOfLastMonth) {
                $year = $currentDate->year;
                $expectedMonthly = ($year >= 2026) ? 300 : 250;
                $previousExpected += $expectedMonthly;
                $currentDate->addMonth();
            }
            
            $previousPaid = $member->contributions()
                ->where('type', 'monthly_contribution')
                ->where('contribution_date', '>=', $startDate)
                ->where('contribution_date', '<=', $endOfLastMonth)
                ->sum('amount');
            
            $previousOutstanding = max(0, $previousExpected - $previousPaid);
            
            // Current month's outstanding (only current month)
            $currentMonth = Carbon::now()->startOfMonth();
            $currentMonthExpected = 0;
            if ($currentMonth >= $startDate) {
                $year = $currentMonth->year;
                $currentMonthExpected = ($year >= 2026) ? 300 : 250;
            }
            
            $currentMonthPaid = $member->contributions()
                ->where('type', 'monthly_contribution')
                ->where('contribution_date', '>=', $currentMonth)
                ->where('contribution_date', '<=', $endDate)
                ->sum('amount');
            
            $currentMonthOutstanding = max(0, $currentMonthExpected - $currentMonthPaid);
            
            // Total outstanding = previous + current month
            $outstanding = $previousOutstanding + $currentMonthOutstanding;
            
            if ($outstanding > 0) {
                $message = "Hello {$member->name}, this is a reminder that you have an outstanding balance of KES " . number_format($outstanding, 2) . " for your monthly contributions. Please make a payment at your earliest convenience. Thank you!";
                $phoneNumber = preg_replace('/[^0-9]/', '', $member->phone);
                $links[] = "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
            }
        }
        
        return view('whatsapp.bulk', compact('links'));
    }
}

