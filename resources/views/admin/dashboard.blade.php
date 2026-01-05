@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h3 class="card-title mb-2 fw-bold">
                            <i class="bi bi-shield-check me-2"></i>Admin Dashboard
                        </h3>
                        <p class="card-text mb-0 text-white-50 fs-6">
                            Manage members, contributions, and approve user applications.
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge bg-light text-primary me-2 px-3 py-2">
                            <i class="bi bi-wifi me-1"></i>Online
                        </span>
                        <span class="badge bg-light text-primary px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i>Admin
                        </span>
                    </div>
                </div>
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #667eea !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Members</h6>
                        <h3 class="fw-bold mb-0">{{ $totalMembers }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-person-check-fill text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Users</h6>
                        <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Pending Approvals</h6>
                        <h3 class="fw-bold mb-0">{{ $pendingApprovals }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-cash-stack text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Contributions</h6>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Contribution::count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Approvals Section -->
@if($pendingUsers->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-hourglass-split me-2 text-warning"></i>Pending Member Approvals
            <span class="badge bg-warning text-dark ms-2">{{ $pendingUsers->count() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Registered</th>
                        <th>Link to Member</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->phone)
                                <i class="bi bi-telephone me-1"></i>{{ $user->phone }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <form action="{{ route('admin.approve-user', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <select name="member_id" class="form-select" required>
                                        <option value="">Select Member...</option>
                                        @foreach(\App\Models\Member::whereDoesntHave('users')->get() as $member)
                                            <option value="{{ $member->id }}">{{ $member->member_no }} - {{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="View Profile">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-5">
        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        <h4 class="mt-3 mb-2">All Clear!</h4>
        <p class="text-muted">No pending member approvals at this time.</p>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #667eea !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Members</h6>
                        <h4 class="fw-bold mb-0">{{ $totalMembers }}</h4>
                    </div>
                </div>
                <p class="text-muted mb-3">Manage all registered members and their information.</p>
                <a href="{{ route('members.index') }}" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-right-circle me-1"></i>Go to Members
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
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
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-trophy text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">QBASH Pool League</h6>
                        <h4 class="fw-bold mb-0">{{ \App\Models\QplGame::count() }}</h4>
                    </div>
                </div>
                <p class="text-muted mb-3">Record and manage QBASH Pool League game scores.</p>
                <a href="{{ route('qpl-games.index') }}" class="btn btn-warning w-100">
                    <i class="bi bi-trophy me-1"></i>Manage QBASH Pool League
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ff6b6b !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-calendar-event text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Calendar</h6>
                        <h4 class="fw-bold mb-0">{{ \App\Models\CalendarActivity::count() }}</h4>
                    </div>
                </div>
                <p class="text-muted mb-3">Create and manage calendar activities for the year.</p>
                <a href="{{ route('calendar-activities.index') }}" class="btn btn-danger w-100">
                    <i class="bi bi-calendar-plus me-1"></i>Manage Calendar
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6c757d !important;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-receipt-cutoff text-secondary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Financials</h6>
                        <h4 class="fw-bold mb-0">View</h4>
                    </div>
                </div>
                <p class="text-muted mb-3">See expenses and financial records for better management.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
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

@php
    $pendingPayments = \App\Models\PaymentRequest::where('status', 'pending')->count();
@endphp

@if($pendingPayments > 0)
<div class="alert alert-info alert-dismissible fade show shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-credit-card me-3" style="font-size: 2rem;"></i>
        <div class="flex-grow-1">
            <h5 class="alert-heading mb-1">Pending Payment Requests</h5>
            <p class="mb-0">You have <strong>{{ $pendingPayments }}</strong> M-Pesa payment request(s) waiting for approval.</p>
        </div>
        <div>
            <a href="{{ route('admin.payment-requests') }}" class="btn btn-info">
                <i class="bi bi-credit-card me-1"></i>Review Payments
            </a>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Payment Requests Section -->
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #0d6efd !important;">
    <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-credit-card me-2 text-primary"></i>Payment Requests
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Review and approve M-Pesa payment requests from members.</p>
        <a href="{{ route('admin.payment-requests') }}" class="btn btn-primary">
            <i class="bi bi-credit-card me-1"></i>Manage Payment Requests
            @if($pendingPayments > 0)
                <span class="badge bg-danger ms-2">{{ $pendingPayments }}</span>
            @endif
        </a>
    </div>
</div>

<!-- WhatsApp Reminders Section -->
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #25D366 !important;">
    <div class="card-header bg-success bg-opacity-10 border-0 py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-whatsapp me-2" style="color: #25D366;"></i>WhatsApp Reminders
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Send payment reminders to members with outstanding balances via WhatsApp.</p>
        <a href="{{ route('whatsapp.index') }}" class="btn btn-success" style="background-color: #25D366; border-color: #25D366;">
            <i class="bi bi-whatsapp me-1"></i>Send WhatsApp Reminders
        </a>
    </div>
</div>

<!-- Reports Section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-info bg-opacity-10 border-0 py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-file-earmark-text me-2 text-info"></i>Reports
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 border rounded">
                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-1">Men Outstanding Balances</h6>
                        <p class="text-muted small mb-0">Download report of men with outstanding balances (as of current month)</p>
                    </div>
                    <a href="{{ route('reports.outstanding-balances') }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center p-3 border rounded">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-trophy text-success" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-1">Best Contributors</h6>
                        <p class="text-muted small mb-0">Download report of top contributing members</p>
                    </div>
                    <a href="{{ route('reports.best-contributors') }}" class="btn btn-success btn-sm">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Activities Section -->
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #ff6b6b !important;">
    <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-calendar-event me-2 text-danger"></i>Calendar Activities
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Create and manage calendar activities for the year (meetings, events, games, etc.).</p>
        <a href="{{ route('calendar-activities.index') }}" class="btn btn-danger">
            <i class="bi bi-calendar-plus me-1"></i>Manage Calendar
        </a>
    </div>
</div>
@endsection

