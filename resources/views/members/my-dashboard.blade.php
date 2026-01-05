@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card-body text-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-2 fw-bold text-white">
                    <i class="bi bi-person-circle me-2"></i>Welcome, {{ $member->name }}!
                </h2>
                <p class="mb-0 text-white-50" style="font-size: 1.05rem;">
                    Member No: <strong>{{ $member->member_no }}</strong> | 
                    Status: <span class="badge bg-light text-dark">{{ $member->status }}</span>
                </p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-light mt-3 mt-md-0 shadow-sm">
                <i class="bi bi-gear me-1"></i>Edit Profile
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Quick Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-cash-stack text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Paid</h6>
                        <h4 class="fw-bold mb-0">KES {{ number_format($totalPaid, 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Outstanding</h6>
                        <h4 class="fw-bold mb-0">KES {{ number_format($outstanding, 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-trophy text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">QPL Points</h6>
                        <h4 class="fw-bold mb-0">{{ $qplStats['points'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0dcaf0 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-calendar-event text-info" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Games Played</h6>
                        <h4 class="fw-bold mb-0">{{ $qplStats['played'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4" id="memberTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
            <i class="bi bi-info-circle me-1"></i>Overview
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contributions-tab" data-bs-toggle="tab" data-bs-target="#contributions" type="button" role="tab">
            <i class="bi bi-cash-stack me-1"></i>Contributions
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="qpl-tab" data-bs-toggle="tab" data-bs-target="#qpl" type="button" role="tab">
            <i class="bi bi-trophy me-1"></i>QPL Games
            @if($qplStats['played'] > 0)
                <span class="badge bg-warning text-dark ms-1">{{ $qplStats['played'] }}</span>
            @endif
        </button>
    </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab">
                <i class="bi bi-calendar-event me-1"></i>Calendar
                @if($upcomingActivities->count() > 0)
                    <span class="badge bg-info ms-1">{{ $upcomingActivities->count() }}</span>
                @endif
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="qpl-standings-tab" data-bs-toggle="tab" data-bs-target="#qpl-standings" type="button" role="tab">
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person me-2 text-primary"></i>Personal Information
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
                                    <span class="badge bg-{{ $member->status === 'ACTIVE' ? 'success' : 'secondary' }}">
                                        {{ $member->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Registration:</td>
                                <td>
                                    @if($isRegistered)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Registered (KES {{ number_format($registrationFeePaid, 0) }})
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success bg-opacity-10 border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-cash-stack me-2 text-success"></i>Contribution Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width: 50%;">Total Paid:</td>
                                <td class="fw-bold text-success">KES {{ number_format($totalPaid, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Outstanding Balance:</td>
                                <td class="fw-bold {{ $outstanding > 0 ? 'text-danger' : 'text-success' }}">
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
                            <div class="mt-3">
                                <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-primary w-100">
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
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success bg-opacity-10 border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-cash-stack me-2 text-success"></i>My Contributions
                    </h5>
                    <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Make Payment
                    </a>
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('member.contributions') }}" class="btn btn-outline-primary mb-3">
                    <i class="bi bi-arrow-right me-1"></i>View Full Contribution History
                </a>
                @if($pendingPayments && $pendingPayments->count() > 0)
                    <div class="alert alert-warning">
                        <h6 class="fw-bold"><i class="bi bi-hourglass-split me-2"></i>Pending Payment Requests ({{ $pendingPayments->count() }})</h6>
                        <p class="mb-0 small">You have {{ $pendingPayments->count() }} payment request(s) awaiting admin approval.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- QPL Games Tab -->
    <div class="tab-pane fade" id="qpl" role="tabpanel">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-trophy me-2 text-warning"></i>QBASH Pool League Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h3 class="fw-bold text-primary mb-1">{{ $qplStats['played'] }}</h3>
                            <small class="text-muted">Games Played</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h3 class="fw-bold text-success mb-1">{{ $qplStats['won'] }}</h3>
                            <small class="text-muted">Wins</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h3 class="fw-bold text-info mb-1">{{ $qplStats['drawn'] }}</h3>
                            <small class="text-muted">Draws</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h3 class="fw-bold text-danger mb-1">{{ $qplStats['lost'] }}</h3>
                            <small class="text-muted">Losses</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h4 class="fw-bold text-primary mb-1">{{ $qplStats['goals_for'] }}</h4>
                            <small class="text-muted">Goals For</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h4 class="fw-bold text-danger mb-1">{{ $qplStats['goals_against'] }}</h4>
                            <small class="text-muted">Goals Against</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded">
                            <h4 class="fw-bold {{ ($qplStats['goals_for'] - $qplStats['goals_against']) >= 0 ? 'text-success' : 'text-danger' }} mb-1">
                                {{ ($qplStats['goals_for'] - $qplStats['goals_against']) >= 0 ? '+' : '' }}{{ $qplStats['goals_for'] - $qplStats['goals_against'] }}
                            </h4>
                            <small class="text-muted">Goal Difference</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($qplGames && $qplGames->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2 text-warning"></i>My QPL Games
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white;">
                            <tr>
                                <th class="text-white">Date</th>
                                <th class="text-white">Opponent</th>
                                <th class="text-white text-center">Score</th>
                                <th class="text-white text-center">Result</th>
                                <th class="text-white">Venue</th>
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
                                        <td>
                                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                                            {{ $game->game_date->format('M d, Y') }}
                                        </td>
                                        <td class="fw-semibold">{{ $opponent }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-6 px-3 py-2">
                                                {{ $myScore }} - {{ $opponentScore }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $resultClass }} fs-6">{{ $result }}</span>
                                        </td>
                                        <td>
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
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-trophy display-4 text-muted d-block mb-3"></i>
                <h5 class="mb-2">No QPL Games Yet</h5>
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
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                <h5 class="mb-0 fw-bold text-warning">
                    <i class="bi bi-trophy me-2"></i>QBASH Pool League Standings
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">View the complete QBASH Pool League standings, game results, and rankings.</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('member.qpl.standings') }}" class="btn btn-warning btn-lg">
                        <i class="bi bi-trophy me-1"></i>View Full Standings
                    </a>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Click the button above to view the complete league standings, recent games, and individual player statistics.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

