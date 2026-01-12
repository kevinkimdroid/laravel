@extends('layouts.app')

@section('title', 'Member Statement')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-file-text me-2"></i>Member Statement
                </h3>
                <p class="text-muted mb-0">
                    Complete contribution history and balance information
                </p>
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Members
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #667eea !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-person text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Member</h6>
                        <h5 class="fw-bold mb-0">{{ $member->name }}</h5>
                        <small class="text-muted">{{ $member->member_no }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid {{ ($isRegistered ?? false) ? '#28a745' : '#ffc107' }} !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ ($isRegistered ?? false) ? 'success' : 'warning' }} bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-{{ ($isRegistered ?? false) ? 'check-circle' : 'exclamation-triangle' }} text-{{ ($isRegistered ?? false) ? 'success' : 'warning' }}" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Registration Status</h6>
                        @if($isRegistered ?? false)
                            <h6 class="fw-bold mb-0 text-success">
                                <i class="bi bi-check-circle me-1"></i>Registered
                            </h6>
                            <small class="text-success">KES {{ number_format($registrationFeePaid ?? 0, 2) }} paid (in bank)</small>
                        @else
                            <h6 class="fw-bold mb-0 text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>Not Registered
                            </h6>
                            <small class="text-muted">KES {{ number_format($registrationFeePaid ?? 0, 2) }} / 1,000.00</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-cash-stack text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Paid</h6>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($totalPaid ?? 0, 2) }}</h4>
                        <small class="text-muted">Monthly contributions only</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid {{ ($outstanding ?? 0) > 0 ? '#dc3545' : '#28a745' }} !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ ($outstanding ?? 0) > 0 ? 'danger' : 'success' }} bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-exclamation-triangle text-{{ ($outstanding ?? 0) > 0 ? 'danger' : 'success' }}" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Outstanding Balance</h6>
                        <h4 class="fw-bold mb-0 text-{{ ($outstanding ?? 0) > 0 ? 'danger' : 'success' }}">
                            {{ number_format($outstanding ?? 0, 2) }}
                        </h4>
                        @if(($outstanding ?? 0) > 0)
                            <small class="text-danger">{{ $monthsBehind ?? 0 }} month(s) behind</small>
                        @else
                            <small class="text-success">All paid up!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(($outstanding ?? 0) > 0 && $member->phone)
<div class="alert alert-info alert-dismissible fade show shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-info-circle me-3" style="font-size: 2rem;"></i>
        <div class="flex-grow-1">
            <h5 class="alert-heading mb-1">Send WhatsApp Reminder</h5>
            <p class="mb-2">This member has an outstanding balance. Use the WhatsApp Reminders page to send reminders to this member or all members with outstanding balances.</p>
            <a href="{{ route('whatsapp.index') }}" class="btn btn-success" style="background-color: #25D366; border-color: #25D366;">
                <i class="bi bi-whatsapp me-1"></i>Go to WhatsApp Reminders
            </a>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-info-circle me-2"></i>Member Information
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2">
                    <strong>Member No:</strong> {{ $member->member_no }}
                </p>
                <p class="mb-2">
                    <strong>Name:</strong> {{ $member->name }}
                </p>
                <p class="mb-2">
                    <strong>Initials:</strong> {{ $member->initials }}
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-2">
                    <strong>Phone:</strong> 
                    @if($member->phone)
                        {{ $member->phone }}
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member->phone) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-success ms-2">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp
                        </a>
                    @else
                        <span class="text-muted">Not provided</span>
                    @endif
                </p>
                <p class="mb-2">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $member->status === 'ACTIVE' ? 'success' : 'secondary' }}">
                        {{ $member->status }}
                    </span>
                </p>
                <p class="mb-2">
                    <strong>Registration Fee:</strong> 
                    @if($isRegistered ?? false)
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Paid - KES {{ number_format($registrationFeePaid ?? 0, 2) }} (in bank)
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-exclamation-triangle me-1"></i>Pending - KES {{ number_format($registrationFeePaid ?? 0, 2) }} / 1,000.00
                        </span>
                    @endif
                </p>
                <p class="mb-2">
                    <strong>Expected Total:</strong> {{ number_format($expectedTotal ?? 0, 2) }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-list-ul me-2"></i>Contribution History
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-white">#</th>
                        <th class="text-white">Date</th>
                        <th class="text-white">Type</th>
                        <th class="text-white">Amount</th>
                        <th class="text-white">Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $contribution)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $contribution->contribution_date->format('M d, Y') }}
                            </td>
                            <td>
                                @if($contribution->type === 'registration_fee')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill me-1"></i>Registration Fee
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        <i class="bi bi-calendar-month me-1"></i>Monthly
                                    </span>
                                @endif
                            </td>
                            <td class="fw-semibold text-success">
                                <i class="bi bi-currency-dollar me-1"></i>{{ number_format($contribution->amount, 2) }}
                            </td>
                            <td>
                                <small class="text-muted">{{ $contribution->transaction_ref ?? '-' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No contributions found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <th colspan="3">Total Contributions (All Types)</th>
                        <th class="text-success">{{ number_format($total, 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
