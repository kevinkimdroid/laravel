@extends('layouts.app')

@section('title', 'Generate QBASH Pool League Fixtures')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-trophy me-2"></i>Generate QBASH Pool League Fixtures
                </h3>
                <p class="text-muted mb-0">
                    Choose when the league starts, then generate member vs member fixtures automatically.
                </p>
            </div>
            <a href="{{ route('qpl-games.index') }}" class="btn btn-outline-secondary mt-3 mt-md-0">
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
        <form method="POST" action="{{ route('qpl-games.generate') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>League Start Date
                    </label>
                    <input type="date"
                           name="start_date"
                           class="form-control"
                           value="{{ old('start_date', date('Y-m-d')) }}"
                           required>
                    <small class="text-muted">All fixtures will be created using this date (you can edit dates later).</small>
                </div>
            </div>

            <p class="text-muted mb-3">
                The system will generate a round-robin list of fixtures:
                every member will play against every other member once (Member A vs Member B).
                After generation you can <strong>edit</strong>, <strong>update</strong> or <strong>delete</strong> any game from the games list.
            </p>

            <div class="alert alert-warning small">
                <i class="bi bi-info-circle me-1"></i>
                Existing games will not be removed. If you don't want duplicates, clear old fixtures first.
            </div>

            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-magic me-2"></i>Generate Fixtures
            </button>
        </form>
    </div>
</div>
@endsection


