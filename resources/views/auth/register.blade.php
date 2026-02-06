@extends('layouts.guest')

@section('content')
<div class="container-fluid px-0 register-screen" style="min-height: 100vh; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%); position: relative; overflow: hidden;">
    <!-- Soft background blobs (same style as login) -->
    <div class="position-absolute w-100 h-100" style="z-index: 0;">
        <div class="position-absolute" style="top: -10%; right: -5%; width: 500px; height: 500px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(80px);"></div>
        <div class="position-absolute" style="bottom: -10%; left: -5%; width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(80px);"></div>
    </div>

    <div class="container py-2 position-relative d-flex align-items-center justify-content-center" style="z-index: 1; min-height: 100vh;">
        <div class="row justify-content-center w-100">
            <div class="col-lg-11 col-xl-10 col-xxl-9 mx-auto">
                <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.98);">
                    <div class="row g-0">
                        <!-- Left Side - Pool Table & QBASH Branding (matches login style) -->
                        <div class="col-lg-5 d-none d-lg-flex position-relative" style="background: linear-gradient(135deg, #1d1d1d 0%, #111 100%); min-height: 500px; overflow: hidden;">
                            <!-- Pool table background image (local asset) -->
                            <div class="position-absolute w-100 h-100" style="
                                background-image: url('{{ asset('images/pool-table-bg.jpg') }}');
                                background-size: cover;
                                background-position: center;
                                opacity: 0.28;
                                mix-blend-mode: lighten;
                            "></div>

                            <div class="position-relative w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-4" style="z-index: 2;">
                                <!-- QBASH Logo -->
                                <div class="text-center mb-3">
                                    <div class="mb-3">
                                        @if(file_exists(public_path('logo.svg')))
                                            <img src="{{ asset('logo.svg') }}" alt="QBASH Logo" style="width: 160px; height: 160px; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.6)); animation: float 3s ease-in-out infinite;">
                                        @else
                                            <!-- Fallback CSS logo if SVG doesn't exist -->
                                            <div class="d-inline-flex flex-column align-items-center">
                                                <div class="position-relative d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 130px; height: 130px; border: 3px solid #FFD700; border-radius: 50%; background: transparent;">
                                                    <div class="position-absolute d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 110px; height: 110px; border: 4px solid #FFD700; border-radius: 50%; background: transparent;">
                                                        <div class="position-absolute d-inline-flex align-items-center justify-content-center rounded-circle" 
                                                             style="width: 75px; height: 75px; background: #2a2a2a; box-shadow: 0 8px 25px rgba(0,0,0,0.5), inset 0 -3px 10px rgba(0,0,0,0.3); animation: float 3s ease-in-out infinite;">
                                                            <div class="fw-bold" style="font-size: 1.9rem; color: #FFD700; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                                                                8
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="position-relative mt-3">
                                                    <div class="text-uppercase fw-bold mb-1" style="font-size: 0.95rem; letter-spacing: 2px; color: #FFFFFF; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                                        QBASH POOL CLUB
                                                    </div>
                                                    <div class="text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 1.5px; color: #FFFFFF; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                                        ON TARGET
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pool table icon instead of generic images -->
                                <div class="mt-2 text-center w-100 px-4">
                                    <h3 class="fw-bold mb-2" style="font-size: 1.2rem; color: #FFD700;">Join the Table</h3>
                                    <p class="mb-2" style="opacity: 0.95; line-height: 1.4; font-size: 0.85rem;">
                                        Manage games, standings, and contributions in one place.
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <div class="rounded-circle p-3" style="background: rgba(255, 215, 0, 0.15); border: 2px solid #FFD700;">
                                            <!-- Pool Table Icon -->
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#FFD700" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <!-- Table outline -->
                                                <rect x="3" y="6" width="18" height="12" rx="1" fill="none" stroke="#FFD700"/>
                                                <!-- Pockets -->
                                                <circle cx="3" cy="6" r="1.5" fill="#FFD700"/>
                                                <circle cx="21" cy="6" r="1.5" fill="#FFD700"/>
                                                <circle cx="3" cy="18" r="1.5" fill="#FFD700"/>
                                                <circle cx="21" cy="18" r="1.5" fill="#FFD700"/>
                                                <!-- Center line -->
                                                <line x1="12" y1="6" x2="12" y2="18" stroke="#FFD700" stroke-width="0.8"/>
                                                <!-- Pool balls -->
                                                <circle cx="8" cy="12" r="1.2" fill="#FFD700"/>
                                                <circle cx="16" cy="12" r="1.2" fill="#FFD700"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Decorative Pool Balls (keep consistent with login) -->
                            <div class="position-absolute" style="top: 8%; right: 8%; width: 50px; height: 50px; background: #2a2a2a; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.5), inset -3px -3px 6px rgba(0,0,0,0.3); border: 2px solid #FFD700; animation: float 4s ease-in-out infinite;">
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="fw-bold" style="color: #FFD700; font-size: 1.2rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">8</span>
                                </div>
                            </div>
                            <div class="position-absolute" style="bottom: 12%; left: 8%; width: 45px; height: 45px; background: #FFD700; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.4), inset -3px -3px 6px rgba(0,0,0,0.2); border: 2px solid #2a2a2a; animation: float 3.5s ease-in-out infinite;">
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="fw-bold" style="color: #2a2a2a; font-size: 1rem; text-shadow: 1px 1px 2px rgba(255,255,255,0.3);">1</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Registration Form (styled like login) -->
                        <div class="col-lg-7">
                                <div class="p-3 p-md-3 p-lg-3">
                                <!-- QBASH Logo for Mobile/Tablet -->
                                <div class="text-center mb-2 d-lg-none">
                                    @if(file_exists(public_path('logo.svg')))
                                        <img src="{{ asset('logo.svg') }}" alt="QBASH Logo" style="width: 120px; height: 120px; margin-bottom: 10px;">
                                    @else
                                        <div class="d-inline-flex flex-column align-items-center mb-3">
                                            <div class="position-relative d-inline-flex align-items-center justify-content-center mb-2" 
                                                 style="width: 100px; height: 100px; border: 2px solid #FFD700; border-radius: 50%; background: transparent;">
                                                <div class="position-absolute d-inline-flex align-items-center justify-content-center rounded-circle" 
                                                     style="width: 70px; height: 70px; background: #2a2a2a; border: 2px solid #FFD700;">
                                                    <div class="fw-bold" style="font-size: 1.5rem; color: #FFD700;">8</div>
                                                </div>
                                            </div>
                                            <div class="text-uppercase fw-bold mb-1" style="font-size: 0.9rem; letter-spacing: 1.5px; color: #000000;">
                                                QBASH POOL CLUB
                                            </div>
                                            <div class="text-uppercase fw-semibold small" style="letter-spacing: 1px; color: #666;">
                                                ON TARGET
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center mb-2">
                                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill auth-badge" style="--accent: #FFD700;">
                                        <i class="bi bi-stars"></i>
                                        <span>Join QBASH Today</span>
                                    </div>
                                    <h2 class="fw-bold mb-2 auth-title">
                                        <!-- Cue stick icon before title -->
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFD700" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: -3px;">
                                            <line x1="3" y1="21" x2="19" y2="5" />
                                            <circle cx="19" cy="5" r="2" fill="#FFD700" />
                                        </svg>
                                        Create Account
                                    </h2>
                                    <p class="text-muted mb-0">Create your account in seconds</p>
                                </div>

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

                                <form method="POST" action="{{ route('register') }}" class="mt-2">
        @csrf

                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label for="name" class="form-label fw-semibold mb-1" style="color: #000000; font-size: 0.88rem;">
                                                <i class="bi bi-person me-2" style="color: #FFD700;"></i>Full Name
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #FFD700; border-right: none !important; padding: 8px 12px;">
                                                    <i class="bi bi-person" style="color: #FFD700;"></i>
                                                </span>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                                       class="form-control border-start-0 border-end-0" required autofocus autocomplete="name"
                                                       placeholder="Enter your full name"
                                                       style="border: 2px solid #FFD700; padding: 8px 12px; font-size: 0.92rem;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="initials" class="form-label fw-semibold mb-1" style="color: #000000; font-size: 0.88rem;">
                                                <i class="bi bi-type me-2" style="color: #FFD700;"></i>Initials
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #FFD700; border-right: none !important; padding: 8px 12px;">
                                                    <i class="bi bi-type" style="color: #FFD700;"></i>
                                                </span>
                                                <input id="initials" type="text" name="initials" value="{{ old('initials') }}"
                                                       class="form-control border-start-0 border-end-0 text-uppercase" required 
                                                       maxlength="10" placeholder="JD"
                                                       style="text-transform: uppercase; border: 2px solid #FFD700; padding: 8px 12px; font-size: 0.92rem;">
                                            </div>
                                            <small class="text-muted">e.g., JD</small>
                                        </div>
        </div>

        <div class="row g-2 mb-2">
                                        <div class="col-md-7">
                                            <label for="email" class="form-label fw-semibold mb-1" style="color: #000000; font-size: 0.88rem;">
                                                <i class="bi bi-envelope me-2" style="color: #FFD700;"></i>Email Address
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #FFD700; border-right: none !important; padding: 8px 12px;">
                                                    <i class="bi bi-envelope-at" style="color: #FFD700;"></i>
                                                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                                       class="form-control border-start-0 border-end-0" required autocomplete="username"
                                                       placeholder="Enter your email"
                                                       style="border: 2px solid #FFD700; padding: 8px 12px; font-size: 0.92rem;">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="phone" class="form-label fw-semibold mb-1" style="color: #000000; font-size: 0.88rem;">
                                                <i class="bi bi-telephone me-2" style="color: #FFD700;"></i>Phone <small class="text-muted">(Optional)</small>
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #FFD700; border-right: none !important; padding: 8px 12px;">
                                                    <i class="bi bi-phone" style="color: #FFD700;"></i>
                                                </span>
                                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                                       class="form-control border-start-0 border-end-0" autocomplete="tel"
                                                       placeholder="Phone number"
                                                       style="border: 2px solid #FFD700; padding: 8px 12px; font-size: 0.92rem;">
                                            </div>
                                        </div>
        </div>

        <div class="row g-2 mb-2">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label fw-semibold mb-1" style="color: #000000; font-size: 0.88rem;">
                                                <i class="bi bi-lock me-2" style="color: #FFD700;"></i>Password
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #FFD700; border-right: none !important; padding: 8px 12px;">
                                                    <i class="bi bi-key" style="color: #FFD700;"></i>
                                                </span>
                                                <input id="password" type="password" name="password"
                                                       class="form-control border-start-0 border-end-0" required autocomplete="new-password"
                                                       placeholder="Create a password"
                                                       style="border: 2px solid #FFD700; padding: 8px 12px; font-size: 0.92rem;">
                                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePassword" style="border: 2px solid #FFD700; border-left: none !important; color: #FFD700; padding: 8px 12px;">
                                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label fw-semibold mb-1" style="color: #000000; font-size: 0.88rem;">
                                                <i class="bi bi-lock-fill me-2" style="color: #FFD700;"></i>Confirm Password
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #FFD700; border-right: none !important; padding: 8px 12px;">
                                                    <i class="bi bi-key-fill" style="color: #FFD700;"></i>
                                                </span>
                <input id="password_confirmation" type="password" name="password_confirmation"
                                                       class="form-control border-start-0 border-end-0" required autocomplete="new-password"
                                                       placeholder="Confirm your password"
                                                       style="border: 2px solid #FFD700; padding: 8px 12px; font-size: 0.92rem;">
                                                <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePasswordConfirmation" style="border: 2px solid #FFD700; border-left: none !important; color: #FFD700; padding: 8px 12px;">
                                                    <i class="bi bi-eye" id="eyeIconConfirmation"></i>
                                                </button>
                                            </div>
                                        </div>
        </div>

                                    <div class="d-grid mb-2">
                                        <button type="submit" class="btn btn-primary fw-semibold shadow-lg btn-auth-primary" style="--accent: #FFD700; --accent-dark: #c5a030;">
                                            <i class="bi bi-person-plus me-2"></i>Create Account
                                        </button>
        </div>

                                    <div class="text-center pt-2 border-top small">
                                        <p class="text-muted mb-0">
                                            Already have an account?
                                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #FFD700;">
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

    .auth-title {
        color: #000000;
        font-size: 1.45rem;
        letter-spacing: 0.2px;
    }

    .auth-badge {
        background: rgba(212, 175, 55, 0.12);
        color: #6b5a00;
        font-weight: 600;
        font-size: 0.75rem;
        border: 1px solid rgba(212, 175, 55, 0.5);
    }

    .btn-auth-primary {
        background: var(--accent);
        color: #000000;
        border-radius: 10px;
        padding: 10px;
        font-size: 0.9rem;
        border: 2px solid var(--accent) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .register-screen .card {
        max-height: 90vh;
    }

    .register-screen .card .row.g-0 > [class*="col-"] {
        min-height: 100%;
    }

    .btn-auth-primary:hover,
    .btn-auth-primary:focus {
        background: var(--accent-dark);
        color: #000000;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(212, 175, 55, 0.35);
    }

    .btn-auth-primary:active {
        transform: translateY(0);
        box-shadow: 0 6px 18px rgba(212, 175, 55, 0.25);
    }

    #initials {
        text-transform: uppercase;
    }

    .form-control:focus {
        border-color: #FFD700 !important;
        box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25) !important;
    }

    .input-group-text {
        transition: all 0.3s ease;
    }

    .input-group:focus-within .input-group-text {
        border-color: #FFD700 !important;
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
