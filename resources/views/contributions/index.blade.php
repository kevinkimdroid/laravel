
@extends('layouts.app')

@section('title', 'Contributions Overview')

@section('content')
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
    Showing contributions per member for <strong>{{ $year }}</strong>, broken down by month, with deficit and aging based on an expected 250 per month.
    <strong>Monthly totals, deficit, and aging are automatically calculated from all contributions for the selected year.</strong>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <tr>
                <th rowspan="2" class="text-white">#</th>
                <th rowspan="2" class="text-white">Member</th>
                <th rowspan="2" class="text-white">Initials</th>
                <th rowspan="2" class="text-white">Registration Fee</th>
                <th colspan="12" class="text-white">Monthly Contributions</th>
                <th rowspan="2" class="text-white">Deficit</th>
                <th rowspan="2" class="text-white">Aging</th>
            </tr>
            <tr>
                <th class="text-white">Jan</th>
                <th class="text-white">Feb</th>
                <th class="text-white">Mar</th>
                <th class="text-white">Apr</th>
                <th class="text-white">May</th>
                <th class="text-white">Jun</th>
                <th class="text-white">Jul</th>
                <th class="text-white">Aug</th>
                <th class="text-white">Sep</th>
                <th class="text-white">Oct</th>
                <th class="text-white">Nov</th>
                <th class="text-white">Dec</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['member']->name }}</td>
                    <td class="text-center">{{ $row['initials'] }}</td>
                    <td class="text-end">{{ number_format($row['member']->registration_fee ?? 1000, 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['jan'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['feb'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['mar'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['apr'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['may'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['jun'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['jul'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['aug'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['sep'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['oct'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['nov'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['months']['dec'], 2) }}</td>
                    <td class="text-end">
                        @if($row['deficit'] > 0)
                            <span class="badge bg-danger">{{ number_format($row['deficit'], 2) }}</span>
                        @else
                            <span class="badge bg-success">{{ number_format($row['deficit'], 2) }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($row['aging'] > 3)
                            <span class="badge bg-danger">{{ $row['aging'] }}</span>
                        @elseif($row['aging'] > 0)
                            <span class="badge bg-warning text-dark">{{ $row['aging'] }}</span>
                        @else
                            <span class="badge bg-success">{{ $row['aging'] }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="text-center py-5">
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
