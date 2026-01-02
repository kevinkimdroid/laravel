@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-75">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="row g-0">
                    <!-- Left Side - Image/Design -->
                    <div class="col-md-6 d-none d-md-flex position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 500px;">
                        <div class="position-absolute w-100 h-100" style="background-image: url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center; opacity: 0.2;"></div>
                        <div class="position-relative w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5" style="z-index: 2;">
                            <div class="text-center mb-4">
                                <i class="bi bi-shield-lock-fill" style="font-size: 4rem; opacity: 0.9;"></i>
                            </div>
                            <h2 class="fw-bold mb-3 text-center" style="font-size: 2.2rem;">Welcome Back!</h2>
                            <p class="text-center fs-5 mb-4" style="opacity: 0.95;">
                                Secure member and contribution management portal
                            </p>
                            <div class="d-flex justify-content-center gap-4 mt-3">
                                <div class="text-center">
                                    <i class="bi bi-people-fill d-block mb-2" style="font-size: 1.8rem;"></i>
                                    <small>Members</small>
                                </div>
                                <div class="text-center">
                                    <i class="bi bi-cash-stack d-block mb-2" style="font-size: 1.8rem;"></i>
                                    <small>Contributions</small>
                                </div>
                                <div class="text-center">
                                    <i class="bi bi-graph-up d-block mb-2" style="font-size: 1.8rem;"></i>
                                    <small>Analytics</small>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative elements -->
                        <div class="position-absolute" style="top: 10%; right: 10%; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div class="position-absolute" style="bottom: 15%; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="col-md-6">
                        <div class="p-4 p-md-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-primary mb-2">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                </h3>
                                <p class="text-muted mb-0">Enter your credentials to access your account</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>{{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

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

                            <form method="POST" action="{{ route('login') }}" class="mt-4">
                                @csrf

                                <div class="mb-3">
                                    <label for="login" class="form-label fw-semibold">
                                        <i class="bi bi-person me-2 text-primary"></i>Email or Phone Number
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope-at text-muted"></i>
                                        </span>
                                        <input id="login" type="text" name="login" value="{{ old('login') }}"
                                               class="form-control border-start-0" required autofocus autocomplete="username"
                                               placeholder="Enter your email or phone number">
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
                                               class="form-control border-start-0" required autocomplete="current-password"
                                               placeholder="Enter your password">
                                        <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                        <label class="form-check-label" for="remember_me">Remember me</label>
                                    </div>

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small">
                                            <i class="bi bi-question-circle me-1"></i>Forgot password?
                                        </a>
                                    @endif
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm" 
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                    </button>
                                </div>

                                @if (Route::has('register'))
                                    <div class="text-center pt-3 border-top">
                                        <p class="text-muted mb-0">
                                            Don't have an account?
                                            <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                                                <i class="bi bi-person-plus me-1"></i>Register Now
                                            </a>
                                        </p>
                                    </div>
                                @endif
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
</style>

<script>
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
</script>
@endsection
