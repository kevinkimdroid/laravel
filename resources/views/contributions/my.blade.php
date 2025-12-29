@extends('layouts.app')

@section('title', 'My Contributions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>My Contributions</h3>
    <a href="{{ route('member.contributions.pay.form') }}" class="btn btn-primary btn-sm">
        Pay Contribution
    </a>
</div>

<div class="mb-3">
    <strong>Member:</strong> {{ $member->name }} ({{ $member->member_no }})<br>
    <strong>Total Paid:</strong> {{ number_format($total, 2) }}
</div>

<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @forelse($contributions as $contribution)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $contribution->contribution_date->format('Y-m-d') }}</td>
                <td>{{ number_format($contribution->amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No contributions found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection


