@extends('layouts.app')

@section('title', 'Import Members')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Import Members from Excel</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if(session('import_failures'))
                        <div class="alert alert-warning">
                            <strong>Import completed with some errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach(session('import_failures') as $failure)
                                    <li>Row {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Instructions:</h5>
                        <ol>
                            <li>Download the Excel template below</li>
                            <li>Fill in the member information</li>
                            <li>Upload the completed file</li>
                        </ol>
                        <p class="text-muted small">
                            <strong>Required columns:</strong> member_no, name, initials, registration_amount_paid, paid_to_date<br>
                            <strong>Optional columns:</strong> registration_fee (default: 1000), phone, status (ACTIVE/INACTIVE, default: ACTIVE)<br>
                            <strong>Note:</strong> Please use CSV format. If you have an Excel file, save it as CSV first.
                        </p>
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('members.template') }}" class="btn btn-outline-primary">
                            <i class="bi bi-download"></i> Download Template
                        </a>
                    </div>

                    <form action="{{ route('members.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">Select Excel File</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Accepted format: .csv (Max: 10MB). For Excel files, please save as CSV first.
                            </small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Import Members</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

