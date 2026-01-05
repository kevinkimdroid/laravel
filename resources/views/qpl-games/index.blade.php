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
                    @forelse($games as $game)
                        <tr>
                            <td>
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $game->game_date->format('M d, Y') }}
                            </td>
                            <td class="fw-semibold">{{ $game->home_team }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    {{ $game->home_score }} - {{ $game->away_score }}
                                </span>
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
@endsection

