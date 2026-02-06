@extends('layouts.app')

@section('title', 'Update Phone Numbers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3" style="border-bottom: 3px solid #FFD700;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-telephone-fill me-2" style="color: #FFD700;"></i>Update Phone Numbers
                        </h4>
                        <a href="{{ route('members.index') }}" class="btn btn-sm" style="background: #FFD700; color: #000000; border: none;">
                            <i class="bi bi-arrow-left me-1"></i>Back to Members
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('update_errors'))
                        <div class="alert alert-warning">
                            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Update Errors:</h6>
                            <ul class="mb-0 small" style="max-height: 300px; overflow-y: auto;">
                                @foreach(session('update_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3" style="color: #000000;">
                            <i class="bi bi-info-circle me-2" style="color: #FFD700;"></i>Instructions:
                        </h5>
                        <ol class="mb-3">
                            <li>Download the template below</li>
                            <li>Fill in the member numbers and their new phone numbers</li>
                            <li>Upload the completed file</li>
                        </ol>
                        <div class="alert alert-info border-0" style="background: rgba(212, 175, 55, 0.1);">
                            <p class="mb-2"><strong>Required columns:</strong></p>
                            <ul class="mb-0 small">
                                <li><strong>member_no</strong> - The member number (e.g., M001)</li>
                                <li><strong>phone</strong> - The new phone number (e.g., 254712345678)</li>
                            </ul>
                            <p class="mb-0 mt-2 small">
                                <strong>Note:</strong> Only members that exist in the system will be updated. Members not found will be listed in the errors.
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('members.phone-template') }}" class="btn" style="background: #FFD700; color: #000000; border: none;">
                            <i class="bi bi-download me-1"></i>Download Template
                        </a>
                    </div>

                    <form action="{{ route('members.update-phones') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="file" class="form-label fw-semibold" style="color: #000000;">
                                <i class="bi bi-file-earmark-spreadsheet me-2" style="color: #FFD700;"></i>Select Excel/CSV File
                            </label>
                            <input type="file" 
                                   class="form-control @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file" 
                                   accept=".xlsx,.xls,.csv,.txt" 
                                   required
                                   style="border: 2px solid #FFD700;">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Accepted formats: .csv, .xlsx, .xls (Max: 10MB)
                            </small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn" style="background: #000000; color: #FFFFFF; border: 2px solid #FFD700;">
                                <i class="bi bi-upload me-1"></i>Update Phone Numbers
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
