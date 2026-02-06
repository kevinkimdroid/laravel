@extends('layouts.app')

@section('title', 'QBASH Pool League Standings')

@section('content')
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #FFD700 0%, #c5a030 100%);">
    <div class="card-body text-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-2 fw-bold text-white">
                    <i class="bi bi-trophy-fill me-2"></i>QBASH Pool League Standings
                </h2>
                <p class="mb-0 text-white-50" style="font-size: 1.05rem;">
                    Complete league table showing all members ranked from first to last
                </p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                @auth
                    @if(auth()->user() && auth()->user()->role === 'admin')
                        <a href="{{ route('qpl-games.index') }}" class="btn btn-light shadow-sm">
                            <i class="bi bi-list-ul me-1"></i>All Games
                        </a>
                        <a href="{{ route('qpl-games.create') }}" class="btn btn-light shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>Add Game
                        </a>
                    @else
                        <a href="{{ route('member.dashboard') }}" class="btn btn-light shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- League Statistics -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #FFD700 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background: rgba(212, 175, 55, 0.1);">
                        <!-- Pool Table Icon -->
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FFD700" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block;">
                            <!-- Table outline -->
                            <rect x="3" y="6" width="18" height="12" rx="1" fill="none" stroke="#FFD700"/>
                            <!-- Pockets -->
                            <circle cx="3" cy="6" r="1.5" fill="#FFD700"/>
                            <circle cx="21" cy="6" r="1.5" fill="#FFD700"/>
                            <circle cx="3" cy="18" r="1.5" fill="#FFD700"/>
                            <circle cx="21" cy="18" r="1.5" fill="#FFD700"/>
                            <!-- Center line -->
                            <line x1="12" y1="6" x2="12" y2="18" stroke="#FFD700" stroke-width="0.8"/>
                            <!-- Pool balls -->
                            <circle cx="8" cy="12" r="1.5" fill="#FFD700"/>
                            <circle cx="16" cy="12" r="1.5" fill="#FFD700"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Games Played</h6>
                        <h4 class="fw-bold mb-0">{{ $totalGames ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-trophy text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Goals</h6>
                        <h4 class="fw-bold mb-0 text-success">{{ $totalGoals ?? 0 }}</h4>
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
                        <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Avg Goals/Game</h6>
                        <h4 class="fw-bold mb-0 text-info">{{ $averageGoalsPerGame ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #667eea !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Active Players</h6>
                        <h4 class="fw-bold mb-0 text-primary">{{ $playedStandings->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation for Standings -->
<ul class="nav nav-tabs mb-4" id="standingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="standings-tab" data-bs-toggle="tab" data-bs-target="#standings" type="button" role="tab" aria-controls="standings" aria-selected="true">
            <i class="bi bi-table me-1"></i>Standings
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="recent-games-tab" data-bs-toggle="tab" data-bs-target="#recent-games" type="button" role="tab" aria-controls="recent-games" aria-selected="false">
            <i class="bi bi-clock-history me-1"></i>Recent Games
            @if($recentGames->count() > 0)
                <span class="badge bg-info ms-1">{{ $recentGames->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="game-history-tab" data-bs-toggle="tab" data-bs-target="#game-history" type="button" role="tab" aria-controls="game-history" aria-selected="false">
            <i class="bi bi-list-check me-1"></i>Game History
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="standingsTabsContent">
    <!-- Standings Tab -->
    <div class="tab-pane fade show active" id="standings" role="tabpanel" aria-labelledby="standings-tab">
        @if($playedStandings->count() > 0)
<!-- Members Who Have Played -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-success bg-opacity-10 border-0 py-3">
        <h4 class="mb-0 fw-bold text-success">
            <i class="bi bi-check-circle-fill me-2"></i>Active Players ({{ $playedStandings->count() }})
        </h4>
        <p class="mb-0 mt-2 text-muted small">Members who have played at least one game</p>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                    <tr>
                        <th class="text-white text-center" style="width: 60px; font-weight: 600;">#</th>
                        <th class="text-white" style="font-weight: 600;">Member</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Played">P</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Won">W</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Drawn">D</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Lost">L</th>
                        <th class="text-white text-center" style="width: 60px; font-weight: 600;" title="Goals For">GF</th>
                        <th class="text-white text-center" style="width: 60px; font-weight: 600;" title="Goals Against">GA</th>
                        <th class="text-white text-center" style="width: 70px; font-weight: 600;" title="Goal Difference">GD</th>
                        <th class="text-white text-center" style="width: 70px; font-weight: 600;" title="Points">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($playedStandings as $index => $row)
                        <tr class="{{ $index === 0 ? 'table-warning' : '' }}" style="{{ $index === 0 ? 'background-color: rgba(255, 193, 7, 0.1) !important;' : '' }}">
                            <td class="fw-bold text-center" style="font-size: 1.1rem;">
                                @if($index === 0)
                                    <span class="badge" style="background: #FFD700; color: #000000; font-size: 1rem; padding: 6px 10px;">
                                        <i class="bi bi-trophy-fill me-1"></i>{{ $index + 1 }}
                                    </span>
                                @elseif($index === 1)
                                    <span class="badge bg-secondary text-white" style="font-size: 0.95rem; padding: 5px 9px;">
                                        <i class="bi bi-award me-1"></i>{{ $index + 1 }}
                                    </span>
                                @elseif($index === 2)
                                    <span class="badge bg-info text-white" style="font-size: 0.95rem; padding: 5px 9px;">
                                        <i class="bi bi-award me-1"></i>{{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="fw-semibold" style="font-size: 1rem;">
                                @if($index === 0)
                                    <i class="bi bi-star-fill me-1" style="color: #FFD700;"></i>
                                @endif
                                {{ $row['name'] }}
                            </td>
                            <td class="text-center">
                                @php
                                    $form = $formGuides[$row['name']] ?? [];
                                    $formDisplay = array_slice($form, 0, 5);
                                @endphp
                                @if(count($formDisplay) > 0)
                                    <div class="d-flex justify-content-center gap-1">
                                        @foreach($formDisplay as $result)
                                            @if($result === 'W')
                                                <span class="badge bg-success" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem;">W</span>
                                            @elseif($result === 'L')
                                                <span class="badge bg-danger" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem;">L</span>
                                            @else
                                                <span class="badge bg-info" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem;">D</span>
                                            @endif
                                        @endforeach
                                        @if(count($form) < 5)
                                            @for($i = count($form); $i < 5; $i++)
                                                <span class="badge bg-secondary" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem;">-</span>
                                            @endfor
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small">No form</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $row['played'] }}</td>
                            <td class="text-center text-success fw-semibold">{{ $row['won'] }}</td>
                            <td class="text-center text-info fw-semibold">{{ $row['drawn'] }}</td>
                            <td class="text-center text-danger fw-semibold">{{ $row['lost'] }}</td>
                            <td class="text-center fw-semibold">{{ $row['goals_for'] }}</td>
                            <td class="text-center fw-semibold">{{ $row['goals_against'] }}</td>
                            <td class="text-center fw-semibold">
                                @php $gd = $row['goal_diff']; @endphp
                                <span class="{{ $gd > 0 ? 'text-success' : ($gd < 0 ? 'text-danger' : 'text-muted') }}">
                                    {{ $gd > 0 ? '+' : '' }}{{ $gd }}
                                </span>
                            </td>
                            <td class="text-center fw-bold" style="font-size: 1.1rem; color: #28a745;">
                                {{ $row['points'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($notPlayed->count() > 0)
<!-- Members Who Haven't Played -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-secondary bg-opacity-10 border-0 py-3">
        <h4 class="mb-0 fw-bold text-secondary">
            <i class="bi bi-pause-circle-fill me-2"></i>Not Yet Played ({{ $notPlayed->count() }})
        </h4>
        <p class="mb-0 mt-2 text-muted small">Members who have not played any games yet</p>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white;">
                    <tr>
                        <th class="text-white text-center" style="width: 60px; font-weight: 600;">#</th>
                        <th class="text-white" style="font-weight: 600;">Member</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Played">P</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Won">W</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Drawn">D</th>
                        <th class="text-white text-center" style="width: 50px; font-weight: 600;" title="Lost">L</th>
                        <th class="text-white text-center" style="width: 60px; font-weight: 600;" title="Goals For">GF</th>
                        <th class="text-white text-center" style="width: 60px; font-weight: 600;" title="Goals Against">GA</th>
                        <th class="text-white text-center" style="width: 70px; font-weight: 600;" title="Goal Difference">GD</th>
                        <th class="text-white text-center" style="width: 70px; font-weight: 600;" title="Points">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notPlayed as $index => $row)
                        <tr style="opacity: 0.7;">
                            <td class="text-center text-muted">
                                {{ $playedStandings->count() + $index + 1 }}
                            </td>
                            <td class="fw-semibold text-muted">{{ $row['name'] }}</td>
                            <td class="text-center text-muted">0</td>
                            <td class="text-center text-muted">0</td>
                            <td class="text-center text-muted">0</td>
                            <td class="text-center text-muted">0</td>
                            <td class="text-center text-muted">0</td>
                            <td class="text-center text-muted">0</td>
                            <td class="text-center text-muted">0</td>
                            <td class="text-center text-muted">0</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($playedStandings->count() === 0 && $notPlayed->count() === 0)
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-trophy display-4 text-muted d-block mb-3"></i>
        <h4 class="text-muted mb-2">No Standings Available</h4>
        <p class="text-muted">No games have been recorded yet. Standings will appear here once games are played.</p>
        <a href="{{ route('qpl-games.create') }}" class="btn btn-primary mt-3">
            <i class="bi bi-plus-circle me-1"></i>Record First Game
        </a>
    </div>
</div>
@endif

@if($playedStandings->count() > 0)
        <div class="card border-0 shadow-sm">
    <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
        <h4 class="mb-0 fw-bold text-primary">
            <i class="bi bi-list-check me-2"></i>Individual Game Results & Scores
        </h4>
        <p class="mb-0 mt-2 text-muted small">See who has played and their individual game scores</p>
    </div>
    <div class="card-body">
        <div class="accordion" id="memberGameAccordion">
            @foreach($playedStandings as $index => $member)
                @php
                    $memberName = $member['name'];
                    $games = $memberGameHistory[$memberName] ?? [];
                @endphp
                @if(count($games) > 0)
                    <div class="accordion-item mb-2 border rounded">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                    <div>
                                        <span class="badge bg-primary me-2">#{{ $index + 1 }}</span>
                                        <strong>{{ $memberName }}</strong>
                                    </div>
                                    <div class="text-muted small">
                                        <span class="badge bg-success me-1">{{ $member['won'] }}W</span>
                                        <span class="badge bg-info me-1">{{ $member['drawn'] }}D</span>
                                        <span class="badge bg-danger me-1">{{ $member['lost'] }}L</span>
                                        <span class="badge" style="background: #FFD700; color: #000000;">{{ $member['points'] }} Pts</span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#memberGameAccordion">
                            <div class="accordion-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 120px;">Date</th>
                                                <th>Opponent</th>
                                                <th class="text-center" style="width: 150px;">Score</th>
                                                <th class="text-center" style="width: 80px;">Result</th>
                                                <th>Venue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($games as $game)
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                                        <small>{{ \Carbon\Carbon::parse($game['date'])->format('M d, Y') }}</small>
                                                    </td>
                                                    <td class="fw-semibold">{{ $game['opponent'] }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary px-3 py-2 fw-bold">
                                                            {{ $game['score_for'] }} - {{ $game['score_against'] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($game['result'] === 'W')
                                                            <span class="badge bg-success fs-6">W</span>
                                                        @elseif($game['result'] === 'L')
                                                            <span class="badge bg-danger fs-6">L</span>
                                                        @else
                                                            <span class="badge bg-info fs-6">D</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($game['venue'])
                                                            <i class="bi bi-geo-alt me-1 text-muted"></i>
                                                            <small>{{ $game['venue'] }}</small>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-list-check display-4 text-muted d-block mb-3"></i>
                <h5 class="mb-2">No Game History</h5>
                <p class="text-muted">No game history available yet.</p>
            </div>
        </div>
@endif
    </div>
</div>
@endsection
