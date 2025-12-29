@extends('layouts.app')

@section('title', 'Import Contributions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Import Contributions from CSV</h3>
    <a href="{{ route('contributions.template') }}" class="btn btn-outline-primary">
        📥 Download CSV Template
    </a>
</div>

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
                    <td>Text (must exist in system)</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td><code>member_name</code></td>
                    <td>Member full name</td>
                    <td>Text (e.g., John Doe)</td>
                    <td>No (will update member if provided)</td>
                </tr>
                <tr>
                    <td><code>initials</code></td>
                    <td>Member initials</td>
                    <td>Text (e.g., JD, SM)</td>
                    <td>No (will update member if provided)</td>
                </tr>
                <tr>
                    <td><code>year</code></td>
                    <td>Year for contributions</td>
                    <td>Number (e.g., 2024)</td>
                    <td>Yes</td>
                </tr>
                <tr>
                    <td><code>jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec</code></td>
                    <td>Monthly contribution amounts</td>
                    <td>Number (e.g., 250.00) or leave empty</td>
                    <td>No (at least one month should have a value)</td>
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
            The first row should contain the header. Leave monthly columns empty if no contribution for that month.
            The <code>member_name</code>, <code>initials</code>, and <code>registration_fee</code> columns are optional and will update the member's data if provided.
        </p>
        <div class="alert alert-info mt-3 mb-0">
            <strong>💡 Tip:</strong> Click "Download CSV Template" above to get a ready-to-fill template with example data. 
            Simply replace the example rows with your actual contribution data.
        </div>
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
        <small class="form-text text-muted">Upload your CSV file with monthly contributions. The year is included in the CSV file itself.</small>
        <div class="alert alert-warning mt-2 mb-0 small">
            <strong>⚠️ Excel Users:</strong> When saving from Excel, make sure to:
            <ul class="mb-0 mt-1">
                <li>Save as "CSV (Comma delimited) (*.csv)" - NOT "CSV UTF-8" or "CSV (Macintosh)"</li>
                <li>Or use "Save As" → Choose "CSV (Comma delimited) (*.csv)"</li>
                <li>Ensure the first row contains column headers exactly as shown in the template</li>
            </ul>
        </div>
    </div>

    <button type="submit" class="btn btn-success">
        Import
    </button>
    <a href="{{ route('contributions.index') }}" class="btn btn-secondary">
        Cancel
    </a>
</form>
@endsection


