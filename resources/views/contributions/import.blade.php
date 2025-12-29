@extends('layouts.app')

@section('title', 'Import Contributions')

@section('content')
<h3 class="mb-3">Import Contributions from CSV</h3>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">CSV Format Requirements</h5>
        <p class="card-text">
            Export your Excel file as CSV (comma separated values) with the following columns:
        </p>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Column Name</th>
                    <th>Description</th>
                    <th>Format</th>
                    <th>Required</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>member_no</code></td>
                    <td>Member number</td>
                    <td>Text</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td><code>amount</code></td>
                    <td>Contribution amount</td>
                    <td>Number (e.g., 250.00)</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td><code>contribution_date</code></td>
                    <td>Date of contribution</td>
                    <td>YYYY-MM-DD (e.g., 2024-01-15)</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td><code>registration_fee</code></td>
                    <td>Registration fee for member</td>
                    <td>Number (e.g., 1000.00)</td>
                    <td>No (defaults to 1000 if not provided)</td>
                </tr>
            </tbody>
        </table>
        <p class="text-muted small mb-0">
            <strong>Note:</strong> Column order doesn't matter, but column names must match exactly (case-insensitive).
            The first row should contain the header. The <code>registration_fee</code> column is optional and will update the member's registration fee if provided.
        </p>
    </div>
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

@if (session('import_result'))
    @php
        $result = session('import_result');
        $added = $result['added'] ?? 0;
        $skipped = $result['skipped'] ?? 0;
        $errors = $result['errors'] ?? [];
    @endphp

    <div class="alert {{ $added > 0 ? 'alert-success' : 'alert-warning' }}">
        <strong>Import Complete:</strong>
        <ul class="mb-0">
            <li>Successfully imported: <strong>{{ $added }}</strong> contributions</li>
            <li>Skipped: <strong>{{ $skipped }}</strong> rows</li>
        </ul>
        <p class="mb-0 mt-2 small">
            <strong>Note:</strong> Monthly totals (Jan-Dec), deficit, and aging are automatically calculated from all contributions. 
            View the <a href="{{ route('contributions.index') }}">Contributions Overview</a> to see the updated values.
        </p>
    </div>

    @if (!empty($errors))
        <div class="alert alert-warning">
            <strong>Errors encountered (showing first {{ count($errors) }}):</strong>
            <ul class="mb-0 small">
                @foreach ($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif

<form method="POST" action="{{ route('contributions.import') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">CSV File</label>
        <input type="file" name="file" class="form-control" accept=".csv,text/csv" required>
    </div>

    <button type="submit" class="btn btn-success">
        Import
    </button>
    <a href="{{ route('contributions.index') }}" class="btn btn-secondary">
        Cancel
    </a>
</form>
@endsection


