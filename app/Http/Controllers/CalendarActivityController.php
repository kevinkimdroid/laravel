<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarActivity;
use Carbon\Carbon;

class CalendarActivityController extends Controller
{
    /**
     * Display a listing of calendar activities.
     */
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $year = (int) $year;

        $activities = CalendarActivity::whereYear('activity_date', $year)
            ->orderBy('activity_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('calendar-activities.index', compact('activities', 'year'));
    }

    /**
     * Show the form for creating a new calendar activity.
     */
    public function create()
    {
        return view('calendar-activities.create');
    }

    /**
     * Store a newly created calendar activity.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            // Allow events that start and end at the same time on the same day
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'venue' => 'nullable|string|max:255',
            'type' => 'required|string|in:general,meeting,event,game,training,other',
            'status' => 'required|string|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        CalendarActivity::create($validated);

        return redirect()->route('calendar-activities.index')
            ->with('success', 'Calendar activity created successfully!');
    }

    /**
     * Display the specified calendar activity.
     */
    public function show(CalendarActivity $calendarActivity)
    {
        return view('calendar-activities.show', compact('calendarActivity'));
    }

    /**
     * Show the form for editing the specified calendar activity.
     */
    public function edit(CalendarActivity $calendarActivity)
    {
        return view('calendar-activities.edit', compact('calendarActivity'));
    }

    /**
     * Update the specified calendar activity.
     */
    public function update(Request $request, CalendarActivity $calendarActivity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            // Allow events that start and end at the same time on the same day
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'venue' => 'nullable|string|max:255',
            'type' => 'required|string|in:general,meeting,event,game,training,other',
            'status' => 'required|string|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $calendarActivity->update($validated);

        return redirect()->route('calendar-activities.index')
            ->with('success', 'Calendar activity updated successfully!');
    }

    /**
     * Remove the specified calendar activity.
     */
    public function destroy(CalendarActivity $calendarActivity)
    {
        $calendarActivity->delete();

        return redirect()->route('calendar-activities.index')
            ->with('success', 'Calendar activity deleted successfully!');
    }
}
