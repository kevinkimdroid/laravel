@extends('layouts.app')

@section('title', 'Create Contribution')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h3 class="mb-1 fw-bold text-primary">
            <i class="bi bi-plus-circle me-2"></i>Create Contribution
        </h3>
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

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('contributions.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person me-2 text-primary"></i>Member
                    </label>
                    <select name="member_id" class="form-select" required>
                        <option value="">Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->member_no }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag me-2 text-primary"></i>Contribution Type
                    </label>
                    <select name="type" id="contribution_type" class="form-select" required>
                        <option value="monthly_contribution" {{ old('type', 'monthly_contribution') == 'monthly_contribution' ? 'selected' : '' }}>
                            Monthly Contribution (Default varies by year: 250 for 2024-2025, 300 for 2026+)
                        </option>
                        <option value="registration_fee" {{ old('type') == 'registration_fee' ? 'selected' : '' }}>
                            Registration Fee (Default: 1000)
                        </option>
                    </select>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>Contribution Date
                    </label>
                    <input type="date" 
                           name="contribution_date" 
                           class="form-control" 
                           value="{{ old('contribution_date', date('Y-m-d')) }}" 
                           required
                           min="2024-07-01">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-currency-dollar me-2 text-primary"></i>Amount
                    </label>
                    <input type="number" 
                           step="0.01" 
                           min="0.01" 
                           name="amount" 
                           id="amount"
                           class="form-control" 
                           value="{{ old('amount') }}"
                           placeholder="Leave empty for default">
                    <small class="text-muted">
                        Default: <span id="default_amount">250</span> (varies by year: 250 for 2024-2025, 300 for 2026+)
                    </small>
                </div>
            </div>

            <div class="alert alert-info shadow-sm">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> 
                <ul class="mb-0 mt-2">
                    <li><strong>Registration Fee:</strong> One-time payment of 1000</li>
                    <li><strong>Monthly Contribution:</strong> 250/month for 2024-2025, 300/month for 2026+</li>
                </ul>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Create Contribution
                </button>
                <a href="{{ route('contributions.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Update default amount based on type and date
    const contributionDateInput = document.querySelector('input[name="contribution_date"]');
    const contributionTypeSelect = document.getElementById('contribution_type');
    
    function updateDefaultAmount() {
        const type = contributionTypeSelect.value;
        const dateValue = contributionDateInput.value;
        let defaultAmount = 1000; // registration fee default
        
        if (type === 'monthly_contribution') {
            if (dateValue) {
                const year = new Date(dateValue).getFullYear();
                defaultAmount = (year >= 2026) ? 300 : 250;
            } else {
                defaultAmount = 250; // default to 250 if no date selected
            }
        }
        
        document.getElementById('default_amount').textContent = defaultAmount;
        
        // Update placeholder
        const amountInput = document.getElementById('amount');
        if (!amountInput.value) {
            amountInput.placeholder = `Leave empty for default (${defaultAmount})`;
        }
    }
    
    contributionTypeSelect.addEventListener('change', updateDefaultAmount);
    if (contributionDateInput) {
        contributionDateInput.addEventListener('change', updateDefaultAmount);
    }
</script>
@endsection
