
@extends('layouts.app')

@section('title', 'Contributions Overview')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Contributions Overview ({{ $year }})</h3>
    <div>
        <a href="{{ route('contributions.import.form') }}" class="btn btn-outline-secondary me-2">
            Import CSV
        </a>
        <a href="{{ route('contributions.create') }}" class="btn btn-primary">
            Post Contribution
        </a>
    </div>
</div>

<p class="text-muted small mb-3">
    Showing contributions per member, broken down by month, with deficit and aging based on an expected 250 per month.
</p>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm align-middle">
        <thead class="table-light text-center">
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Member</th>
                <th rowspan="2">Initials</th>
                <th colspan="12">Monthly Contributions ({{ $year }})</th>
                <th rowspan="2">Deficit</th>
                <th rowspan="2">Aging (months)</th>
            </tr>
            <tr>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>May</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Aug</th>
                <th>Sep</th>
                <th>Oct</th>
                <th>Nov</th>
                <th>Dec</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['member']->name }}</td>
                    <td class="text-center">{{ $row['initials'] }}</td>
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
                    <td class="text-end text-danger">{{ number_format($row['deficit'], 2) }}</td>
                    <td class="text-center">{{ $row['aging'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="17" class="text-center">
                        No members or contributions found for {{ $year }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
