@extends('layouts.app')

@section('title', 'Calendar Activities')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-calendar-event me-2"></i>Calendar Activities
                </h3>
                <form method="GET" action="{{ auth()->user() && auth()->user()->role === 'admin' ? route('calendar-activities.index') : route('member.calendar') }}" class="d-inline-flex align-items-center mt-2">
                    <label for="year" class="form-label me-2 mb-0 fw-semibold">Year:</label>
                    <select name="year" id="year" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        @for($y = now()->year + 1; $y >= now()->year - 1; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>
            @if(auth()->user() && auth()->user()->role === 'admin')
            <div class="mt-3 mt-md-0">
                <a href="{{ route('calendar-activities.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add Activity
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-white" style="font-weight: 600;">Date</th>
                        <th class="text-white" style="font-weight: 600;">Title</th>
                        <th class="text-white" style="font-weight: 600;">Time</th>
                        <th class="text-white" style="font-weight: 600;">Venue</th>
                        <th class="text-white" style="font-weight: 600;">Type</th>
                        <th class="text-white" style="font-weight: 600;">Status</th>
                        @if(auth()->user() && auth()->user()->role === 'admin')
                        <th class="text-white" style="font-weight: 600;">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        <tr>
                            <td class="fw-semibold">{{ $activity->activity_date->format('M d, Y') }}</td>
                            <td class="fw-semibold">{{ $activity->title }}</td>
                            <td>
                                @if($activity->start_time)
                                    {{ \Carbon\Carbon::parse($activity->start_time)->format('h:i A') }}
                                    @if($activity->end_time)
                                        - {{ \Carbon\Carbon::parse($activity->end_time)->format('h:i A') }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $activity->venue ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($activity->type) }}</span>
                            </td>
                            <td>
                                @if($activity->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($activity->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">Scheduled</span>
                                @endif
                            </td>
                            @if(auth()->user() && auth()->user()->role === 'admin')
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('calendar-activities.edit', $activity->id) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('calendar-activities.destroy', $activity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this activity?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user() && auth()->user()->role === 'admin' ? '7' : '6' }}" class="text-center py-5">
                                <i class="bi bi-calendar-x display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No calendar activities found for {{ $year }}.</p>
                                @if(auth()->user() && auth()->user()->role === 'admin')
                                <a href="{{ route('calendar-activities.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Add First Activity
                                </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
