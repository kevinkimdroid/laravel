@extends('layouts.app')

@section('title', 'Import Contributions')

@section('content')
<h3 class="mb-3">Import Contributions from CSV</h3>

<p class="text-muted">
    Export your Excel file as CSV (comma separated values) with columns:
    <strong>member_no, amount, contribution_date (YYYY-MM-DD)</strong>.
</p>

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
    <div class="alert alert-info">
        Imported {{ session('import_result.added') }} rows,
        skipped {{ session('import_result.skipped') }} rows
        (missing member or invalid data).
    </div>
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


