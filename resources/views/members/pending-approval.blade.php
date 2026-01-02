@extends('layouts.app')

@section('title', 'Application Pending Approval')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-lg">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 5rem;"></i>
                </div>
                
                <h2 class="fw-bold text-primary mb-3">Application Pending Approval</h2>
                
                <p class="text-muted fs-5 mb-4">
                    Your membership application has been successfully submitted and is currently under review.
                </p>
                
                <div class="alert alert-info shadow-sm mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle me-3 mt-1" style="font-size: 1.5rem;"></i>
                        <div class="text-start">
                            <h5 class="alert-heading mb-2">What happens next?</h5>
                            <ul class="mb-0 ps-3">
                                <li>Your application is being reviewed by the administration team</li>
                                <li>You will be notified once your membership is approved</li>
                                <li>Once approved, you'll have full access to member features</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-house me-2"></i>Go to Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-person me-2"></i>Update Profile
                    </a>
                </div>
                
                <div class="mt-4 pt-4 border-top">
                    <p class="text-muted small mb-0">
                        <i class="bi bi-envelope me-1"></i>
                        Need assistance? Contact the administration team for support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

