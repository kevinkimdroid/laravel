@extends('layouts.app')

@section('title', 'Payment Requests')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-credit-card me-2"></i>Payment Requests Management
                </h3>
                <p class="text-muted mb-0">
                    Review and approve M-Pesa payment requests from members
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
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

<!-- Pending Payment Requests -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning bg-opacity-10">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-hourglass-split me-2 text-warning"></i>Pending Payment Requests ({{ $pendingRequests->count() }})
        </h5>
    </div>
    <div class="card-body p-0">
        @if($pendingRequests->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Type</th>
                            <th class="text-end">Amount</th>
                            <th>M-Pesa Code</th>
                            <th>Payment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingRequests as $request)
                            <tr>
                                <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <strong>{{ $request->member->name }}</strong><br>
                                    <small class="text-muted">{{ $request->member->member_no }}</small>
                                </td>
                                <td>
                                    @if($request->type === 'registration_fee')
                                        <span class="badge bg-warning text-dark">Registration Fee</span>
                                    @else
                                        <span class="badge bg-primary">Monthly Contribution</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">KES {{ number_format($request->amount, 2) }}</td>
                                <td><code class="bg-light px-2 py-1 rounded">{{ $request->mpesa_code }}</code></td>
                                <td>{{ $request->payment_date->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#approveModal{{ $request->id }}">
                                            <i class="bi bi-check-circle me-1"></i>Approve
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $request->id }}">
                                            <i class="bi bi-x-circle me-1"></i>Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.payment-requests.approve', $request->id) }}">
                                            @csrf
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-check-circle me-2"></i>Approve Payment Request
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <strong>Member:</strong> {{ $request->member->name }} ({{ $request->member->member_no }})<br>
                                                    <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $request->type)) }}<br>
                                                    <strong>Amount:</strong> KES {{ number_format($request->amount, 2) }}<br>
                                                    <strong>M-Pesa Code:</strong> <code>{{ $request->mpesa_code }}</code><br>
                                                    <strong>Payment Date:</strong> {{ $request->payment_date->format('M d, Y') }}
                                                </div>
                                                @if($request->mpesa_message)
                                                    <div class="alert alert-info">
                                                        <strong>M-Pesa Message:</strong><br>
                                                        <small>{{ $request->mpesa_message }}</small>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label">Admin Notes (Optional)</label>
                                                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-check-circle me-1"></i>Approve & Record Contribution
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.payment-requests.reject', $request->id) }}">
                                            @csrf
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-x-circle me-2"></i>Reject Payment Request
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <strong>Member:</strong> {{ $request->member->name }} ({{ $request->member->member_no }})<br>
                                                    <strong>Amount:</strong> KES {{ number_format($request->amount, 2) }}<br>
                                                    <strong>M-Pesa Code:</strong> <code>{{ $request->mpesa_code }}</code>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Explain why this payment is being rejected..." required></textarea>
                                                    <small class="text-muted">This reason will be visible to the member.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Reject Payment
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-check-circle display-4 text-success d-block mb-3"></i>
                <p class="text-muted">No pending payment requests. All payments have been processed.</p>
            </div>
        @endif
    </div>
</div>

<!-- Recently Approved Payments -->
@if($approvedRequests->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="card-header bg-success bg-opacity-10">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-check-circle me-2 text-success"></i>Recently Approved Payments
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Member</th>
                        <th>Type</th>
                        <th class="text-end">Amount</th>
                        <th>M-Pesa Code</th>
                        <th>Approved By</th>
                        <th>Approved At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedRequests as $request)
                        <tr>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>{{ $request->member->name }}</td>
                            <td>
                                @if($request->type === 'registration_fee')
                                    <span class="badge bg-warning text-dark">Registration Fee</span>
                                @else
                                    <span class="badge bg-primary">Monthly</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-success">KES {{ number_format($request->amount, 2) }}</td>
                            <td><code>{{ $request->mpesa_code }}</code></td>
                            <td>{{ $request->approver->name ?? 'N/A' }}</td>
                            <td>{{ $request->approved_at ? $request->approved_at->format('M d, Y H:i') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

