@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Hero Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg text-white position-relative overflow-hidden" style="background: #000000; border-bottom: 3px solid #D4AF37; min-height: 180px;">
            <div class="position-absolute" style="top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(212, 175, 55, 0.1); border-radius: 50%;"></div>
            <div class="position-absolute" style="bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(212, 175, 55, 0.08); border-radius: 50%;"></div>
            <div class="card-body p-5 position-relative" style="z-index: 1;">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h2 class="mb-3 fw-bold text-white" style="font-size: 1.75rem;">
                            <i class="bi bi-shield-check me-2" style="color: #D4AF37;"></i>Admin Dashboard
                        </h2>
                        <p class="mb-0 text-white-50" style="font-size: 1rem;">
                            Complete control center for managing QBASH Pool League operations
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0 d-flex gap-2 flex-wrap">
                        <span class="badge px-3 py-2" style="background: rgba(212, 175, 55, 0.2); color: #D4AF37; border: 1px solid #D4AF37;">
                            <i class="bi bi-wifi me-1"></i>Online
                        </span>
                        <span class="badge px-3 py-2" style="background: #D4AF37; color: #000000;">
                            <i class="bi bi-shield-lock me-1"></i>Admin Access
                        </span>
                    </div>
                </div>
            </div>
        </div>
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

<!-- Key Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-people-fill" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-arrow-up"></i>
                    </span>
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">Total Members</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.9rem; color: #000000;">{{ $totalMembers }}</h2>
                <p class="text-muted small mb-0 mt-2">Active pool league members</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-person-check-fill" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-check-circle"></i>
                    </span>
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">Total Users</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.9rem; color: #000000;">{{ $totalUsers }}</h2>
                <p class="text-muted small mb-0 mt-2">Registered system users</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-hourglass-split" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    @if($pendingApprovals > 0)
                    <span class="badge rounded-pill px-3 py-2" style="background: #D4AF37; color: #000000; font-size: 0.75rem; animation: pulse 2s infinite;">
                        <i class="bi bi-exclamation-triangle"></i> {{ $pendingApprovals }}
                    </span>
                    @else
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-check-circle"></i>
                    </span>
                    @endif
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">Pending Approvals</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.9rem; color: #D4AF37;">{{ $pendingApprovals }}</h2>
                <p class="text-muted small mb-0 mt-2">Awaiting member approval</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
            <div class="position-absolute top-0 end-0" style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, transparent 100%); border-radius: 0 0 0 100px;"></div>
            <div class="card-body p-4 position-relative" style="border-left: 4px solid #D4AF37;">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="rounded-circle p-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-cash-stack" style="font-size: 1.8rem; color: #D4AF37;"></i>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; font-size: 0.75rem;">
                        <i class="bi bi-currency-exchange"></i>
                    </span>
                </div>
                <h6 class="text-muted text-uppercase small mb-2 fw-semibold" style="letter-spacing: 1px;">Contributions</h6>
                <h2 class="fw-bold mb-0" style="font-size: 1.9rem; color: #000000;">{{ \App\Models\Contribution::count() }}</h2>
                <p class="text-muted small mb-0 mt-2">Total payment records</p>
            </div>
        </div>
    </div>
</div>

<!-- Pending Approvals Alert -->
@if($pendingUsers->count() > 0)
<div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
    <div class="card-header border-0 py-4" style="background: rgba(212, 175, 55, 0.1); border-radius: 16px 16px 0 0;">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-1 fw-bold" style="color: #000000;">
                    <i class="bi bi-hourglass-split me-2" style="color: #D4AF37;"></i>Pending Member Approvals
                </h5>
                <p class="mb-0 text-muted small">Action required: {{ $pendingUsers->count() }} user(s) need member approval</p>
            </div>
            <span class="badge px-3 py-2" style="background: #D4AF37; color: #000000; font-size: 1rem;">{{ $pendingUsers->count() }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead style="background: rgba(212, 175, 55, 0.1);">
                    <tr>
                        <th class="px-4" style="font-weight: 600;">#</th>
                        <th class="px-4" style="font-weight: 600;">Name</th>
                        <th class="px-4" style="font-weight: 600;">Email</th>
                        <th class="px-4" style="font-weight: 600;">Phone</th>
                        <th class="px-4" style="font-weight: 600;">Registered</th>
                        <th class="px-4" style="font-weight: 600;">Link to Member</th>
                        <th class="px-4 text-center" style="font-weight: 600;">Actions</th>
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
                                <div class="input-group input-group-sm" style="width: 280px;">
                                    <select name="member_id" class="form-select" required style="border-radius: 8px 0 0 8px;">
                                        <option value="">Select Member...</option>
                                        @foreach(\App\Models\Member::whereDoesntHave('users')->get() as $member)
                                            <option value="{{ $member->id }}">{{ $member->member_no }} - {{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn" style="background: #D4AF37; color: #000000; border-radius: 0 8px 8px 0;">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td class="px-4 text-center">
                            <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-sm" style="background: #000000; color: #FFFFFF; border: 1px solid #D4AF37; border-radius: 8px;" title="View Profile">
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
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body text-center py-5">
        <div class="mb-3">
            <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: #D4AF37;"></i>
        </div>
        <h4 class="mb-2 fw-bold">All Clear!</h4>
        <p class="text-muted mb-0">No pending member approvals at this time.</p>
    </div>
</div>
@endif

<!-- Quick Actions Grid -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 16px; transition: all 0.3s ease; border-top: 3px solid #D4AF37;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-people-fill" style="font-size: 1.6rem; color: #D4AF37;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">Members Management</h6>
                        <p class="text-muted small mb-0">Manage all registered members</p>
                    </div>
                </div>
                <a href="{{ route('members.index') }}" class="btn w-100" style="background: #000000; color: #FFFFFF; border: 2px solid #D4AF37; border-radius: 10px;">
                    <i class="bi bi-arrow-right-circle me-2"></i>Go to Members
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 16px; transition: all 0.3s ease; border-top: 3px solid #D4AF37;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-cash-stack" style="font-size: 1.6rem; color: #D4AF37;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">Contributions</h6>
                        <p class="text-muted small mb-0">Track member payments</p>
                    </div>
                </div>
                <a href="{{ route('contributions.index') }}" class="btn w-100" style="background: #D4AF37; color: #000000; border-radius: 10px;">
                    <i class="bi bi-arrow-right-circle me-2"></i>View Contributions
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 16px; transition: all 0.3s ease; border-top: 3px solid #D4AF37;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-trophy" style="font-size: 1.6rem; color: #D4AF37;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">QPL Games</h6>
                        <p class="text-muted small mb-0">Manage pool league</p>
                    </div>
                </div>
                <a href="{{ route('qpl-games.index') }}" class="btn w-100" style="background: #D4AF37; color: #000000; border-radius: 10px;">
                    <i class="bi bi-trophy me-2"></i>Manage QPL
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 16px; transition: all 0.3s ease; border-top: 3px solid #25D366;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, rgba(37, 211, 102, 0.15) 0%, rgba(37, 211, 102, 0.05) 100%);">
                        <i class="bi bi-whatsapp" style="font-size: 1.6rem; color: #25D366;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">WhatsApp Reminders</h6>
                        <p class="text-muted small mb-0">Send payment reminders</p>
                    </div>
                </div>
                <a href="{{ route('whatsapp.index') }}" class="btn w-100" style="background: #25D366; color: #FFFFFF; border-radius: 10px;">
                    <i class="bi bi-whatsapp me-2"></i>Send Reminders
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 16px; transition: all 0.3s ease; border-top: 3px solid #D4AF37;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-credit-card" style="font-size: 1.6rem; color: #D4AF37;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">Payment Requests</h6>
                        <p class="text-muted small mb-0">
                            @php $pendingPayments = \App\Models\PaymentRequest::where('status', 'pending')->count(); @endphp
                            @if($pendingPayments > 0)
                                <span class="fw-semibold" style="color: #D4AF37;">{{ $pendingPayments }} pending</span>
                            @else
                                All clear
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.payment-requests') }}" class="btn w-100" style="background: #D4AF37; color: #000000; border-radius: 10px;">
                    <i class="bi bi-credit-card me-2"></i>Review Payments
                    @if($pendingPayments > 0)
                        <span class="badge ms-2" style="background: #000000; color: #FFFFFF;">{{ $pendingPayments }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 16px; transition: all 0.3s ease; border-top: 3px solid #D4AF37;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);">
                        <i class="bi bi-calendar-event" style="font-size: 1.6rem; color: #D4AF37;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 fw-bold">Calendar Activities</h6>
                        <p class="text-muted small mb-0">Manage events & meetings</p>
                    </div>
                </div>
                <a href="{{ route('calendar-activities.index') }}" class="btn w-100" style="background: #D4AF37; color: #000000; border-radius: 10px;">
                    <i class="bi bi-calendar-plus me-2"></i>Manage Calendar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reports Section -->
<div class="card border-0 shadow-lg mb-4" style="border-radius: 16px; border-left: 4px solid #D4AF37;">
    <div class="card-header border-0 py-4" style="background: rgba(212, 175, 55, 0.1); border-radius: 16px 16px 0 0;">
        <h5 class="mb-0 fw-bold" style="color: #000000;">
            <i class="bi bi-file-earmark-text me-2" style="color: #D4AF37;"></i>Reports & Analytics
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                    <div class="rounded-circle p-3 me-3" style="background: rgba(212, 175, 55, 0.1);">
                        <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: #D4AF37;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Outstanding Balances</h6>
                        <p class="text-muted small mb-0">Download report of members with outstanding balances</p>
                    </div>
                    <a href="{{ route('reports.outstanding-balances') }}" class="btn btn-sm" style="background: #D4AF37; color: #000000; border-radius: 8px;">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center p-4 border rounded" style="border-radius: 12px; background: rgba(212, 175, 55, 0.03);">
                    <div class="rounded-circle p-3 me-3" style="background: rgba(212, 175, 55, 0.1);">
                        <i class="bi bi-trophy" style="font-size: 1.5rem; color: #D4AF37;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">Best Contributors</h6>
                        <p class="text-muted small mb-0">Download report of top contributing members</p>
                    </div>
                    <a href="{{ route('reports.best-contributors') }}" class="btn btn-sm" style="background: #D4AF37; color: #000000; border-radius: 8px;">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
</style>
@endsection
