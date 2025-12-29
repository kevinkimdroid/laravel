@extends('layouts.app')

@section('title', 'Record Expense')

@section('content')
<h3 class="mb-3">Record Expense</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('expenses.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Account</label>
        <select name="account_id" class="form-select" required>
            @foreach ($accounts as $account)
                <option value="{{ $account->id }}">
                    {{ $account->name }} (Balance: {{ number_format($account->balance, 2) }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>

    <button type="submit" class="btn btn-success">
        Save Expense
    </button>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
        Cancel
    </a>
</form>
@endsection


