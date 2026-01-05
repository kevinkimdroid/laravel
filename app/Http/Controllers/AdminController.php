<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Member;
use App\Models\PaymentRequest;
use App\Models\Contribution;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        $pendingUsers = User::whereNull('member_id')
            ->where('role', 'member')
            ->get();
        
        $totalMembers = Member::count();
        $totalUsers = User::count();
        $pendingApprovals = $pendingUsers->count();
        $pendingPayments = PaymentRequest::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact('pendingUsers', 'totalMembers', 'totalUsers', 'pendingApprovals', 'pendingPayments'));
    }

    /**
     * Approve a user by linking them to a member.
     */
    public function approveUser(Request $request, User $user)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        // Check if member is already linked to another user
        $existingUser = User::where('member_id', $request->member_id)
            ->where('id', '!=', $user->id)
            ->first();

        if ($existingUser) {
            return back()->with('error', 'This member is already linked to another user.');
        }

        $user->member_id = $request->member_id;
        $user->save();

        return back()->with('success', 'User approved and linked to member successfully!');
    }

    /**
     * Get members for approval dropdown.
     */
    public function getAvailableMembers()
    {
        $members = Member::whereDoesntHave('users')->get();

        return response()->json($members);
    }

    /**
     * Display pending payment requests.
     */
    public function paymentRequests()
    {
        $pendingRequests = PaymentRequest::with('member')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $approvedRequests = PaymentRequest::with('member', 'approver')
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('admin.payment-requests', compact('pendingRequests', 'approvedRequests'));
    }

    /**
     * Approve a payment request and create contribution.
     */
    public function approvePayment(Request $request, PaymentRequest $paymentRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($paymentRequest->status !== 'pending') {
            return back()->with('error', 'This payment request has already been processed.');
        }

        DB::beginTransaction();
        try {
            // Create contribution
            $ref = 'MPESA-' . $paymentRequest->mpesa_code . '-' . now()->format('YmdHis');
            
            Contribution::create([
                'member_id' => $paymentRequest->member_id,
                'amount' => $paymentRequest->amount,
                'type' => $paymentRequest->type,
                'contribution_date' => $paymentRequest->payment_date,
                'transaction_ref' => $ref,
            ]);

            // Record transaction
            $account = Account::firstOrCreate(
                ['slug' => 'contributions'],
                ['name' => 'Contributions Account', 'balance' => 0]
            );

            Transaction::create([
                'account_id' => $account->id,
                'income' => $paymentRequest->amount,
                'expense' => 0,
                'transaction_date' => $paymentRequest->payment_date,
            ]);

            $account->increment('balance', $paymentRequest->amount);

            // Update payment request
            $paymentRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            DB::commit();

            return back()->with('success', 'Payment approved and contribution recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve payment: ' . $e->getMessage());
        }
    }

    /**
     * Reject a payment request.
     */
    public function rejectPayment(Request $request, PaymentRequest $paymentRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        if ($paymentRequest->status !== 'pending') {
            return back()->with('error', 'This payment request has already been processed.');
        }

        $paymentRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Payment request rejected.');
    }
}
