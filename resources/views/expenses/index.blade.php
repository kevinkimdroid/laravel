@extends('layouts.app')

@section('title', 'Financial Overview')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Financial Overview</h3>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
        Record Expense
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Contributions</h6>
                <h4 class="card-text text-success">
                    {{ number_format($totalIncome, 2) }}
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Expenses</h6>
                <h4 class="card-text text-danger">
                    {{ number_format($totalExpense, 2) }}
                </h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted">Net Balance</h6>
                <h4 class="card-text {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($net, 2) }}
                </h4>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Accounts
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ number_format($account->balance, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No accounts yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


