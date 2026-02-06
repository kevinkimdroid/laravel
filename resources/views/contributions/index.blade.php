
@extends('layouts.app')

@section('title', 'Contributions Overview')

@section('content')
<style>
    .contributions-table {
        width: 100%;
        min-width: 1200px;
        table-layout: fixed;
    }
    .contributions-table thead th {
        vertical-align: middle;
        border: 1px solid rgba(255, 255, 255, 0.2);
        white-space: normal;
        word-wrap: break-word;
        padding: 6px 6px;
        text-align: center;
        color: white !important;
        background: transparent !important;
    }
    .contributions-table tbody td {
        vertical-align: middle;
        border: 1px solid #dee2e6;
        padding: 6px 6px;
        text-align: center;
        font-size: 0.85rem;
    }
    .contributions-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }
    .contributions-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .contributions-table tbody tr:nth-child(even) {
        background-color: rgba(255, 215, 0, 0.04);
    }
    .contributions-table tbody td.month-cell {
        position: relative;
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
        width: 52px;
        min-width: 52px;
        text-align: right !important;
        font-size: 0.78rem;
    }
    .contributions-table .col-outstanding {
        width: 110px;
        text-align: right !important;
    }
    .contributions-table .col-total-paid {
        width: 95px;
        text-align: right !important;
    }
    .contributions-table .col-months-behind {
        width: 90px;
    }
    .contributions-table .month-cell {
        font-size: 0.82rem;
        font-weight: 500;
    }
    .contributions-table thead th {
        font-size: 0.82rem;
    }
    .contributions-table tbody td {
        line-height: 1.2;
    }
    .contributions-table thead th.col-month {
        white-space: nowrap;
        word-break: keep-all;
        line-height: 1;
        padding-top: 4px;
        padding-bottom: 4px;
    }
    .contributions-toolbar .card-body {
        padding: 1.25rem;
    }
</style>
<div class="card border-0 shadow-sm mb-3 position-relative overflow-hidden contributions-toolbar" style="background: #FFFFFF; border-bottom: 3px solid #FFD700; border-radius: 16px;">
    <div class="position-absolute" style="top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255, 215, 0, 0.08); border-radius: 50%;"></div>
    <div class="card-body position-relative" style="z-index: 1;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h3 class="mb-1 fw-bold" style="color: #000000;">
                    <i class="bi bi-cash-stack me-2" style="color: #FFD700;"></i>Contributions
                </h3>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                    <label for="year" class="form-label mb-0 fw-semibold">Year</label>
                    <form method="GET" action="{{ route('contributions.index') }}" id="yearForm" class="d-flex align-items-center gap-2">
                        <select name="year" id="year" class="form-select form-select-sm shadow-sm" style="width: 110px; font-weight: 600; border: 1px solid #FFD700; background: #ffffff;" onchange="document.getElementById('yearForm').submit();">
                            @for($y = now()->year; $y >= 2024; $y--)
                                <option value="{{ $y }}" {{ (int)$year === (int)$y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <button type="submit" class="btn btn-sm shadow-sm" id="yearSubmitBtn" style="background: #FFD700; color: #000000; border: none; border-radius: 8px;">
                            <i class="bi bi-arrow-clockwise me-1"></i>Load
                        </button>
                    </form>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('contributions.create') }}" class="btn btn-sm" style="background: #FFD700; color: #000000; border-radius: 8px;">
                    <i class="bi bi-plus-circle me-1"></i>Post Contribution
                </a>
                <a href="{{ route('contributions.import.form') }}" class="btn btn-sm btn-outline-dark" style="border-color: #FFD700; color: #000000; border-radius: 8px;">
                    <i class="bi bi-upload me-1"></i>Import CSV
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm mb-3" style="border-radius: 16px; border-left: 4px solid #FFD700;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('contributions.index') }}" class="row g-3 align-items-end">
            <input type="hidden" name="year" value="{{ $year }}">
            <div class="col-md-8">
                <label for="search" class="form-label fw-semibold">
                    <i class="bi bi-search me-2" style="color: #FFD700;"></i>Search Members
                </label>
                <div class="input-group">
                    <span class="input-group-text" style="background: #f8f9fa; border-color: #FFD700;">
                        <i class="bi bi-search" style="color: #FFD700;"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search by name, member number, phone, or initials..."
                           style="border-color: #FFD700;">
                    @if(request('search'))
                        <a href="{{ route('contributions.index', ['year' => $year]) }}" class="btn btn-outline-secondary" title="Clear search" style="border-color: #FFD700;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn w-100 btn-sm" style="background: #FFD700; color: #000000; border-radius: 8px;">
                    <i class="bi bi-search me-1"></i>Search
                </button>
            </div>
        </form>
        @if(request('search'))
            <div class="mt-2">
                <small class="text-muted">
                    Showing results for: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('contributions.index', ['year' => $year]) }}" class="text-decoration-none ms-2" style="color: #FFD700;">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </small>
            </div>
        @endif
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

<details class="alert shadow-sm mb-3 border-start border-4" style="background: rgba(255, 215, 0, 0.08); border-color: #FFD700 !important; border-radius: 12px;">
    <summary class="fw-semibold d-flex align-items-center justify-content-between" style="cursor: pointer; color: #000000;">
        <span><i class="bi bi-info-circle me-2" style="color: #FFD700;"></i>Contributions for {{ $year }}</span>
        <span class="small text-muted">Click to expand</span>
    </summary>
    <div class="mt-2">
        @if($year == 2024)
            <p class="mb-1">The club officially started in <strong>July 2024</strong>, so only months from July onwards are shown.</p>
            <p class="mb-0">Expected contributions: <strong style="color: #FFD700;">{{ number_format($expectedPerMonth, 0) }} KES/month</strong> for {{ count($monthlyKeys) }} months.</p>
        @else
            <p class="mb-1">Showing all 12 months for <strong>{{ $year }}</strong>.</p>
            <p class="mb-0">Expected contributions: <strong style="color: #FFD700;">{{ number_format($expectedPerMonth, 0) }} KES/month</strong>.</p>
        @endif
        <p class="mb-0 mt-2 small">
            <i class="bi bi-lightbulb me-1"></i><strong>Note:</strong> The table shows contributions paid in <strong>{{ $year }}</strong>. Outstanding balances are <strong>cumulative</strong> - they include all previous years' outstanding brought forward to the end of <strong>{{ $year }}</strong>{{ $year == now()->year ? ' (current month)' : '' }}, calculated from each member's join date.
        </p>
    </div>
</details>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0 align-middle contributions-table">
                <thead style="background: #FFF9D6; border-bottom: 2px solid #FFD700; color: #000000 !important;">
            <tr>
                <th rowspan="2" class="col-sn" style="font-weight: 600; color: #000000 !important; background: transparent !important;">#</th>
                <th rowspan="2" class="col-member-no" style="font-weight: 600; color: #000000 !important; background: transparent !important;">Mbr<br>No</th>
                <th rowspan="2" class="col-member-name" style="font-weight: 600; color: #000000 !important; background: transparent !important;">Name</th>
                <th rowspan="2" class="col-initials" style="font-weight: 600; color: #000000 !important; background: transparent !important;">Init</th>
                <th rowspan="2" class="col-reg-fee" style="font-weight: 600; color: #000000 !important; background: transparent !important;">
                    Reg Fee<br><small>(KES)</small>
                </th>
                <th colspan="{{ count($monthlyKeys) }}" style="font-weight: 600; color: #000000 !important; background: transparent !important;">
                    Monthly (KES)
                </th>
                <th rowspan="2" class="col-total-paid" style="font-weight: 600; color: #000000 !important; background: transparent !important;">
                    Total Paid<br><small>(KES)</small>
                </th>
                <th rowspan="2" class="col-outstanding" style="font-weight: 600; color: #000000 !important; background: transparent !important;">
                    Outstanding<br><small>(KES)</small>
                </th>
                <th rowspan="2" class="col-months-behind" style="font-weight: 600; color: #000000 !important; background: transparent !important;">
                    Months<br>Behind
                </th>
            </tr>
            <tr>
                @if($year == 2024)
                    {{-- For 2024, only show July-December --}}
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Jul</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Aug</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Sep</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Oct</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Nov</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Dec</th>
                @else
                    {{-- For other years, show all 12 months --}}
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Jan</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Feb</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Mar</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Apr</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">May</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Jun</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Jul</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Aug</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Sep</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Oct</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Nov</th>
                    <th class="col-month" style="font-weight: 500; color: #000000 !important; background: transparent !important;">Dec</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr class="align-middle">
                    <td class="fw-semibold text-center">{{ $loop->iteration }}</td>
                    <td class="fw-semibold text-center">QBM{{ $row['member']->member_no }}</td>
                    <td class="fw-semibold" style="text-align: left !important;">{{ $row['member']->name }}</td>
                    <td class="fw-semibold text-center" style="font-size: 0.9rem; letter-spacing: 0.5px; font-weight: 700;">{{ $row['initials'] }}</td>
                    <td>
                        @if(isset($row['registration_fee']) && $row['registration_fee'] > 0)
                            <span class="badge fw-semibold" style="background: #FFD700; color: #000000;">{{ number_format($row['registration_fee'], 2) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    @php
                        $joinDate = $row['join_date'];
                        $joinYear = $joinDate->year;
                        $joinMonth = $joinDate->month;
                    @endphp
                    @if($year == 2024)
                        {{-- For 2024, only show July-December --}}
                        @php
                            $monthData = [
                                'jul' => ['num' => 7, 'name' => 'Jul'],
                                'aug' => ['num' => 8, 'name' => 'Aug'],
                                'sep' => ['num' => 9, 'name' => 'Sep'],
                                'oct' => ['num' => 10, 'name' => 'Oct'],
                                'nov' => ['num' => 11, 'name' => 'Nov'],
                                'dec' => ['num' => 12, 'name' => 'Dec'],
                            ];
                        @endphp
                        @foreach(['jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $monthKey)
                            @php
                                $monthNum = $monthData[$monthKey]['num'];
                                $isBeforeJoin = ($year < $joinYear) || ($year == $joinYear && $monthNum < $joinMonth);
                            @endphp
                            <td class="month-cell" style="@if($isBeforeJoin) background-color: #f8f9fa; opacity: 0.6; @endif">
                                @if($isBeforeJoin)
                                    <span class="badge bg-light text-muted border" style="font-size: 0.7rem; padding: 3px 6px; font-weight: 500;" title="Not a member until {{ $joinDate->format('M Y') }}">
                                        <i class="bi bi-person-x me-1"></i>N/A
                                    </span>
                                @else
                                    {{ ($row['months'][$monthKey] ?? 0) > 0 ? number_format($row['months'][$monthKey], 2) : '-' }}
                                @endif
                            </td>
                        @endforeach
                    @else
                        {{-- For other years, show all 12 months --}}
                        @php
                            $monthData = [
                                'jan' => ['num' => 1, 'name' => 'Jan'],
                                'feb' => ['num' => 2, 'name' => 'Feb'],
                                'mar' => ['num' => 3, 'name' => 'Mar'],
                                'apr' => ['num' => 4, 'name' => 'Apr'],
                                'may' => ['num' => 5, 'name' => 'May'],
                                'jun' => ['num' => 6, 'name' => 'Jun'],
                                'jul' => ['num' => 7, 'name' => 'Jul'],
                                'aug' => ['num' => 8, 'name' => 'Aug'],
                                'sep' => ['num' => 9, 'name' => 'Sep'],
                                'oct' => ['num' => 10, 'name' => 'Oct'],
                                'nov' => ['num' => 11, 'name' => 'Nov'],
                                'dec' => ['num' => 12, 'name' => 'Dec'],
                            ];
                        @endphp
                        @foreach(['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $monthKey)
                            @php
                                $monthNum = $monthData[$monthKey]['num'];
                                $isBeforeJoin = ($year < $joinYear) || ($year == $joinYear && $monthNum < $joinMonth);
                            @endphp
                            <td class="month-cell" style="@if($isBeforeJoin) background-color: #f8f9fa; opacity: 0.6; @endif">
                                @if($isBeforeJoin)
                                    <span class="badge bg-light text-muted border" style="font-size: 0.7rem; padding: 3px 6px; font-weight: 500;" title="Not a member until {{ $joinDate->format('M Y') }}">
                                        <i class="bi bi-person-x me-1"></i>N/A
                                    </span>
                                @else
                                    {{ ($row['months'][$monthKey] ?? 0) > 0 ? number_format($row['months'][$monthKey], 2) : '-' }}
                                @endif
                            </td>
                        @endforeach
                    @endif
                    @php
                        $yearTotal = collect($row['months'] ?? [])->sum();
                    @endphp
                    <td class="fw-semibold text-end">
                        {{ $yearTotal > 0 ? number_format($yearTotal, 2) : '-' }}
                    </td>
                    <td>
                        @if($row['outstanding'] > 0)
                            <span class="badge fw-semibold" style="background: #111111; color: #FFFFFF;">{{ number_format($row['outstanding'], 2) }}</span>
                        @else
                            <span class="badge fw-semibold" style="background: #FFD700; color: #000000;">{{ number_format($row['outstanding'], 2) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($row['aging'] > 3)
                            <span class="badge fw-semibold" style="background: #111111; color: #FFFFFF;">{{ $row['aging'] }}</span>
                        @elseif($row['aging'] > 0)
                            <span class="badge fw-semibold" style="background: #FFD700; color: #000000;">{{ $row['aging'] }}</span>
                        @else
                            <span class="badge fw-semibold" style="background: #FFD700; color: #000000;">{{ $row['aging'] }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 6 + count($monthlyKeys) }}" class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                        <h5 class="text-muted mb-2">No Data Available</h5>
                        <p class="text-muted">No members or contributions found for {{ $year }}.</p>
                        <a href="{{ route('contributions.create') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-1"></i>Add First Contribution
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
        </div>
    </div>
</div>

<script>
    // Smooth year selection with loading indicator
    document.addEventListener('DOMContentLoaded', function() {
        const yearSelect = document.getElementById('year');
        const yearForm = document.getElementById('yearForm');
        const submitBtn = document.getElementById('yearSubmitBtn');
        
        if (yearSelect && yearForm && submitBtn) {
            // Show loading when form is submitted
            yearForm.addEventListener('submit', function(e) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Loading...';
                submitBtn.disabled = true;
                yearSelect.disabled = true;
            });
        }
    });
</script>
@endsection
