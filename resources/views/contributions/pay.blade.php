@extends('layouts.app')

@section('title', 'Pay Contribution')

@section('content')
<h3 class="mb-3">Pay Contribution</h3>

<div class="mb-3">
    <strong>Member:</strong> {{ $member->name }} ({{ $member->member_no }})
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('member.contributions.pay') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Amount (leave empty for default 250)</label>
        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="contribution_date" class="form-control"
               value="{{ old('contribution_date', date('Y-m-d')) }}" required>
    </div>

    <button type="submit" class="btn btn-success">
        Record Payment
    </button>
    <a href="{{ route('member.contributions') }}" class="btn btn-secondary">
        Cancel
    </a>
</form>
@endsection


