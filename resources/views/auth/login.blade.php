@extends('layouts.guest')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%); position: relative; overflow: hidden;">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100" style="z-index: 0;">
        <div class="position-absolute" style="top: -10%; right: -5%; width: 500px; height: 500px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(80px);"></div>
        <div class="position-absolute" style="bottom: -10%; left: -5%; width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(80px);"></div>
    </div>

    <div class="container py-5 position-relative" style="z-index: 1;">
        <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="col-lg-10 col-xl-8">
                <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.98);">
                    <div class="row g-0">
                        <!-- Left Side - Pool Image & QBASH Branding -->
                        <div class="col-lg-6 d-none d-lg-flex position-relative" style="background: #000000; min-height: 550px; overflow: hidden;">
                            <!-- Pool Image Background -->
                            <div class="position-absolute w-100 h-100" style="background-image: url('https://images.unsplash.com/photo-1571068316344-75bc76f77890?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center; opacity: 0.15;"></div>
                            
                            <div class="position-relative w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5" style="z-index: 2;">
                                <!-- QBASH Logo - Matching the actual logo design -->
                                <div class="text-center mb-4">
                                    <div class="mb-4">
                                        <!-- Logo with black 8-ball, yellow rings, and white text -->
                                        <div class="d-inline-flex flex-column align-items-center">
                                            <!-- Outer yellow circle -->
                                            <div class="position-relative d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 180px; height: 180px; border: 3px solid #FFD700; border-radius: 50%; background: transparent;">
                                                <!-- Middle yellow ring -->
                                                <div class="position-absolute d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 140px; height: 140px; border: 4px solid #FFD700; border-radius: 50%; background: transparent;">
                                                    <!-- Inner black 8-ball -->
                                                    <div class="position-absolute d-inline-flex align-items-center justify-content-center rounded-circle" 
                                                         style="width: 100px; height: 100px; background: #000000; box-shadow: 0 8px 25px rgba(0,0,0,0.5), inset 0 -3px 10px rgba(0,0,0,0.3); animation: float 3s ease-in-out infinite;">
                                                        <div class="fw-bold" style="font-size: 2.5rem; color: #D4AF37; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                                                            8
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Text around the circle -->
                                            <div class="position-relative mt-3">
                                                <div class="text-uppercase fw-bold mb-1" style="font-size: 1.1rem; letter-spacing: 2px; color: #FFFFFF; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                                    QBASH POOL CLUB
                                                </div>
                                                <div class="text-uppercase fw-semibold" style="font-size: 0.9rem; letter-spacing: 1.5px; color: #FFFFFF; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                                    ON TARGET
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-center w-100 px-4">
                                    <h3 class="fw-bold mb-3" style="font-size: 1.6rem; color: #D4AF37;">Welcome Back!</h3>
                                    <p class="mb-4" style="opacity: 0.95; line-height: 1.6; font-size: 1rem;">
                                        Secure member and contribution management portal
                                    </p>
                                </div>
                                
                                <div class="d-flex justify-content-center gap-5 mt-4 w-100">
                                    <div class="text-center">
                                        <div class="rounded-circle p-3 mb-2 d-inline-block" style="background: rgba(212, 175, 55, 0.15); border: 2px solid #D4AF37;">
                                            <i class="bi bi-people-fill" style="font-size: 1.8rem; color: #D4AF37;"></i>
                                        </div>
                                        <small class="d-block fw-semibold" style="font-size: 0.85rem;">Members</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="rounded-circle p-3 mb-2 d-inline-block" style="background: rgba(212, 175, 55, 0.15); border: 2px solid #D4AF37;">
                                            <i class="bi bi-cash-stack" style="font-size: 1.8rem; color: #D4AF37;"></i>
                                        </div>
                                        <small class="d-block fw-semibold" style="font-size: 0.85rem;">Contributions</small>
                                    </div>
                                    <div class="text-center">
                                        <div class="rounded-circle p-3 mb-2 d-inline-block" style="background: rgba(212, 175, 55, 0.15); border: 2px solid #D4AF37;">
                                            <i class="bi bi-trophy" style="font-size: 1.8rem; color: #D4AF37;"></i>
                                        </div>
                                        <small class="d-block fw-semibold" style="font-size: 0.85rem;">League</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Decorative Pool Balls -->
                            <div class="position-absolute" style="top: 8%; right: 8%; width: 50px; height: 50px; background: #000000; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.5), inset -3px -3px 6px rgba(0,0,0,0.3); border: 2px solid #D4AF37; animation: float 4s ease-in-out infinite;">
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="fw-bold" style="color: #D4AF37; font-size: 1.2rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">8</span>
                                </div>
                            </div>
                            <div class="position-absolute" style="bottom: 12%; left: 8%; width: 45px; height: 45px; background: #D4AF37; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.4), inset -3px -3px 6px rgba(0,0,0,0.2); border: 2px solid #000000; animation: float 3.5s ease-in-out infinite;">
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="fw-bold text-black" style="font-size: 1rem; text-shadow: 1px 1px 2px rgba(255,255,255,0.3);">1</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Login Form -->
                        <div class="col-lg-6">
                            <div class="p-5 p-md-5 p-lg-5">
                                <!-- QBASH Logo for Mobile/Tablet -->
                                <div class="text-center mb-4 d-lg-none">
                                    <div class="d-inline-flex flex-column align-items-center mb-3">
                                        <div class="position-relative d-inline-flex align-items-center justify-content-center mb-2" 
                                             style="width: 120px; height: 120px; border: 2px solid #D4AF37; border-radius: 50%; background: transparent;">
                                            <div class="position-absolute d-inline-flex align-items-center justify-content-center rounded-circle" 
                                                 style="width: 80px; height: 80px; background: #000000; border: 2px solid #D4AF37;">
                                                <div class="fw-bold" style="font-size: 1.8rem; color: #D4AF37;">8</div>
                                            </div>
                                        </div>
                                        <div class="text-uppercase fw-bold mb-1" style="font-size: 0.9rem; letter-spacing: 1.5px; color: #000000;">
                                            QBASH POOL CLUB
                                        </div>
                                        <div class="text-uppercase fw-semibold small" style="letter-spacing: 1px; color: #666;">
                                            ON TARGET
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mb-5">
                                    <h2 class="fw-bold mb-3" style="color: #000000; font-size: 2rem;">
                                        <i class="bi bi-box-arrow-in-right me-2" style="color: #D4AF37;"></i>Sign In
                                    </h2>
                                    <p class="text-muted mb-0">Enter your credentials to access your account</p>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-info alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
                                        <i class="bi bi-info-circle me-2"></i>{{ session('status') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
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

                                    <div class="mb-4">
                                        <label for="login" class="form-label fw-semibold mb-3" style="color: #000000; font-size: 0.95rem;">
                                            <i class="bi bi-person-fill me-2" style="color: #D4AF37;"></i>Email or Phone Number
                                        </label>
                                        <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                            <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 12px 15px;">
                                                <i class="bi bi-envelope-at" style="color: #D4AF37; font-size: 1.1rem;"></i>
                                            </span>
                                            <input id="login" type="text" name="login" value="{{ old('login') }}"
                                                   class="form-control border-start-0 border-end-0" required autofocus autocomplete="username"
                                                   placeholder="Enter your email or phone"
                                                   style="border: 2px solid #D4AF37; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease;">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-semibold mb-3" style="color: #000000; font-size: 0.95rem;">
                                            <i class="bi bi-lock-fill me-2" style="color: #D4AF37;"></i>Password
                                        </label>
                                        <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                            <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 12px 15px;">
                                                <i class="bi bi-key-fill" style="color: #D4AF37; font-size: 1.1rem;"></i>
                                            </span>
                                            <input id="password" type="password" name="password"
                                                   class="form-control border-start-0 border-end-0" required autocomplete="current-password"
                                                   placeholder="Enter your password"
                                                   style="border: 2px solid #D4AF37; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease;">
                                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePassword" style="border: 2px solid #D4AF37; border-left: none !important; color: #D4AF37; padding: 12px 15px;">
                                                <i class="bi bi-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4 d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="border: 2px solid #D4AF37; cursor: pointer;">
                                            <label class="form-check-label" for="remember_me" style="color: #000000; cursor: pointer; font-size: 0.9rem;">Remember me</label>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-decoration-none fw-semibold" style="color: #D4AF37; font-size: 0.9rem;">
                                                <i class="bi bi-question-circle me-1"></i>Forgot password?
                                            </a>
                                        @endif
                                    </div>

                                    <div class="d-grid mb-4">
                                        <button type="submit" class="btn btn-primary fw-semibold shadow-lg border-0" 
                                                style="background: #000000; color: #FFFFFF; border-radius: 12px; padding: 14px; font-size: 1.1rem; transition: all 0.3s ease; transform: translateY(0); border: 2px solid #D4AF37 !important;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.4)'; this.style.background='#D4AF37'; this.style.color='#000000';"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.3)'; this.style.background='#000000'; this.style.color='#FFFFFF';">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                        </button>
                                    </div>

                                    @if (Route::has('register'))
                                        <div class="text-center pt-4 border-top">
                                            <p class="text-muted mb-0">
                                                Don't have an account?
                                                <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: #D4AF37;">
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
</div>

<style>
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    .min-vh-100 {
        min-height: 100vh;
    }
    
    .form-control:focus {
        border-color: #D4AF37 !important;
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
    }
    
    .input-group-text {
        transition: all 0.3s ease;
    }
    
    .input-group:focus-within .input-group-text {
        border-color: #D4AF37 !important;
        background-color: #faf8f0 !important;
    }
    
    @media (max-width: 991px) {
        .min-vh-100 {
            min-height: auto;
            padding: 1.5rem 0;
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
    
    // Add focus effects to inputs
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform 0.2s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
</script>
@endsection
