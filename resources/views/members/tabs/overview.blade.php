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
        <div class="card border-0 shadow-sm" style="border-left: 4px solid {{ $isRegistered ? '#28a745' : '#ffc107' }} !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ $isRegistered ? 'success' : 'warning' }} bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-{{ $isRegistered ? 'check-circle' : 'exclamation-triangle' }} text-{{ $isRegistered ? 'success' : 'warning' }}" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Registration</h6>
                        @if($isRegistered)
                            <h6 class="fw-bold mb-0 text-success">
                                <i class="bi bi-check-circle me-1"></i>Registered
                            </h6>
                            <small class="text-success">KES {{ number_format($registrationFeePaid, 2) }} paid (in bank)</small>
                        @else
                            <h6 class="fw-bold mb-0 text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>Not Registered
                            </h6>
                            <small class="text-muted">KES {{ number_format($registrationFeePaid, 2) }} / 1,000.00</small>
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
                        <small class="text-muted">Monthly contributions</small>
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
                        <h6 class="text-muted text-uppercase small mb-0">Outstanding</h6>
                        <h4 class="fw-bold mb-0 text-{{ ($outstanding ?? 0) > 0 ? 'danger' : 'success' }}">
                            {{ number_format($outstanding ?? 0, 2) }}
                        </h4>
                        @if(($outstanding ?? 0) > 0)
                            <small class="text-danger">Outstanding balance</small>
                        @else
                            <small class="text-success">All paid up!</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
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
                    @if($isRegistered)
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Paid - KES {{ number_format($registrationFeePaid, 2) }} (in bank)
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-exclamation-triangle me-1"></i>Pending - KES {{ number_format($registrationFeePaid, 2) }} / 1,000.00
                        </span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

@if(isset($outstandingMonths) && count($outstandingMonths) > 0)
<!-- Outstanding Months Section -->
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #dc3545 !important;">
    <div class="card-header bg-danger bg-opacity-10 border-0 py-3">
        <h5 class="mb-0 fw-bold text-danger">
            <i class="bi bi-calendar-x me-2"></i>Outstanding Months ({{ count($outstandingMonths) }})
        </h5>
        <p class="mb-0 mt-2 text-muted small">Months with unpaid or partially paid contributions</p>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($outstandingMonths as $month)
                <div class="col-md-4 col-lg-3">
                    <div class="card border-danger h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold text-danger mb-0">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $month['short_month'] }}
                                </h6>
                                <span class="badge bg-danger">{{ $month['outstanding'] > 0 ? 'Unpaid' : 'Partial' }}</span>
                            </div>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Expected:</span>
                                    <span class="fw-semibold">KES {{ number_format($month['expected'], 0) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Paid:</span>
                                    <span class="fw-semibold text-success">KES {{ number_format($month['paid'], 0) }}</span>
                                </div>
                                <div class="d-flex justify-content-between pt-2 border-top">
                                    <span class="text-muted">Outstanding:</span>
                                    <span class="fw-bold text-danger">KES {{ number_format($month['outstanding'], 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">
            <a href="{{ route('members.statement', $member) }}" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-text me-1"></i>View Full Statement
            </a>
        </div>
    </div>
</div>
@elseif(isset($outstandingMonths))
<!-- All Paid Up -->
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #28a745 !important;">
    <div class="card-header bg-success bg-opacity-10 border-0 py-3">
        <h5 class="mb-0 fw-bold text-success">
            <i class="bi bi-check-circle me-2"></i>All Months Paid
        </h5>
        <p class="mb-0 mt-2 text-muted small">This member is up to date with all monthly contributions</p>
    </div>
    <div class="card-body">
        <div class="text-center py-3">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
            <p class="mt-3 mb-0 fw-semibold text-success">No outstanding months</p>
        </div>
    </div>
</div>
@endif

