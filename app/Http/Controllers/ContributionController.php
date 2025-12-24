<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Member;

class ContributionController extends Controller
{
    /**
     * Display a listing of contributions.
     */
    public function index()
    {
        $contributions = Contribution::with('member')->get();
        return view('contributions.index', compact('contributions'));
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
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0.01',
            'contribution_date' => 'required|date'
        ]);

        // Create contribution
        Contribution::create([
            'member_id' => $request->member_id,
            'amount' => $request->amount,
            'contribution_date' => $request->contribution_date,
        ]);

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
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0.01',
            'contribution_date' => 'required|date'
        ]);

        $contribution->update($request->only(['member_id','amount','contribution_date']));

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution updated successfully!');
    }

    /**
     * Remove a contribution from storage.
     */
    public function destroy(Contribution $contribution)
    {
        $contribution->delete();

        return redirect()->route('contributions.index')
                         ->with('success', 'Contribution deleted successfully!');
    }
}

