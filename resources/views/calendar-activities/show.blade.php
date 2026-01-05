@extends('layouts.app')

@section('title', $calendarActivity->title)

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-calendar-event me-2"></i>{{ $calendarActivity->title }}
                </h3>
                <p class="text-muted mb-0">{{ $calendarActivity->activity_date->format('F d, Y') }}</p>
            </div>
            <div>
                <a href="{{ route('calendar-activities.edit', $calendarActivity->id) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('calendar-activities.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Activity Details</h5>
                
                @if($calendarActivity->description)
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Description</h6>
                        <p>{{ $calendarActivity->description }}</p>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="text-muted small mb-1">Date</h6>
                        <p class="fw-semibold">{{ $calendarActivity->activity_date->format('l, F d, Y') }}</p>
                    </div>

                    @if($calendarActivity->start_time)
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Time</h6>
                            <p class="fw-semibold">
                                {{ \Carbon\Carbon::parse($calendarActivity->start_time)->format('h:i A') }}
                                @if($calendarActivity->end_time)
                                    - {{ \Carbon\Carbon::parse($calendarActivity->end_time)->format('h:i A') }}
                                @endif
                            </p>
                        </div>
                    @endif

                    @if($calendarActivity->venue)
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Venue</h6>
                            <p class="fw-semibold">{{ $calendarActivity->venue }}</p>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <h6 class="text-muted small mb-1">Type</h6>
                        <p><span class="badge bg-info">{{ ucfirst($calendarActivity->type) }}</span></p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted small mb-1">Status</h6>
                        <p>
                            @if($calendarActivity->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($calendarActivity->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning text-dark">Scheduled</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($calendarActivity->notes)
                    <div class="mt-3">
                        <h6 class="text-muted small mb-1">Notes</h6>
                        <p>{{ $calendarActivity->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('calendar-activities.edit', $calendarActivity->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Edit Activity
                    </a>
                    <form action="{{ route('calendar-activities.destroy', $calendarActivity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this activity?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-1"></i>Delete Activity
                        </button>
                    </form>
                    <a href="{{ route('calendar-activities.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Calendar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
