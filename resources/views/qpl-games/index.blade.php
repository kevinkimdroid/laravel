@extends('layouts.app')

@section('title', 'QBASH Pool League')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-trophy me-2"></i>QBASH Pool League
                </h3>
                <p class="text-muted mb-0">Record and manage QBASH Pool League game scores</p>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <a href="{{ route('qpl-games.standings') }}" class="btn btn-outline-success">
                    <i class="bi bi-table me-1"></i>View Standings
                </a>
                <form action="{{ route('qpl-games.delete-generated') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL auto-generated fixtures? This cannot be undone.');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Delete Generated
                    </button>
                </form>
                <a href="{{ route('qpl-games.generate.form') }}" class="btn btn-outline-primary">
                    <i class="bi bi-magic me-1"></i>Generate Fixtures
                </a>
                <a href="{{ route('qpl-games.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Add Game
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Search and Filter Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-funnel me-2"></i>Search & Filter Games
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('qpl-games.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Search by player, venue...">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Player</label>
                <select name="player" class="form-select">
                    <option value="">All Players</option>
                    @foreach($members as $member)
                        <option value="{{ $member->name }}" {{ request('player') === $member->name ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Games</option>
                    <option value="played" {{ request('status') === 'played' ? 'selected' : '' }}>Played</option>
                    <option value="unplayed" {{ request('status') === 'unplayed' ? 'selected' : '' }}>Unplayed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">From Date</label>
                <input type="date" 
                       name="date_from" 
                       class="form-control" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">To Date</label>
                <input type="date" 
                       name="date_to" 
                       class="form-control" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            @if(request()->hasAny(['search', 'player', 'status', 'date_from', 'date_to']))
            <div class="col-12">
                <a href="{{ route('qpl-games.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Clear Filters
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Quick Stats -->
@php
    $playedCount = $games->filter(function($game) {
        return ($game->notes !== 'Auto-generated fixture' || 
                $game->updated_at != $game->created_at ||
                $game->home_score > 0 || $game->away_score > 0);
    })->count();
    $unplayedCount = $games->count() - $playedCount;
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-controller text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Games</h6>
                        <h4 class="fw-bold mb-0">{{ $games->count() }}</h4>
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
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Played</h6>
                        <h4 class="fw-bold mb-0 text-success">{{ $playedCount }}</h4>
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
                        <i class="bi bi-clock-history text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Pending</h6>
                        <h4 class="fw-bold mb-0 text-danger">{{ $unplayedCount }}</h4>
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
                        <h6 class="text-muted text-uppercase small mb-0">Completion</h6>
                        <h4 class="fw-bold mb-0 text-info">
                            {{ $games->count() > 0 ? round(($playedCount / $games->count()) * 100, 1) : 0 }}%
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4" id="gamesTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-games-tab" data-bs-toggle="tab" data-bs-target="#all-games" type="button" role="tab" aria-controls="all-games" aria-selected="true">
            <i class="bi bi-list-ul me-1"></i>All Games
            <span class="badge bg-secondary ms-1">{{ $allGames->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="well-played-tab" data-bs-toggle="tab" data-bs-target="#well-played" type="button" role="tab" aria-controls="well-played" aria-selected="false">
            <i class="bi bi-star-fill me-1 text-warning"></i>Well-Played
            <span class="badge bg-warning text-dark ms-1">{{ $wellPlayedGames->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="close-games-tab" data-bs-toggle="tab" data-bs-target="#close-games" type="button" role="tab" aria-controls="close-games" aria-selected="false">
            <i class="bi bi-lightning-charge me-1 text-danger"></i>Close Games
            <span class="badge bg-danger ms-1">{{ $closeGames->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="high-scoring-tab" data-bs-toggle="tab" data-bs-target="#high-scoring" type="button" role="tab" aria-controls="high-scoring" aria-selected="false">
            <i class="bi bi-fire me-1 text-warning"></i>High Scoring
            <span class="badge bg-warning text-dark ms-1">{{ $highScoringGames->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab" aria-controls="recent" aria-selected="false">
            <i class="bi bi-clock-history me-1"></i>Recent
            <span class="badge bg-info ms-1">{{ $recentGames->count() }}</span>
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="gamesTabsContent">
    <!-- All Games Tab -->
    <div class="tab-pane fade show active" id="all-games" role="tabpanel" aria-labelledby="all-games-tab">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                <thead class="table-light" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white;">
                    <tr>
                        <th class="text-white">Date</th>
                        <th class="text-white">Member A</th>
                        <th class="text-white text-center">Score</th>
                        <th class="text-white">Member B</th>
                        <th class="text-white">Venue</th>
                        <th class="text-white text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allGames as $game)
                        @php
                            $isPlayed = ($game->notes !== 'Auto-generated fixture' || 
                                        $game->updated_at != $game->created_at ||
                                        $game->home_score > 0 || $game->away_score > 0);
                            $scoreDiff = abs($game->home_score - $game->away_score);
                            $totalGoals = $game->home_score + $game->away_score;
                            $isWellPlayed = $isPlayed && (($scoreDiff <= 2 && $totalGoals >= 4) || $totalGoals >= 8);
                            $isClose = $isPlayed && $scoreDiff <= 1;
                            $isHighScoring = $isPlayed && $totalGoals >= 8;
                        @endphp
                        <tr class="{{ $isWellPlayed ? 'table-warning' : '' }}" style="{{ $isWellPlayed ? 'background-color: rgba(255, 193, 7, 0.1) !important;' : '' }}">
                            <td>
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $game->game_date->format('M d, Y') }}
                                @if($isWellPlayed)
                                    <i class="bi bi-star-fill text-warning ms-1" title="Well-Played Game"></i>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $game->home_team }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = 'bg-primary';
                                    if ($isHighScoring) $badgeClass = 'bg-danger';
                                    elseif ($isClose) $badgeClass = 'bg-warning text-dark';
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6 px-3 py-2">
                                    {{ $game->home_score }} - {{ $game->away_score }}
                                </span>
                                @if($isClose)
                                    <br><small class="text-danger"><i class="bi bi-lightning-charge"></i> Close!</small>
                                @endif
                                @if($isHighScoring)
                                    <br><small class="text-warning"><i class="bi bi-fire"></i> High Scoring!</small>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $game->away_team }}</td>
                            <td>
                                @if($game->venue)
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $game->venue }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @auth
                                    @if(auth()->user() && auth()->user()->role === 'admin')
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('qpl-games.edit', $game->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('qpl-games.destroy', $game->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this game?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-trophy display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No QBASH Pool League games found. Record the first game to get started.</p>
                                <a href="{{ route('qpl-games.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Add First Game
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>

    <!-- Well-Played Games Tab -->
    <div class="tab-pane fade" id="well-played" role="tabpanel" aria-labelledby="well-played-tab">
        @include('qpl-games.partials.games-table', ['games' => $wellPlayedGames, 'title' => 'Well-Played Games', 'description' => 'Close games (score difference ≤ 2) or high-scoring games (≥ 8 goals)'])
    </div>

    <!-- Close Games Tab -->
    <div class="tab-pane fade" id="close-games" role="tabpanel" aria-labelledby="close-games-tab">
        @include('qpl-games.partials.games-table', ['games' => $closeGames, 'title' => 'Close Games', 'description' => 'Games with score difference of 1 or less'])
    </div>

    <!-- High Scoring Games Tab -->
    <div class="tab-pane fade" id="high-scoring" role="tabpanel" aria-labelledby="high-scoring-tab">
        @include('qpl-games.partials.games-table', ['games' => $highScoringGames, 'title' => 'High Scoring Games', 'description' => 'Games with 8 or more total goals'])
    </div>

    <!-- Recent Games Tab -->
    <div class="tab-pane fade" id="recent" role="tabpanel" aria-labelledby="recent-tab">
        @include('qpl-games.partials.games-table', ['games' => $recentGames, 'title' => 'Recent Games', 'description' => 'Last 10 played games'])
    </div>
</div>
@endsection

