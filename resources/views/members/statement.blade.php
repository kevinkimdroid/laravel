@extends('layouts.app')

@section('title', 'Member Statement')

@section('content')
<h3>Member Statement</h3>

<div class="card mb-3">
    <div class="card-body">
        <strong>Member No:</strong> {{ $member->member_no }} <br>
        <strong>Name:</strong> {{ $member->name }} <br>
        <strong>Status:</strong> {{ $member->status }}
    </div>
</div>

<table class="table table-bordered">
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
                <td>{{ $contribution->contribution_date }}</td>
                <td>{{ number_format($contribution->amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No contributions found</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="table-secondary">
        <tr>
            <th colspan="2">Total</th>
            <th>{{ number_format($total, 2) }}</th>
        </tr>
    </tfoot>
</table>

<a href="{{ route('members.index') }}" class="btn btn-secondary">
    Back to Members
</a>
@endsection
