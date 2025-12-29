@extends('layouts.app')

@section('title', 'Financial Record Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Financial Record Details</h3>
    <a href="{{ route('financial-records.edit', $record) }}" class="btn btn-primary btn-sm">Edit</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">{{ $record->name }}</h5>
        <p class="mb-1"><strong>Initials:</strong> {{ $record->initials }}</p>
        <p class="mb-1"><strong>Registration:</strong> {{ $record->registration }}</p>
        <p class="mb-1"><strong>Expected Amount:</strong> {{ number_format($record->expected_amount, 2) }}</p>
        <p class="mb-1"><strong>Deficit:</strong> {{ number_format($record->deficit, 2) }}</p>
        <p class="mb-1"><strong>Aging:</strong> {{ $record->aging }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Monthly Amounts
    </div>
    <div class="card-body p-0">
        <table class="table mb-0 table-sm">
            <thead>
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
                <tr>
                    <td>{{ number_format($record->jan, 2) }}</td>
                    <td>{{ number_format($record->feb, 2) }}</td>
                    <td>{{ number_format($record->mar, 2) }}</td>
                    <td>{{ number_format($record->apr, 2) }}</td>
                    <td>{{ number_format($record->may, 2) }}</td>
                    <td>{{ number_format($record->jun, 2) }}</td>
                    <td>{{ number_format($record->jul, 2) }}</td>
                    <td>{{ number_format($record->aug, 2) }}</td>
                    <td>{{ number_format($record->sep, 2) }}</td>
                    <td>{{ number_format($record->oct, 2) }}</td>
                    <td>{{ number_format($record->nov, 2) }}</td>
                    <td>{{ number_format($record->dec, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('financial-records.index') }}" class="btn btn-secondary btn-sm">Back to list</a>
</div>
@endsection


