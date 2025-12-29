@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h4 class="card-title mb-1">Welcome back, {{ auth()->user()->name }}!</h4>
                    <p class="card-text mb-0 text-white-50">
                        Use the shortcuts below to quickly manage members, contributions, and finances.
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge bg-light text-primary me-2">Online</span>
                    <span class="badge bg-light text-primary">Secure area</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small">Members</h6>
                <p class="fw-semibold mb-2">Manage all registered members.</p>
                <a href="{{ route('members.index') }}" class="btn btn-outline-primary btn-sm">
                    Go to Members
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small">Contributions</h6>
                <p class="fw-semibold mb-2">Track and review member contributions.</p>
                <a href="{{ route('contributions.index') }}" class="btn btn-outline-success btn-sm">
                    Go to Contributions
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small">Financials</h6>
                <p class="fw-semibold mb-2">See expenses and financial records.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-dark btn-sm">
                        Expenses
                    </a>
                    @if (Route::has('financial-records.index'))
                        <a href="{{ route('financial-records.index') }}" class="btn btn-outline-secondary btn-sm">
                            Financial Records
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
