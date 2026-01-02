@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h3 class="card-title mb-2 fw-bold">
                            <i class="bi bi-house-door-fill me-2"></i>Welcome back, {{ auth()->user()->name }}!
                        </h3>
                        <p class="card-text mb-0 text-white-50 fs-6">
                            Use the shortcuts below to quickly manage members, contributions, and finances.
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge bg-light text-primary me-2 px-3 py-2">
                            <i class="bi bi-wifi me-1"></i>Online
                        </span>
                        <span class="badge bg-light text-primary px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i>Secure
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
@php
    $pendingApprovals = \App\Models\User::whereNull('member_id')->where('role', 'member')->count();
@endphp

@if($pendingApprovals > 0)
<div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-hourglass-split me-3" style="font-size: 2rem;"></i>
        <div class="flex-grow-1">
            <h5 class="alert-heading mb-1">Pending Member Approvals</h5>
            <p class="mb-0">You have <strong>{{ $pendingApprovals }}</strong> user(s) waiting for member approval.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-warning">
                <i class="bi bi-check-circle me-1"></i>Review Approvals
            </a>
        </div>
    </div>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #667eea !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Members</h6>
                        <h4 class="fw-bold mb-0">{{ \App\Models\Member::count() }}</h4>
                    </div>
                </div>
                <p class="text-muted mb-3">Manage all registered members and their information.</p>
                <a href="{{ route('members.index') }}" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-right-circle me-1"></i>Go to Members
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-cash-stack text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Contributions</h6>
                        <h4 class="fw-bold mb-0">{{ \App\Models\Contribution::count() }}</h4>
                    </div>
                </div>
                <p class="text-muted mb-3">Track and review member contributions by month and year.</p>
                <a href="{{ route('contributions.index') }}" class="btn btn-success w-100">
                    <i class="bi bi-arrow-right-circle me-1"></i>Go to Contributions
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-receipt-cutoff text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Financials</h6>
                        <h4 class="fw-bold mb-0">View</h4>
                    </div>
                </div>
                <p class="text-muted mb-3">See expenses and financial records for better management.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('expenses.index') }}" class="btn btn-warning">
                        <i class="bi bi-receipt me-1"></i>Expenses
                    </a>
                    @if (Route::has('financial-records.index'))
                        <a href="{{ route('financial-records.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-file-earmark-text me-1"></i>Financial Records
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <i class="bi bi-cash-stack text-primary" style="font-size: 3rem;"></i>
                <h4 class="mt-3 mb-2">My Contributions</h4>
                <p class="text-muted">View and manage your contribution history.</p>
                <a href="{{ route('member.contributions') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-right-circle me-2"></i>View My Contributions
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

