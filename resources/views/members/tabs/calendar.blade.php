<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary bg-opacity-10 border-0">
        <h5 class="mb-0 fw-bold text-primary">
            <i class="bi bi-calendar-event me-2"></i>Upcoming Events & Activities
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-0">
            <i class="bi bi-info-circle me-1"></i>
            View all scheduled and upcoming calendar activities for the club.
        </p>
    </div>
</div>

@if($upcomingActivities && $upcomingActivities->count() > 0)
<div class="row g-4">
    @foreach($upcomingActivities as $activity)
        @php
            $isToday = $activity->activity_date->isToday();
            $isTomorrow = $activity->activity_date->isTomorrow();
            $isThisWeek = $activity->activity_date->isCurrentWeek();
            $daysUntil = now()->diffInDays($activity->activity_date, false);
        @endphp

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 
                @if($isToday) border-start border-4 border-warning @endif
                @if($isTomorrow) border-start border-4 border-info @endif
                @if($daysUntil < 0) opacity-75 @endif">
                <div class="card-body p-4">
                    <!-- Date Badge -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @if($isToday)
                                <span class="badge bg-warning text-dark mb-2">
                                    <i class="bi bi-star-fill me-1"></i>Today
                                </span>
                            @elseif($isTomorrow)
                                <span class="badge bg-info mb-2">
                                    <i class="bi bi-calendar-check me-1"></i>Tomorrow
                                </span>
                            @elseif($daysUntil > 0 && $daysUntil <= 7)
                                <span class="badge bg-success mb-2">
                                    <i class="bi bi-calendar-week me-1"></i>This Week
                                </span>
                            @elseif($daysUntil > 7)
                                <span class="badge bg-secondary mb-2">
                                    <i class="bi bi-calendar me-1"></i>{{ $daysUntil }} days
                                </span>
                            @else
                                <span class="badge bg-secondary mb-2">
                                    <i class="bi bi-clock-history me-1"></i>Past
                                </span>
                            @endif
                        </div>
                        <span class="badge bg-{{ $activity->status === 'completed' ? 'success' : ($activity->status === 'cancelled' ? 'danger' : 'primary') }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>

                    <!-- Activity Title -->
                    <h5 class="card-title fw-bold mb-2 text-primary">
                        {{ $activity->title }}
                    </h5>

                    <!-- Date & Time -->
                    <div class="mb-3">
                        <p class="mb-1 text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>
                            <strong>{{ $activity->activity_date->format('l, F d, Y') }}</strong>
                        </p>
                        @if($activity->start_time)
                            <p class="mb-0 text-muted small">
                                <i class="bi bi-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($activity->start_time)->format('h:i A') }}
                                @if($activity->end_time)
                                    - {{ \Carbon\Carbon::parse($activity->end_time)->format('h:i A') }}
                                @endif
                            </p>
                        @endif
                    </div>

                    <!-- Venue -->
                    @if($activity->venue)
                        <p class="mb-2 text-muted small">
                            <i class="bi bi-geo-alt me-1"></i>
                            <strong>Venue:</strong> {{ $activity->venue }}
                        </p>
                    @endif

                    <!-- Type Badge -->
                    <div class="mb-2">
                        <span class="badge bg-info bg-opacity-10 text-info">
                            <i class="bi bi-tag me-1"></i>{{ ucfirst($activity->type) }}
                        </span>
                    </div>

                    <!-- Description -->
                    @if($activity->description)
                        <p class="text-muted small mb-0">
                            {{ Str::limit($activity->description, 100) }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- View Full Calendar Link -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body text-center">
        <a href="{{ route('member.calendar') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-calendar3 me-2"></i>View Full Calendar
        </a>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-calendar-x display-4 text-muted d-block mb-3"></i>
        <h5 class="mb-2">No Upcoming Activities</h5>
        <p class="text-muted">There are no scheduled activities at this time. Check back later for updates!</p>
        <a href="{{ route('member.calendar') }}" class="btn btn-outline-primary mt-3">
            <i class="bi bi-calendar3 me-1"></i>View Full Calendar
        </a>
    </div>
</div>
@endif

