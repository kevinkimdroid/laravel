@if($pendingPayments && $pendingPayments->count() > 0)
<div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-hourglass-split me-3" style="font-size: 2rem;"></i>
        <div class="flex-grow-1">
            <h5 class="alert-heading mb-1">Payment Requests Status</h5>
            <p class="mb-0">
                You have <strong>{{ $pendingPayments->where('status', 'pending')->count() }}</strong> pending, 
                <strong>{{ $pendingPayments->where('status', 'processing')->count() }}</strong> processing payment request(s).
            </p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Quick Payment -->
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #25D366 !important;">
    <div class="card-header bg-success bg-opacity-10">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-phone me-2" style="color: #25D366;"></i>Make Payment via M-Pesa
        </h5>
    </div>
    <div class="card-body">
        <p class="mb-3">Click the button below to initiate an M-Pesa payment. You will receive a prompt on your phone to enter your M-Pesa PIN.</p>
        <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-success btn-lg">
            <i class="bi bi-phone me-2"></i>Pay Now
        </a>
    </div>
</div>

<!-- Payment Submission Form -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-receipt me-2"></i>Submit Payment Details
        </h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('member.contributions.pay') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag me-2 text-primary"></i>Payment Type
                    </label>
                    <select name="type" id="contribution_type" class="form-select" required>
                        <option value="monthly_contribution" {{ old('type', 'monthly_contribution') == 'monthly_contribution' ? 'selected' : '' }}>
                            Monthly Contribution
                        </option>
                        <option value="registration_fee" {{ old('type') == 'registration_fee' ? 'selected' : '' }}>
                            Registration Fee
                        </option>
                    </select>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-currency-dollar me-2 text-primary"></i>Amount Paid
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">KES</span>
                        <input type="number" 
                               step="0.01" 
                               min="0.01" 
                               name="amount" 
                               id="amount"
                               class="form-control" 
                               value="{{ old('amount') }}"
                               placeholder="Enter amount paid"
                               required>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>Payment Date
                    </label>
                    <input type="date" 
                           name="contribution_date" 
                           id="contribution_date"
                           class="form-control" 
                           value="{{ old('contribution_date', date('Y-m-d')) }}" 
                           required
                           max="{{ date('Y-m-d') }}">
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-receipt-cutoff me-2 text-primary"></i>M-Pesa Transaction Code
                    </label>
                    <input type="text" 
                           name="mpesa_code" 
                           class="form-control" 
                           value="{{ old('mpesa_code') }}"
                           placeholder="e.g., QH7A2B3C4D"
                           required
                           pattern="[A-Z0-9]{10}"
                           maxlength="10"
                           style="text-transform: uppercase;">
                    <small class="text-muted">10-character M-Pesa confirmation code</small>
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-chat-left-text me-2 text-primary"></i>M-Pesa Confirmation Message
                    </label>
                    <textarea name="mpesa_message" 
                              class="form-control" 
                              rows="3"
                              placeholder="Paste the full M-Pesa confirmation message here (optional)">{{ old('mpesa_message') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Submit Payment Details
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Payment Calendar -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-calendar-month me-2"></i>Contribution Calendar
        </h5>
    </div>
    <div class="card-body">
        <div id="paymentCalendar"></div>
        <div class="mt-3">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Green dates indicate months with contributions. Click on a date to see contribution details.
            </small>
        </div>
    </div>
</div>

<script>
    // Auto-uppercase M-Pesa code
    document.querySelector('input[name="mpesa_code"]')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    // Calendar display with contribution dates
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('paymentCalendar');
        if (calendarEl) {
            const today = new Date();
            const year = today.getFullYear();
            const month = today.getMonth();
            
            // Get contribution dates
            const contributionDates = @json($contributions->pluck('contribution_date')->map(function($date) {
                return $date->format('Y-m-d');
            })->toArray());
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();
            
            let calendarHTML = '<div class="table-responsive"><table class="table table-bordered text-center">';
            calendarHTML += '<thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead><tbody>';
            
            let day = 1;
            for (let i = 0; i < 6; i++) {
                calendarHTML += '<tr>';
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < startingDayOfWeek) {
                        calendarHTML += '<td></td>';
                    } else if (day > daysInMonth) {
                        calendarHTML += '<td></td>';
                    } else {
                        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        const isPast = new Date(dateStr) < new Date('2024-07-01');
                        const isToday = dateStr === new Date().toISOString().split('T')[0];
                        const hasContribution = contributionDates.includes(dateStr);
                        let className = '';
                        if (isPast) className = 'text-muted bg-light';
                        if (isToday) className = 'bg-primary text-white fw-bold';
                        if (hasContribution) className = 'bg-success text-white fw-bold';
                        if (isToday && hasContribution) className = 'bg-info text-white fw-bold';
                        
                        calendarHTML += `<td class="${className}" style="cursor: pointer;" onclick="selectDate('${dateStr}')">${day}</td>`;
                        day++;
                    }
                }
                calendarHTML += '</tr>';
                if (day > daysInMonth) break;
            }
            calendarHTML += '</tbody></table></div>';
            calendarEl.innerHTML = calendarHTML;
        }
    });

    function selectDate(dateStr) {
        const date = new Date(dateStr);
        const minDate = new Date('2024-07-01');
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (date >= minDate && date <= today) {
            document.getElementById('contribution_date').value = dateStr;
        }
    }
</script>

