<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Contribution;


class MemberController extends Controller
{
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
        // Validate the request
        $request->validate([
            'member_no' => 'required|unique:members,member_no|max:50',
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE'
        ]);

        // Create the member
        Member::create([
            'member_no' => $request->member_no,
            'name' => $request->name,
            'phone' => $request->phone,
            'status' => $request->status ?? 'ACTIVE',
        ]);

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
        $request->validate([
            'member_no' => "required|unique:members,member_no,{$member->id}|max:50",
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE'
        ]);

        $member->update($request->only(['member_no', 'name', 'phone', 'status']));

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
}
