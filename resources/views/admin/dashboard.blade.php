@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $totalContributions = \App\Models\Contribution::count();
    $approvalRate = $totalMembers > 0 ? min(100, round((($totalMembers - $pendingApprovals) / $totalMembers) * 100)) : 0;
    $memberRatio = $totalUsers > 0 ? min(100, round(($totalMembers / $totalUsers) * 100)) : 0;
@endphp
<div class="admin-board">
    <div class="board-top d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
        <div>
            <div class="board-eyebrow">Admin overview</div>
            <h2 class="board-title mb-1">QBASH Admin Dashboard</h2>
            <p class="board-subtitle mb-0">Track members, approvals, contributions, and league activity.</p>
        </div>
        <div class="board-actions d-flex align-items-center gap-2">
            <span class="badge badge-soft-gold">Online</span>
            <span class="badge badge-gold">Admin access</span>
            <span class="board-meta text-muted small">Updated {{ now()->format('M d, Y') }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 12px;">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="board-card h-100 stat-tile">
                <div class="board-card-body">
                    <div class="stat-row">
                        <div>
                            <div class="stat-label">Total members</div>
                            <div class="stat-value">{{ $totalMembers }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="small text-muted">Registered pool league members</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="board-card h-100 stat-tile">
                <div class="board-card-body">
                    <div class="stat-row">
                        <div>
                            <div class="stat-label">Total users</div>
                            <div class="stat-value">{{ $totalUsers }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                    </div>
                    <div class="small text-muted">Users with portal access</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="board-card h-100 stat-tile">
                <div class="board-card-body">
                    <div class="stat-row">
                        <div>
                            <div class="stat-label">Contributions</div>
                            <div class="stat-value">{{ $totalContributions }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <div class="small text-muted">Total payment records</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="board-card h-100 stat-tile">
                <div class="board-card-body">
                    <div class="stat-row">
                        <div>
                            <div class="stat-label">Pending approvals</div>
                            <div class="stat-value">{{ $pendingApprovals }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="small text-muted">Awaiting member approvals</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="board-card h-100">
                <div class="board-card-header">
                    <span>Performance overview</span>
                    <span class="board-pill">Summary</span>
                </div>
                <div class="board-card-body">
                    <div class="overview-row">
                        <div>
                            <div class="overview-label">Contribution records</div>
                            <div class="overview-value">{{ $totalContributions }}</div>
                        </div>
                        <div class="overview-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ min(100, $totalContributions) }}%"></div>
                            </div>
                            <span class="small text-muted">Tracking total contributions</span>
                        </div>
                    </div>
                    <div class="overview-row">
                        <div>
                            <div class="overview-label">Approval rate</div>
                            <div class="overview-value">{{ $approvalRate }}%</div>
                        </div>
                        <div class="overview-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $approvalRate }}%"></div>
                            </div>
                            <span class="small text-muted">Approved members</span>
                        </div>
                    </div>
                    <div class="overview-row">
                        <div>
                            <div class="overview-label">Members vs users</div>
                            <div class="overview-value">{{ $memberRatio }}%</div>
                        </div>
                        <div class="overview-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $memberRatio }}%"></div>
                            </div>
                            <span class="small text-muted">Membership coverage</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="board-card h-100">
                <div class="board-card-header">
                    <span>Focus areas</span>
                    <span class="board-pill">Insights</span>
                </div>
                <div class="board-card-body d-flex flex-column align-items-center gap-3">
                    <div class="donut"></div>
                    <div class="small text-muted text-center">Members, approvals, and contributions focus</div>
                    <div class="focus-legend">
                        <span><i></i>Members</span>
                        <span><i></i>Approvals</span>
                        <span><i></i>Contributions</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($pendingUsers->count() > 0)
        <div class="board-card mb-4">
            <div class="board-card-header">
                <span>Pending member approvals</span>
                <span class="badge badge-gold">{{ $pendingUsers->count() }}</span>
            </div>
            <div class="board-card-body p-0">
                <div class="table-responsive">
                    <table class="table board-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="px-4">#</th>
                                <th class="px-4">Name</th>
                                <th class="px-4">Email</th>
                                <th class="px-4">Phone</th>
                                <th class="px-4">Registered</th>
                                <th class="px-4">Link to Member</th>
                                <th class="px-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingUsers as $user)
                                <tr>
                                    <td class="px-4">{{ $loop->iteration }}</td>
                                    <td class="px-4 fw-semibold">{{ $user->name }}</td>
                                    <td class="px-4"><small>{{ $user->email }}</small></td>
                                    <td class="px-4">
                                        @if($user->phone)
                                            <i class="bi bi-telephone me-1 text-muted"></i>{{ $user->phone }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4">
                                        <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="px-4">
                                        <form action="{{ route('admin.approve-user', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <div class="input-group input-group-sm" style="width: 260px;">
                                                <select name="member_id" class="form-select" required style="border-radius: 8px 0 0 8px;">
                                                    <option value="">Select Member...</option>
                                                    @foreach(\App\Models\Member::whereDoesntHave('users')->get() as $member)
                                                        <option value="{{ $member->id }}">{{ $member->member_no }} - {{ $member->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-gold" style="border-radius: 0 8px 8px 0;">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-4 text-center">
                                        <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-sm btn-outline-dark-gold" title="View Profile">
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
        <div class="board-card mb-4">
            <div class="board-card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: #FFD700;"></i>
                </div>
                <h4 class="mb-2 fw-bold">All clear</h4>
                <p class="text-muted mb-0">No pending member approvals at this time.</p>
            </div>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4 col-md-6">
            <div class="board-card h-100">
                <div class="board-card-body">
                    <div class="action-row">
                        <div class="action-icon"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="fw-semibold">Members management</div>
                            <div class="small text-muted">Manage all registered members</div>
                        </div>
                    </div>
                    <a href="{{ route('members.index') }}" class="btn w-100 btn-outline-dark-gold mt-3">
                        Go to Members
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="board-card h-100">
                <div class="board-card-body">
                    <div class="action-row">
                        <div class="action-icon"><i class="bi bi-cash-stack"></i></div>
                        <div>
                            <div class="fw-semibold">Contributions</div>
                            <div class="small text-muted">Track member payments</div>
                        </div>
                    </div>
                    <a href="{{ route('contributions.index') }}" class="btn w-100 btn-gold mt-3">
                        View Contributions
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="board-card h-100">
                <div class="board-card-body">
                    <div class="action-row">
                        <div class="action-icon"><i class="bi bi-trophy"></i></div>
                        <div>
                            <div class="fw-semibold">QPL games</div>
                            <div class="small text-muted">Manage pool league</div>
                        </div>
                    </div>
                    <a href="{{ route('qpl-games.index') }}" class="btn w-100 btn-gold mt-3">
                        Manage QPL
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-board .board-top {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        border-radius: 16px;
    }

    .board-eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.7rem;
        color: #6b6b6b;
        margin-bottom: 0.3rem;
    }

    .board-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: #111111;
    }

    .board-subtitle {
        color: #6b6b6b;
        font-size: 0.95rem;
    }

    .board-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.05);
    }

    .board-card-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
        color: #111111;
    }

    .board-card-body {
        padding: 1.25rem;
    }

    .stat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6b6b6b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111111;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 215, 0, 0.18);
        color: #111111;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .board-pill {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: rgba(255, 215, 0, 0.15);
        color: #9c7b00;
        padding: 0.2rem 0.5rem;
        border-radius: 999px;
    }

    .badge-gold {
        background: #FFD700;
        color: #111111;
    }

    .badge-soft-gold {
        background: rgba(255, 215, 0, 0.15);
        color: #9c7b00;
        border: 1px solid rgba(255, 215, 0, 0.4);
    }


    .overview-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    .overview-row:last-child {
        border-bottom: none;
    }

    .overview-label {
        font-size: 0.85rem;
        color: #6b6b6b;
    }

    .overview-value {
        font-size: 1.25rem;
        font-weight: 700;
    }

    .overview-progress {
        flex: 1;
        max-width: 320px;
    }

    .progress {
        height: 8px;
        background: rgba(0, 0, 0, 0.08);
        border-radius: 999px;
    }

    .progress-bar {
        background: #FFD700;
    }

    .donut {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: conic-gradient(#FFD700 0 45%, #111111 45% 70%, rgba(0,0,0,0.15) 70% 100%);
        position: relative;
    }

    .donut::after {
        content: '';
        position: absolute;
        inset: 18px;
        background: #ffffff;
        border-radius: 50%;
    }

    .focus-legend {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        font-size: 0.8rem;
        color: #6b6b6b;
    }

    .focus-legend span {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .focus-legend span i {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #FFD700;
    }

    .focus-legend span:nth-child(2) i {
        background: #111111;
    }

    .focus-legend span:nth-child(3) i {
        background: rgba(0, 0, 0, 0.2);
    }

    .board-table thead {
        background: rgba(255, 215, 0, 0.12);
    }

    .board-table th {
        font-weight: 600;
        font-size: 0.85rem;
        color: #111111;
    }

    .btn-gold {
        background: #FFD700;
        color: #111111;
        border-radius: 10px;
    }

    .btn-gold:hover {
        background: #f0c800;
        color: #111111;
    }

    .btn-outline-dark-gold {
        background: #111111;
        color: #ffffff;
        border: 1px solid #FFD700;
        border-radius: 10px;
    }

    .btn-outline-dark-gold:hover {
        background: #1c1c1c;
        color: #ffffff;
    }

    .action-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .action-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 215, 0, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFD700;
        font-size: 1.2rem;
    }
</style>
@endsection
