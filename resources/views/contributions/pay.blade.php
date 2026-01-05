@extends('layouts.app')

@section('title', 'Pay Contribution via M-Pesa')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-phone me-2"></i>Pay via M-Pesa
                </h3>
                <p class="text-muted mb-0">
                    Member: <strong>{{ $member->name }}</strong> ({{ $member->member_no }})
                </p>
            </div>
            <a href="{{ route('member.contributions') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Contributions
            </a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Error:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- M-Pesa STK Push Payment -->
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #25D366 !important;">
    <div class="card-header bg-success bg-opacity-10">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-phone me-2" style="color: #25D366;"></i>Pay via M-Pesa STK Push
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-4">
            <h6 class="fw-semibold mb-2">
                <i class="bi bi-info-circle me-2"></i>How It Works
            </h6>
            <p class="mb-0">
                Enter your payment details below and click "Pay Now". An M-Pesa payment prompt will be sent directly to your phone. 
                Simply enter your M-Pesa PIN to complete the payment. Your payment will be automatically verified and recorded.
            </p>
        </div>
    </div>
</div>

<!-- Payment Form -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-credit-card me-2"></i>Make Payment
        </h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('member.contributions.pay') }}" id="paymentForm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag me-2 text-primary"></i>Payment Type <span class="text-danger">*</span>
                    </label>
                    <select name="type" id="contribution_type" class="form-select" required>
                        <option value="monthly_contribution" {{ old('type', 'monthly_contribution') == 'monthly_contribution' ? 'selected' : '' }}>
                            Monthly Contribution
                        </option>
                        <option value="registration_fee" {{ old('type') == 'registration_fee' ? 'selected' : '' }}>
                            Registration Fee (KES 1,000)
                        </option>
                    </select>
                    <small class="text-muted">Select what you are paying for</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-currency-dollar me-2 text-primary"></i>Amount (KES) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">KES</span>
                        <input type="number" 
                               step="1" 
                               min="1" 
                               name="amount" 
                               id="amount"
                               class="form-control" 
                               value="{{ old('amount') }}"
                               placeholder="Enter amount"
                               required>
                    </div>
                    <small class="text-muted">Minimum amount: KES 1</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>Payment Date <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-calendar-check text-muted"></i>
                        </span>
                        <input type="date" 
                               name="contribution_date" 
                               id="contribution_date"
                               class="form-control" 
                               value="{{ old('contribution_date', date('Y-m-d')) }}" 
                               required
                               max="{{ date('Y-m-d') }}"
                               min="2024-07-01">
                    </div>
                    <small class="text-muted">Select the payment date</small>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-telephone me-2 text-primary"></i>M-Pesa Phone Number <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-phone text-muted"></i>
                        </span>
                        <input type="tel" 
                               name="phone_number" 
                               id="phone_number"
                               class="form-control" 
                               value="{{ old('phone_number', $member->phone) }}"
                               placeholder="e.g., 0712345678 or 254712345678"
                               required
                               pattern="[0-9]{9,12}">
                    </div>
                    <small class="text-muted">Enter your M-Pesa registered phone number</small>
                </div>
            </div>

            <div class="alert alert-warning shadow-sm">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Important:</strong> 
                <ul class="mb-0 mt-2">
                    <li>Ensure your phone number is registered with M-Pesa</li>
                    <li>You will receive an M-Pesa prompt on your phone</li>
                    <li>Enter your M-Pesa PIN to complete the payment</li>
                    <li>Payment will be automatically verified and recorded</li>
                </ul>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-success btn-lg" id="payButton">
                    <i class="bi bi-phone me-2"></i>Pay Now
                </button>
                <a href="{{ route('member.contributions') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-format phone number
    document.getElementById('phone_number')?.addEventListener('input', function(e) {
        // Remove all non-numeric characters
        let value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = value;
    });

    // Auto-set amount based on payment type
    document.getElementById('contribution_type')?.addEventListener('change', function(e) {
        const amountInput = document.getElementById('amount');
        if (e.target.value === 'registration_fee') {
            amountInput.value = '1000';
        } else {
            // Default monthly contribution amount based on current year
            const currentYear = new Date().getFullYear();
            const defaultAmount = currentYear >= 2026 ? 300 : 250;
            amountInput.value = defaultAmount;
        }
    });

    // Set default amount on page load
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('contribution_type');
        if (typeSelect && typeSelect.value === 'monthly_contribution') {
            const currentYear = new Date().getFullYear();
            const defaultAmount = currentYear >= 2026 ? 300 : 250;
            const amountInput = document.getElementById('amount');
            if (!amountInput.value) {
                amountInput.value = defaultAmount;
            }
        }
    });

    // Handle form submission
    document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
        const payButton = document.getElementById('payButton');
        if (payButton) {
            payButton.disabled = true;
            payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        }
    });
</script>

<script>
    // Auto-uppercase M-Pesa code
    document.querySelector('input[name="mpesa_code"]')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
</script>
@endsection
