@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<!-- Hero Header -->
<div class="card border-0 shadow-lg mb-4 position-relative overflow-hidden" style="background: #000000; border-bottom: 3px solid #D4AF37; border-radius: 16px; min-height: 180px;">
    <div class="position-absolute" style="top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(212, 175, 55, 0.1); border-radius: 50%;"></div>
    <div class="position-absolute" style="bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(212, 175, 55, 0.08); border-radius: 50%;"></div>
    <div class="card-body text-white p-5 position-relative" style="z-index: 1;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-3 fw-bold text-white" style="font-size: 1.75rem;">
                    <i class="bi bi-person-circle me-2" style="color: #D4AF37;"></i>Welcome, {{ $member->name }}!
                </h2>
                <div class="d-flex gap-3 flex-wrap">
                    <span class="badge px-3 py-2" style="background: rgba(212, 175, 55, 0.2); color: #D4AF37; border: 1px solid #D4AF37;">
                        <i class="bi bi-person-badge me-1"></i>Member No: {{ $member->member_no }}
                    </span>
                    <span class="badge px-3 py-2" style="background: {{ $member->status === 'ACTIVE' ? '#D4AF37' : '#6c757d' }}; color: {{ $member->status === 'ACTIVE' ? '#000000' : '#FFFFFF' }};">
                        <i class="bi bi-check-circle me-1"></i>{{ $member->status }}
                    </span>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn mt-3 mt-md-0 shadow-sm" style="background: #D4AF37; color: #000000; border-radius: 10px;">
                <i class="bi bi-gear me-1"></i>Edit Profile
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Quick Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-cash-stack" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-check-circle"></i>
                    </span>
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">Total Paid</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.7rem; color: #000000;">KES {{ number_format($totalPaid, 0) }}</h2>
                <p class="text-muted small mb-0 mt-2">Your total contributions</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-exclamation-triangle" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    @if($outstanding > 0)
                    <span class="badge rounded-pill px-3 py-2" style="background: #D4AF37; color: #000000; font-size: 0.75rem;">
                        <i class="bi bi-exclamation"></i>
                    </span>
                    @else
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-check-circle"></i>
                    </span>
                    @endif
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">Outstanding</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.7rem; color: #000000;">KES {{ number_format($outstanding, 0) }}</h2>
                <p class="text-muted small mb-0 mt-2">Balance to be paid</p>
                @if($outstanding > 0)
                <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-sm mt-2 w-100" style="background: #D4AF37; color: #000000; border-radius: 8px;">
                    <i class="bi bi-credit-card me-1"></i>Pay Now
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-trophy" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-star-fill"></i>
                    </span>
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">QPL Points</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.7rem; color: #D4AF37;">{{ $qplStats['points'] }}</h2>
                <p class="text-muted small mb-0 mt-2">League ranking points</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-calendar-event" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-controller"></i>
                    </span>
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">Games Played</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.7rem; color: #000000;">{{ $qplStats['played'] }}</h2>
                <p class="text-muted small mb-0 mt-2">Total QPL matches</p>
            </div>
        </div>
    </div>
</div>

<!-- Progress Bar for Contributions -->
@if($expectedTotal > 0)
<div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 fw-bold">Contribution Progress</h6>
            <span class="badge px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37;">
                {{ number_format(($totalPaid / $expectedTotal) * 100, 1) }}% Complete
            </span>
        </div>
        <div class="progress" style="height: 12px; border-radius: 10px; background: rgba(212, 175, 55, 0.1);">
            <div class="progress-bar" role="progressbar" 
                 style="width: {{ ($totalPaid / $expectedTotal) * 100 }}%; background: linear-gradient(90deg, #D4AF37 0%, #f4d03f 100%); border-radius: 10px;"
                 aria-valuenow="{{ ($totalPaid / $expectedTotal) * 100 }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
            </div>
        </div>
        <div class="d-flex justify-content-between mt-2">
            <small class="text-muted">KES {{ number_format($totalPaid, 0) }} paid</small>
            <small class="text-muted">KES {{ number_format($expectedTotal, 0) }} expected</small>
        </div>
    </div>
</div>
@endif

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4" id="memberTabs" role="tablist" style="border-bottom: 2px solid #D4AF37;">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" style="border-radius: 10px 10px 0 0; border: none;">
            <i class="bi bi-info-circle me-1"></i>Overview
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contributions-tab" data-bs-toggle="tab" data-bs-target="#contributions" type="button" role="tab" style="border-radius: 10px 10px 0 0; border: none;">
            <i class="bi bi-cash-stack me-1"></i>Contributions
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="qpl-tab" data-bs-toggle="tab" data-bs-target="#qpl" type="button" role="tab" style="border-radius: 10px 10px 0 0; border: none;">
            <i class="bi bi-trophy me-1"></i>QPL Games
            @if($qplStats['played'] > 0)
                <span class="badge rounded-pill ms-1" style="background: #D4AF37; color: #000000;">{{ $qplStats['played'] }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab" style="border-radius: 10px 10px 0 0; border: none;">
            <i class="bi bi-calendar-event me-1"></i>Calendar
            @if($upcomingActivities->count() > 0)
                <span class="badge rounded-pill ms-1" style="background: #D4AF37; color: #000000;">{{ $upcomingActivities->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="qpl-standings-tab" data-bs-toggle="tab" data-bs-target="#qpl-standings" type="button" role="tab" style="border-radius: 10px 10px 0 0; border: none;">
            <i class="bi bi-trophy me-1"></i>QPL Standings
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="memberTabsContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
                    <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.05) 100%); border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person me-2" style="color: #D4AF37;"></i>Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 40%;">Member Number:</td>
                                <td class="fw-semibold">{{ $member->member_no }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Full Name:</td>
                                <td class="fw-semibold">{{ $member->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Initials:</td>
                                <td class="fw-semibold">{{ $member->initials ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phone:</td>
                                <td class="fw-semibold">{{ $member->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status:</td>
                                <td>
                                    <span class="badge" style="background: {{ $member->status === 'ACTIVE' ? '#D4AF37' : '#6c757d' }}; color: {{ $member->status === 'ACTIVE' ? '#000000' : '#FFFFFF' }};">
                                        {{ $member->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Registration:</td>
                                <td>
                                    @if($isRegistered)
                                        <span class="badge" style="background: #D4AF37; color: #000000;">
                                            <i class="bi bi-check-circle me-1"></i>Registered (KES {{ number_format($registrationFeePaid, 0) }})
                                        </span>
                                    @else
                                        <span class="badge" style="background: #6c757d; color: #FFFFFF;">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Not Registered
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-lg" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
                    <div class="card-header border-0 py-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-cash-stack me-2" style="color: #D4AF37;"></i>Contribution Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 50%;">Total Paid:</td>
                                <td class="fw-bold" style="color: #000000;">KES {{ number_format($totalPaid, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Outstanding Balance:</td>
                                <td class="fw-bold" style="color: #000000;">
                                    KES {{ number_format($outstanding, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Expected Total:</td>
                                <td class="fw-semibold">KES {{ number_format($expectedTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Registration Fee:</td>
                                <td class="fw-semibold">KES {{ number_format($registrationFeePaid, 2) }}</td>
                            </tr>
                        </table>
                        @if($outstanding > 0)
                            <div class="mt-4">
                                <a href="{{ route('member.contributions.pay.form') }}" class="btn w-100" style="background: #000000; color: #FFFFFF; border: 2px solid #D4AF37; border-radius: 10px;">
                                    <i class="bi bi-credit-card me-1"></i>Make Payment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contributions Tab -->
    <div class="tab-pane fade" id="contributions" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
            <div class="card-header border-0 py-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 16px 16px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-cash-stack me-2" style="color: #D4AF37;"></i>My Contributions
                    </h5>
                    <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-sm" style="background: #D4AF37; color: #000000; border-radius: 10px;">
                        <i class="bi bi-plus-circle me-1"></i>Make Payment
                    </a>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('member.contributions') }}" class="btn mb-3" style="background: #000000; color: #FFFFFF; border: 1px solid #D4AF37; border-radius: 10px;">
                    <i class="bi bi-arrow-right me-1"></i>View Full Contribution History
                </a>
                @if($pendingPayments && $pendingPayments->count() > 0)
                    <div class="alert border-0" style="background: rgba(212, 175, 55, 0.1); border-radius: 12px;">
                        <h6 class="fw-bold"><i class="bi bi-hourglass-split me-2" style="color: #D4AF37;"></i>Pending Payment Requests ({{ $pendingPayments->count() }})</h6>
                        <p class="mb-0 small">You have {{ $pendingPayments->count() }} payment request(s) awaiting admin approval.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- QPL Games Tab -->
    <div class="tab-pane fade" id="qpl" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
            <div class="card-header border-0 py-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-trophy me-2" style="color: #D4AF37;"></i>QBASH Pool League Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                            <h3 class="fw-bold mb-2" style="font-size: 2rem; color: #000000;">{{ $qplStats['played'] }}</h3>
                            <small class="text-muted fw-semibold">Games Played</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                            <h3 class="fw-bold mb-2" style="font-size: 2rem; color: #D4AF37;">{{ $qplStats['won'] }}</h3>
                            <small class="text-muted fw-semibold">Wins</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                            <h3 class="fw-bold mb-2" style="font-size: 2rem; color: #000000;">{{ $qplStats['drawn'] }}</h3>
                            <small class="text-muted fw-semibold">Draws</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                            <h3 class="fw-bold mb-2" style="font-size: 2rem; color: #000000;">{{ $qplStats['lost'] }}</h3>
                            <small class="text-muted fw-semibold">Losses</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                            <h4 class="fw-bold mb-2" style="font-size: 1.75rem; color: #000000;">{{ $qplStats['goals_for'] }}</h4>
                            <small class="text-muted fw-semibold">Goals For</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                            <h4 class="fw-bold mb-2" style="font-size: 1.75rem; color: #000000;">{{ $qplStats['goals_against'] }}</h4>
                            <small class="text-muted fw-semibold">Goals Against</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                            <h4 class="fw-bold mb-2" style="font-size: 1.75rem; color: #000000;">
                                {{ ($qplStats['goals_for'] - $qplStats['goals_against']) >= 0 ? '+' : '' }}{{ $qplStats['goals_for'] - $qplStats['goals_against'] }}
                            </h4>
                            <small class="text-muted fw-semibold">Goal Difference</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($qplGames && $qplGames->count() > 0)
        <div class="card border-0 shadow-lg" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
            <div class="card-header border-0 py-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2" style="color: #D4AF37;"></i>My QPL Games
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead style="background: #000000; border-bottom: 3px solid #D4AF37; color: white;">
                            <tr>
                                <th class="text-white px-4" style="font-weight: 600;">Date</th>
                                <th class="text-white px-4" style="font-weight: 600;">Opponent</th>
                                <th class="text-white text-center px-4" style="font-weight: 600;">Score</th>
                                <th class="text-white text-center px-4" style="font-weight: 600;">Result</th>
                                <th class="text-white px-4" style="font-weight: 600;">Venue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($qplGames as $game)
                                @php
                                    $isAutoGenerated = ($game->notes === 'Auto-generated fixture');
                                    $hasBeenUpdated = ($game->updated_at != $game->created_at);
                                    $hasScores = ($game->home_score > 0 || $game->away_score > 0);
                                    $isPlayed = !$isAutoGenerated || $hasBeenUpdated || $hasScores;
                                    
                                    if ($game->home_team === $member->name) {
                                        $opponent = $game->away_team;
                                        $myScore = $game->home_score;
                                        $opponentScore = $game->away_score;
                                    } else {
                                        $opponent = $game->home_team;
                                        $myScore = $game->away_score;
                                        $opponentScore = $game->home_score;
                                    }
                                    
                                    if ($isPlayed) {
                                        if ($myScore > $opponentScore) {
                                            $result = 'W';
                                            $resultClass = 'success';
                                        } elseif ($myScore < $opponentScore) {
                                            $result = 'L';
                                            $resultClass = 'danger';
                                        } else {
                                            $result = 'D';
                                            $resultClass = 'info';
                                        }
                                    } else {
                                        $result = '-';
                                        $resultClass = 'secondary';
                                    }
                                @endphp
                                @if($isPlayed)
                                    <tr>
                                        <td class="px-4">
                                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                                            {{ $game->game_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 fw-semibold">{{ $opponent }}</td>
                                        <td class="text-center px-4">
                                            <span class="badge px-3 py-2" style="background: #000000; color: #FFFFFF; border: 2px solid #D4AF37; font-size: 1rem; border-radius: 8px;">
                                                {{ $myScore }} - {{ $opponentScore }}
                                            </span>
                                        </td>
                                        <td class="text-center px-4">
                                            <span class="badge px-3 py-2" style="background: #D4AF37; color: #000000; font-size: 1rem; border-radius: 8px;">{{ $result }}</span>
                                        </td>
                                        <td class="px-4">
                                            @if($game->venue)
                                                <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $game->venue }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="card border-0 shadow-lg" style="border-radius: 16px;">
            <div class="card-body text-center py-5">
                <i class="bi bi-trophy display-4 text-muted d-block mb-3"></i>
                <h5 class="mb-2 fw-bold">No QPL Games Yet</h5>
                <p class="text-muted">Your game results will appear here once games are recorded.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Calendar Tab -->
    <div class="tab-pane fade" id="calendar" role="tabpanel">
        @include('members.tabs.calendar', ['activities' => $upcomingActivities])
    </div>

    <!-- QPL Standings Tab -->
    <div class="tab-pane fade" id="qpl-standings" role="tabpanel">
        <div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
            <div class="card-header border-0 py-3" style="background: rgba(212, 175, 55, 0.1); border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #000000;">
                    <i class="bi bi-trophy me-2" style="color: #D4AF37;"></i>QBASH Pool League Standings
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">View the complete QBASH Pool League standings, game results, and rankings.</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('member.qpl.standings') }}" class="btn btn-lg" style="background: #D4AF37; color: #000000; border-radius: 10px;">
                        <i class="bi bi-trophy me-1"></i>View Full Standings
                    </a>
                </div>
                <div class="alert mt-4 border-0" style="background: rgba(212, 175, 55, 0.1); border-radius: 12px;">
                    <i class="bi bi-info-circle me-2" style="color: #D4AF37;"></i>
                    <strong>Note:</strong> Click the button above to view the complete league standings, recent games, and individual player statistics.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #666;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link:hover {
        border: none;
        color: #D4AF37;
        background: rgba(212, 175, 55, 0.1);
    }
    
    .nav-tabs .nav-link.active {
        background: #D4AF37;
        color: #000000;
        border: none;
        font-weight: 600;
    }
</style>
@endsection
