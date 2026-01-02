<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Member;
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
        
        return view('admin.dashboard', compact('pendingUsers', 'totalMembers', 'totalUsers', 'pendingApprovals'));
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
}
