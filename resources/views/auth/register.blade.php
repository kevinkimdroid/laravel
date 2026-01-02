@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <!-- Left Side - Image/Design -->
                    <div class="col-md-6 d-none d-md-flex position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 600px;">
                        <div class="position-absolute w-100 h-100" style="background-image: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center; opacity: 0.2;"></div>
                        <div class="position-relative w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5" style="z-index: 2;">
                            <div class="text-center mb-4">
                                <i class="bi bi-person-plus-fill" style="font-size: 4rem; opacity: 0.9;"></i>
                            </div>
                            <h2 class="fw-bold mb-3 text-center" style="font-size: 2.2rem;">Join Us Today!</h2>
                            <p class="text-center fs-5 mb-4" style="opacity: 0.95;">
                                Create your account and become part of our community
                            </p>
                            <div class="d-flex justify-content-center gap-4 mt-3">
                                <div class="text-center">
                                    <i class="bi bi-shield-check d-block mb-2" style="font-size: 1.8rem;"></i>
                                    <small>Secure</small>
                                </div>
                                <div class="text-center">
                                    <i class="bi bi-lightning-charge d-block mb-2" style="font-size: 1.8rem;"></i>
                                    <small>Fast</small>
                                </div>
                                <div class="text-center">
                                    <i class="bi bi-people d-block mb-2" style="font-size: 1.8rem;"></i>
                                    <small>Community</small>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative elements -->
                        <div class="position-absolute" style="top: 10%; right: 10%; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div class="position-absolute" style="bottom: 15%; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    </div>

                    <!-- Right Side - Registration Form -->
                    <div class="col-md-6">
                        <div class="p-4 p-md-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-primary mb-2">
                                    <i class="bi bi-person-plus me-2"></i>Create Account
                                </h3>
                                <p class="text-muted mb-0">Fill in your details to get started</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Error:</strong>
                                    <ul class="mb-0 mt-2 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}" class="mt-4">
                                @csrf

                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-person me-2 text-primary"></i>Full Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person text-muted"></i>
                                            </span>
                                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                                   class="form-control border-start-0" required autofocus autocomplete="name"
                                                   placeholder="Enter your full name">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="initials" class="form-label fw-semibold">
                                            <i class="bi bi-type me-2 text-primary"></i>Initials
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-type text-muted"></i>
                                            </span>
                                            <input id="initials" type="text" name="initials" value="{{ old('initials') }}"
                                                   class="form-control border-start-0 text-uppercase" required 
                                                   maxlength="10" placeholder="JD"
                                                   style="text-transform: uppercase;">
                                        </div>
                                        <small class="text-muted">e.g., JD for John Doe</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        <i class="bi bi-envelope me-2 text-primary"></i>Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope-at text-muted"></i>
                                        </span>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                                               class="form-control border-start-0" required autocomplete="username"
                                               placeholder="Enter your email address">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold">
                                        <i class="bi bi-telephone me-2 text-primary"></i>Phone Number <small class="text-muted">(Optional)</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-phone text-muted"></i>
                                        </span>
                                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                               class="form-control border-start-0" autocomplete="tel"
                                               placeholder="Enter your phone number">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        <i class="bi bi-lock me-2 text-primary"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-key text-muted"></i>
                                        </span>
                                        <input id="password" type="password" name="password"
                                               class="form-control border-start-0" required autocomplete="new-password"
                                               placeholder="Create a password">
                                        <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        <i class="bi bi-lock-fill me-2 text-primary"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-key-fill text-muted"></i>
                                        </span>
                                        <input id="password_confirmation" type="password" name="password_confirmation"
                                               class="form-control border-start-0" required autocomplete="new-password"
                                               placeholder="Confirm your password">
                                        <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePasswordConfirmation">
                                            <i class="bi bi-eye" id="eyeIconConfirmation"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm" 
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                        <i class="bi bi-person-plus me-2"></i>Create Account
                                    </button>
                                </div>

                                <div class="text-center pt-3 border-top">
                                    <p class="text-muted mb-0">
                                        Already have an account?
                                        <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .min-vh-75 {
        min-height: 75vh;
    }
    @media (max-width: 768px) {
        .min-vh-75 {
            min-height: auto;
        }
    }
    #initials {
        text-transform: uppercase;
    }
</style>

<script>
    // Auto-uppercase initials
    document.getElementById('initials')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });

    // Toggle password visibility
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (password.type === 'password') {
            password.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });

    // Toggle password confirmation visibility
    document.getElementById('togglePasswordConfirmation')?.addEventListener('click', function() {
        const passwordConfirmation = document.getElementById('password_confirmation');
        const eyeIconConfirmation = document.getElementById('eyeIconConfirmation');
        
        if (passwordConfirmation.type === 'password') {
            passwordConfirmation.type = 'text';
            eyeIconConfirmation.classList.remove('bi-eye');
            eyeIconConfirmation.classList.add('bi-eye-slash');
        } else {
            passwordConfirmation.type = 'password';
            eyeIconConfirmation.classList.remove('bi-eye-slash');
            eyeIconConfirmation.classList.add('bi-eye');
        }
    });
</script>
@endsection
