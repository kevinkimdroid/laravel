@extends('layouts.app')

@section('title', 'My Contributions')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-cash-stack me-2"></i>My Contributions
                </h3>
                <p class="text-muted mb-0">
                    Member: <strong>{{ $member->name }}</strong> ({{ $member->member_no }})
                </p>
            </div>
            <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-success" style="background-color: #25D366; border-color: #25D366;">
                <i class="bi bi-phone me-1"></i>Pay via M-Pesa
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-cash-stack text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Paid (All)</h6>
                        <h4 class="fw-bold mb-0 text-success">{{ number_format($total, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #667eea !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-calendar-month text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Monthly Contributions</h6>
                        <h4 class="fw-bold mb-0">{{ number_format($monthlyTotal ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-star-fill text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Registration Fee</h6>
                        <h4 class="fw-bold mb-0">{{ number_format($registrationTotal ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-left: 4px solid #17a2b8 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-calendar3 text-info" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small mb-0">Total Contributions</h6>
                        <h4 class="fw-bold mb-0">{{ $contributions->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($pendingPayments && $pendingPayments->count() > 0)
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #ffc107 !important;">
    <div class="card-header bg-warning bg-opacity-10">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-hourglass-split me-2 text-warning"></i>Pending Payment Requests ({{ $pendingPayments->count() }})
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>M-Pesa Code</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPayments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>
                                @if($payment->type === 'registration_fee')
                                    <span class="badge bg-warning text-dark">Registration Fee</span>
                                @else
                                    <span class="badge bg-primary">Monthly</span>
                                @endif
                            </td>
                            <td class="fw-semibold">KES {{ number_format($payment->amount, 2) }}</td>
                            <td><code>{{ $payment->mpesa_code }}</code></td>
                            <td>
                                <span class="badge bg-warning">
                                    <i class="bi bi-hourglass-split me-1"></i>Pending Review
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light">
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>Your payment requests are being reviewed by the admin. You will be notified once they are approved.
        </small>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm">
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
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No contributions found. Start by adding your first contribution.</p>
                                <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Add Contribution
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
