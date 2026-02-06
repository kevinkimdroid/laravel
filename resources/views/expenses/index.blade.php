@extends('layouts.app')

@section('title', 'Financial Overview')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
    <div>
        <h3 class="mb-1">Profit & Loss</h3>
        <p class="text-muted mb-0">Summary of income, expenses, budgets, and net position.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
            Record Expense
        </a>
        <a href="{{ route('expenses.export', request()->only(['opening_balance', 'expense_budget'])) }}" class="btn btn-outline-dark btn-sm" style="border-color: #FFD700;">
            Download Excel
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3" style="border-radius: 16px;">
    <div class="card-body">
        <form method="GET" action="{{ route('expenses.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="opening_balance" class="form-label fw-semibold mb-1">Opening Balance (KES)</label>
                <input type="number" step="0.01" id="opening_balance" name="opening_balance" class="form-control"
                       value="{{ old('opening_balance', $openingBalance ?? 0) }}" placeholder="0.00">
            </div>
            <div class="col-md-4">
                <label for="expense_budget" class="form-label fw-semibold mb-1">Expense Budget (KES)</label>
                <input type="number" step="0.01" id="expense_budget" name="expense_budget" class="form-control"
                       value="{{ old('expense_budget', $expenseBudget ?? 0) }}" placeholder="0.00">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-sm w-100" style="background: #FFD700; color: #000000;">
                    Apply
                </button>
            </div>
        </form>
        <div class="small text-muted mt-2">Tip: set opening balance and budget before exporting.</div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-0">
        <table class="table table-borderless mb-0 align-middle">
            <thead style="background: rgba(255, 215, 0, 0.12);">
                <tr>
                    <th class="px-4 py-3">Profit & Loss Statement</th>
                    <th class="px-4 py-3 text-end">Amount (KES)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-3 text-muted text-uppercase small">Opening Balance</td>
                    <td class="px-4 py-3 text-end fw-semibold">{{ number_format($openingBalance ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-3 text-muted text-uppercase small">Income</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="px-4">Contributions (Operating)</td>
                    <td class="px-4 text-end text-success fw-semibold">{{ number_format($monthlyContributions ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-3 text-muted text-uppercase small">Expenses</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="px-4">Total Expenses</td>
                    <td class="px-4 text-end text-danger fw-semibold">{{ number_format($totalExpense, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4">Expense Budget</td>
                    <td class="px-4 text-end fw-semibold">{{ number_format($expenseBudget ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4">Overuse Amount</td>
                    <td class="px-4 text-end fw-semibold {{ ($overuseAmount ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($overuseAmount ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="px-4">Overuse %</td>
                    <td class="px-4 text-end fw-semibold">
                        {{ $overusePercent === null ? 'N/A' : $overusePercent . '%' }}
                    </td>
                </tr>
                <tr style="border-top: 1px solid rgba(0,0,0,0.08);">
                    <td class="px-4 py-3 fw-bold">Net Profit / (Loss)</td>
                    <td class="px-4 py-3 text-end fw-bold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($net, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="px-4">Capital Injection (Registration)</td>
                    <td class="px-4 text-end fw-semibold">{{ number_format($registrationInjection ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4">Expected Registration (42 × 1,000)</td>
                    <td class="px-4 text-end fw-semibold">{{ number_format($expectedRegistration ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4">Registration Variance</td>
                    <td class="px-4 text-end fw-semibold {{ ($registrationVariance ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($registrationVariance ?? 0, 2) }}
                    </td>
                </tr>
                <tr style="border-top: 1px solid rgba(0,0,0,0.08);">
                    <td class="px-4 py-3 fw-bold">Net Cash Position</td>
                    <td class="px-4 py-3 text-end fw-bold {{ ($netCashPosition ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($netCashPosition ?? 0, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="px-4 pb-3 small text-muted" colspan="2">
                        Note: Registration injection is shown separately and not included in net cash position.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 py-3">
        <h6 class="mb-0 fw-semibold">Accounts Snapshot</h6>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="text-end">{{ number_format($account->balance, 2) }}</td>
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


