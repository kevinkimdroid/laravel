@extends('layouts.app')

@section('title', 'Member Details')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-person me-2"></i>{{ $member->name }}
                </h3>
                <p class="text-muted mb-0">
                    Member No: <strong>{{ $member->member_no }}</strong> | 
                    Status: <span class="badge bg-{{ $member->status === 'ACTIVE' ? 'success' : 'secondary' }}">{{ $member->status }}</span>
                </p>
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Members
            </a>
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
        <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
            <i class="bi bi-credit-card me-1"></i>Make Payments
            @if($pendingPayments && $pendingPayments->count() > 0)
                <span class="badge bg-warning text-dark ms-1">{{ $pendingPayments->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="qpl-tab" data-bs-toggle="tab" data-bs-target="#qpl" type="button" role="tab">
            <i class="bi bi-trophy me-1"></i>QPL Games
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab">
            <i class="bi bi-calendar-event me-1"></i>Calendar
            @if($upcomingActivities && $upcomingActivities->count() > 0)
                <span class="badge bg-info ms-1">{{ $upcomingActivities->count() }}</span>
            @endif
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="memberTabsContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        @include('members.tabs.overview')
    </div>

    <!-- Payments Tab -->
    <div class="tab-pane fade" id="payments" role="tabpanel">
        @include('members.tabs.payments')
    </div>

    <!-- QPL Games Tab -->
    <div class="tab-pane fade" id="qpl" role="tabpanel">
        @include('members.tabs.qpl-games')
    </div>

    <!-- Calendar Tab -->
    <div class="tab-pane fade" id="calendar" role="tabpanel">
        @include('members.tabs.calendar')
    </div>
</div>
@endsection

