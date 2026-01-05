
@extends('layouts.app')

@section('title', 'Contributions Overview')

@section('content')
<style>
    .contributions-table {
        width: 100%;
        table-layout: fixed;
    }
    .contributions-table thead th {
        vertical-align: middle;
        border: 1px solid rgba(255, 255, 255, 0.2);
        white-space: normal;
        word-wrap: break-word;
        padding: 12px 8px;
        text-align: center;
        color: white !important;
        background: transparent !important;
    }
    .contributions-table tbody td {
        vertical-align: middle;
        border: 1px solid #dee2e6;
        padding: 10px 8px;
        text-align: center;
    }
    .contributions-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .contributions-table .col-sn {
        width: 40px;
    }
    .contributions-table .col-member-no {
        width: 70px;
    }
    .contributions-table .col-member-name {
        width: 180px;
        text-align: left !important;
    }
    .contributions-table .col-initials {
        width: 60px;
    }
    .contributions-table .col-reg-fee {
        width: 100px;
    }
    .contributions-table .col-month {
        width: 65px;
        text-align: right !important;
        font-size: 0.85rem;
    }
    .contributions-table .col-outstanding {
        width: 110px;
        text-align: right !important;
    }
    .contributions-table .col-months-behind {
        width: 90px;
    }
    .contributions-table .month-cell {
        font-size: 0.9rem;
        font-weight: 500;
    }
</style>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-cash-stack me-2"></i>Contributions Overview
                </h3>
                <form method="GET" action="{{ route('contributions.index') }}" class="d-inline-flex align-items-center mt-2">
                    <label for="year" class="form-label me-2 mb-0 fw-semibold">Year:</label>
                    <select name="year" id="year" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= now()->year - 10; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('contributions.import.form') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-upload me-1"></i>Import CSV
                </a>
                <a href="{{ route('contributions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Post Contribution
                </a>
            </div>
        </div>
    </div>
</div>

@if (session('import_result'))
    @php
        $result = session('import_result');
        $added = $result['added'] ?? 0;
        $skipped = $result['skipped'] ?? 0;
        $errors = $result['errors'] ?? [];
    @endphp

    <div class="alert {{ $added > 0 ? 'alert-success' : 'alert-warning' }} alert-dismissible fade show">
        <strong>CSV Import Complete:</strong>
        Successfully imported <strong>{{ $added }}</strong> monthly contributions.
        @if ($skipped > 0)
            <strong>{{ $skipped }}</strong> rows were skipped (invalid data or missing members).
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @if (!empty($errors))
        <div class="alert alert-warning alert-dismissible fade show">
            <strong>Import Errors (first {{ count($errors) }}):</strong>
            <ul class="mb-0 small">
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endif

<div class="alert alert-info shadow-sm mb-4">
    <i class="bi bi-info-circle me-2"></i>
    @if($year == 2024)
        Showing contributions per member for <strong>{{ $year }}</strong>. The club officially started in July 2024, so only months from July onwards are shown. Expected contributions: <strong>{{ number_format($expectedPerMonth, 0) }}/month</strong> for {{ count($monthlyKeys) }} months.
    @else
        Showing contributions per member for <strong>{{ $year }}</strong>, broken down by month, with deficit and aging based on an expected <strong>{{ number_format($expectedPerMonth, 0) }}/month</strong>.
    @endif
    <strong>Monthly totals, deficit, and aging are automatically calculated from all contributions for the selected year.</strong>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle contributions-table">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white !important;">
            <tr>
                <th rowspan="2" class="text-white col-sn" style="font-weight: 600; color: white !important; background: transparent !important;">#</th>
                <th rowspan="2" class="text-white col-member-no" style="font-weight: 600; color: white !important; background: transparent !important;">Mbr<br>No</th>
                <th rowspan="2" class="text-white col-member-name" style="font-weight: 600; color: white !important; background: transparent !important;">Name</th>
                <th rowspan="2" class="text-white col-initials" style="font-weight: 600; color: white !important; background: transparent !important;">Init</th>
                <th rowspan="2" class="text-white col-reg-fee" style="font-weight: 600; color: white !important; background: transparent !important;">
                    Reg Fee<br><small>(KES)</small>
                </th>
                <th colspan="{{ count($monthlyKeys) }}" class="text-white" style="font-weight: 600; color: white !important; background: transparent !important;">
                    Monthly (KES)
                </th>
                <th rowspan="2" class="text-white col-outstanding" style="font-weight: 600; color: white !important; background: transparent !important;">
                    Outstanding<br><small>(KES)</small>
                </th>
                <th rowspan="2" class="text-white col-months-behind" style="font-weight: 600; color: white !important; background: transparent !important;">
                    Months<br>Behind
                </th>
            </tr>
            <tr>
                @if($year == 2024)
                    {{-- For 2024, only show July-December --}}
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Jul</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Aug</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Sep</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Oct</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Nov</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Dec</th>
                @else
                    {{-- For other years, show all 12 months --}}
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Jan</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Feb</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Mar</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Apr</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">May</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Jun</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Jul</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Aug</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Sep</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Oct</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Nov</th>
                    <th class="text-white col-month" style="font-weight: 500; color: white !important; background: transparent !important;">Dec</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td class="fw-semibold text-center">{{ $loop->iteration }}</td>
                    <td class="fw-semibold text-center">{{ $row['member']->member_no }}</td>
                    <td class="fw-semibold" style="text-align: left !important;">{{ $row['member']->name }}</td>
                    <td class="fw-semibold text-center" style="font-size: 1rem; letter-spacing: 1px;">{{ $row['initials'] }}</td>
                    <td>
                        @if(isset($row['registration_fee']) && $row['registration_fee'] > 0)
                            <span class="badge bg-warning text-dark fw-semibold">{{ number_format($row['registration_fee'], 2) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    @if($year == 2024)
                        {{-- For 2024, only show July-December --}}
                        <td class="month-cell">{{ ($row['months']['jul'] ?? 0) > 0 ? number_format($row['months']['jul'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['aug'] ?? 0) > 0 ? number_format($row['months']['aug'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['sep'] ?? 0) > 0 ? number_format($row['months']['sep'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['oct'] ?? 0) > 0 ? number_format($row['months']['oct'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['nov'] ?? 0) > 0 ? number_format($row['months']['nov'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['dec'] ?? 0) > 0 ? number_format($row['months']['dec'], 2) : '-' }}</td>
                    @else
                        {{-- For other years, show all 12 months --}}
                        <td class="month-cell">{{ ($row['months']['jan'] ?? 0) > 0 ? number_format($row['months']['jan'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['feb'] ?? 0) > 0 ? number_format($row['months']['feb'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['mar'] ?? 0) > 0 ? number_format($row['months']['mar'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['apr'] ?? 0) > 0 ? number_format($row['months']['apr'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['may'] ?? 0) > 0 ? number_format($row['months']['may'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['jun'] ?? 0) > 0 ? number_format($row['months']['jun'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['jul'] ?? 0) > 0 ? number_format($row['months']['jul'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['aug'] ?? 0) > 0 ? number_format($row['months']['aug'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['sep'] ?? 0) > 0 ? number_format($row['months']['sep'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['oct'] ?? 0) > 0 ? number_format($row['months']['oct'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['nov'] ?? 0) > 0 ? number_format($row['months']['nov'], 2) : '-' }}</td>
                        <td class="month-cell">{{ ($row['months']['dec'] ?? 0) > 0 ? number_format($row['months']['dec'], 2) : '-' }}</td>
                    @endif
                    <td>
                        @if($row['deficit'] > 0)
                            <span class="badge bg-danger fw-semibold">{{ number_format($row['deficit'], 2) }}</span>
                        @else
                            <span class="badge bg-success fw-semibold">{{ number_format($row['deficit'], 2) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($row['aging'] > 3)
                            <span class="badge bg-danger fw-semibold">{{ $row['aging'] }}</span>
                        @elseif($row['aging'] > 0)
                            <span class="badge bg-warning text-dark fw-semibold">{{ $row['aging'] }}</span>
                        @else
                            <span class="badge bg-success fw-semibold">{{ $row['aging'] }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="19" class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                        <p class="text-muted">No members or contributions found for {{ $year }}.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
        </div>
    </div>
</div>
@endsection
