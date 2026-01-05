@extends('layouts.app')

@section('title', 'Edit QBASH Pool League Game')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-pencil me-2"></i>Edit QBASH Pool League Game
                </h3>
            </div>
            <a href="{{ route('qpl-games.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Games
            </a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Error:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('qpl-games.update', $qplGame->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>Game Date
                    </label>
                    <input type="date" 
                           name="game_date" 
                           class="form-control" 
                           value="{{ old('game_date', $qplGame->game_date->format('Y-m-d')) }}" 
                           required>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-geo-alt me-2 text-primary"></i>Venue
                    </label>
                    <input type="text" 
                           name="venue" 
                           class="form-control" 
                           value="{{ old('venue', $qplGame->venue) }}"
                           placeholder="Game venue (optional)">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person me-2 text-primary"></i>Member A
                    </label>
                    <select name="home_team" class="form-select" required>
                        <option value="">Select Member A</option>
                        @foreach($members as $member)
                            <option value="{{ $member->name }}"
                                {{ old('home_team', $qplGame->home_team) === $member->name ? 'selected' : '' }}>
                                {{ $member->member_no }} - {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Choose the first member playing in this game.</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person me-2 text-primary"></i>Member B
                    </label>
                    <select name="away_team" class="form-select" required>
                        <option value="">Select Member B</option>
                        @foreach($members as $member)
                            <option value="{{ $member->name }}"
                                {{ old('away_team', $qplGame->away_team) === $member->name ? 'selected' : '' }}>
                                {{ $member->member_no }} - {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Choose the second member playing in this game.</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-trophy me-2 text-primary"></i>Member A Score
                    </label>
                    <input type="number" 
                           name="home_score" 
                           class="form-control" 
                           value="{{ old('home_score', $qplGame->home_score) }}"
                           min="0"
                           required>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-trophy me-2 text-primary"></i>Member B Score
                    </label>
                    <input type="number" 
                           name="away_score" 
                           class="form-control" 
                           value="{{ old('away_score', $qplGame->away_score) }}"
                           min="0"
                           required>
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-chat-left-text me-2 text-primary"></i>Notes
                    </label>
                    <textarea name="notes" 
                              class="form-control" 
                              rows="3"
                              placeholder="Additional notes about the game (optional)">{{ old('notes', $qplGame->notes) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Update Game
                </button>
                <a href="{{ route('qpl-games.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

