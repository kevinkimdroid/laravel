@extends('layouts.app')

@section('title', 'WhatsApp Reminders')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-whatsapp me-2" style="color: #25D366;"></i>WhatsApp Reminders
                </h3>
                <p class="text-muted mb-0">
                    Send payment reminders to members with outstanding balances
                </p>
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Members
            </a>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(count($membersWithOutstanding) > 0)
<form id="bulkForm" method="POST" action="{{ route('whatsapp.send-bulk') }}">
    @csrf
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-people me-2"></i>Members with Outstanding Balances ({{ count($membersWithOutstanding) }})
            </h5>
            <button type="button" class="btn btn-sm btn-success" onclick="selectAll()">
                <i class="bi bi-check-all me-1"></i>Select All
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleAll(this)">
                            </th>
                            <th>Member No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th class="text-end">Outstanding</th>
                            <th class="text-center">Months Behind</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($membersWithOutstanding as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" 
                                           name="member_ids[]" 
                                           value="{{ $item['member']->id }}"
                                           class="member-checkbox">
                                </td>
                                <td>{{ $item['member']->member_no }}</td>
                                <td>{{ $item['member']->name }}</td>
                                <td>
                                    {{ $item['member']->phone }}
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item['member']->phone) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-success ms-2">
                                        <i class="bi bi-whatsapp me-1"></i>Open
                                    </a>
                                </td>
                                <td class="text-end fw-bold text-danger">
                                    {{ number_format($item['outstanding'], 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $item['monthsBehind'] }} month(s)</span>
                                </td>
                                <td>
                                    <a href="{{ route('whatsapp.send', $item['member']->id) }}" 
                                       class="btn btn-sm btn-success"
                                       target="_blank">
                                        <i class="bi bi-whatsapp me-1"></i>Send Reminder
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-whatsapp me-1"></i>Send Bulk Reminders to Selected
            </button>
            <small class="text-muted ms-3">
                <i class="bi bi-info-circle me-1"></i>This will open WhatsApp for each selected member in new tabs
            </small>
        </div>
    </div>
</form>
@else
<div class="alert alert-success shadow-sm">
    <i class="bi bi-check-circle me-2"></i>
    <strong>Great news!</strong> All members are up to date with their contributions. No outstanding balances.
</div>
@endif

<script>
    function toggleAll(checkbox) {
        const checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
    }
    
    function selectAll() {
        const checkbox = document.getElementById('selectAllCheckbox');
        checkbox.checked = true;
        toggleAll(checkbox);
    }
</script>
@endsection

