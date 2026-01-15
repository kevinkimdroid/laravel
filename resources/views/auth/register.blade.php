@extends('layouts.guest')

@section('content')
<div class="container-fluid px-0" style="min-height: 100vh; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%); position: relative; overflow: hidden;">
    <!-- Soft background blobs (same style as login) -->
    <div class="position-absolute w-100 h-100" style="z-index: 0;">
        <div class="position-absolute" style="top: -10%; right: -5%; width: 500px; height: 500px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(80px);"></div>
        <div class="position-absolute" style="bottom: -10%; left: -5%; width: 400px; height: 400px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(80px);"></div>
    </div>

    <div class="container py-5 position-relative d-flex align-items-center justify-content-center" style="z-index: 1; min-height: 100vh;">
        <div class="row justify-content-center w-100">
            <div class="col-lg-11 col-xl-10 col-xxl-9 mx-auto">
                <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255,255,255,0.98);">
                    <div class="row g-0">
                        <!-- Left Side - Pool Table & QBASH Branding (matches login style) -->
                        <div class="col-lg-6 d-none d-lg-flex position-relative" style="background: linear-gradient(135deg, #1d1d1d 0%, #111 100%); min-height: 620px; overflow: hidden;">
                            <!-- Pool table background image (local asset) -->
                            <div class="position-absolute w-100 h-100" style="
                                background-image: url('{{ asset('images/pool-table-bg.jpg') }}');
                                background-size: cover;
                                background-position: center;
                                opacity: 0.28;
                                mix-blend-mode: lighten;
                            "></div>

                            <div class="position-relative w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5" style="z-index: 2;">
                                <!-- QBASH Logo -->
                                <div class="text-center mb-4">
                                    <div class="mb-4">
                                        @if(file_exists(public_path('logo.svg')))
                                            <img src="{{ asset('logo.svg') }}" alt="QBASH Logo" style="width: 220px; height: 220px; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.6)); animation: float 3s ease-in-out infinite;">
                                        @else
                                            <!-- Fallback CSS logo if SVG doesn't exist -->
                                            <div class="d-inline-flex flex-column align-items-center">
                                                <div class="position-relative d-inline-flex align-items-center justify-content-center" 
                                                     style="width: 180px; height: 180px; border: 3px solid #FFD700; border-radius: 50%; background: transparent;">
                                                    <div class="position-absolute d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 140px; height: 140px; border: 4px solid #FFD700; border-radius: 50%; background: transparent;">
                                                        <div class="position-absolute d-inline-flex align-items-center justify-content-center rounded-circle" 
                                                             style="width: 100px; height: 100px; background: #2a2a2a; box-shadow: 0 8px 25px rgba(0,0,0,0.5), inset 0 -3px 10px rgba(0,0,0,0.3); animation: float 3s ease-in-out infinite;">
                                                            <div class="fw-bold" style="font-size: 2.5rem; color: #FFD700; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                                                                8
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="position-relative mt-3">
                                                    <div class="text-uppercase fw-bold mb-1" style="font-size: 1.1rem; letter-spacing: 2px; color: #FFFFFF; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                                        QBASH POOL CLUB
                                                    </div>
                                                    <div class="text-uppercase fw-semibold" style="font-size: 0.9rem; letter-spacing: 1.5px; color: #FFFFFF; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                                                        ON TARGET
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pool table icon instead of generic images -->
                                <div class="mt-2 text-center w-100 px-4">
                                    <h3 class="fw-bold mb-3" style="font-size: 1.6rem; color: #FFD700;">Join the Table</h3>
                                    <p class="mb-4" style="opacity: 0.95; line-height: 1.6; font-size: 1rem;">
                                        Register to manage your games, standings and contributions in one place.
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
                            <div class="position-absolute" style="top: 8%; right: 8%; width: 50px; height: 50px; background: #2a2a2a; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.5), inset -3px -3px 6px rgba(0,0,0,0.3); border: 2px solid #D4AF37; animation: float 4s ease-in-out infinite;">
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="fw-bold" style="color: #D4AF37; font-size: 1.2rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">8</span>
                                </div>
                            </div>
                            <div class="position-absolute" style="bottom: 12%; left: 8%; width: 45px; height: 45px; background: #D4AF37; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.4), inset -3px -3px 6px rgba(0,0,0,0.2); border: 2px solid #2a2a2a; animation: float 3.5s ease-in-out infinite;">
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <span class="fw-bold" style="color: #2a2a2a; font-size: 1rem; text-shadow: 1px 1px 2px rgba(255,255,255,0.3);">1</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Registration Form (styled like login) -->
                        <div class="col-lg-6">
                            <div class="p-5 p-md-5 p-lg-5">
                                <!-- QBASH Logo for Mobile/Tablet -->
                                <div class="text-center mb-4 d-lg-none">
                                    @if(file_exists(public_path('logo.svg')))
                                        <img src="{{ asset('logo.svg') }}" alt="QBASH Logo" style="width: 150px; height: 150px; margin-bottom: 15px;">
                                    @else
                                        <div class="d-inline-flex flex-column align-items-center mb-3">
                                            <div class="position-relative d-inline-flex align-items-center justify-content-center mb-2" 
                                                 style="width: 120px; height: 120px; border: 2px solid #FFD700; border-radius: 50%; background: transparent;">
                                                <div class="position-absolute d-inline-flex align-items-center justify-content-center rounded-circle" 
                                                     style="width: 80px; height: 80px; background: #2a2a2a; border: 2px solid #FFD700;">
                                                    <div class="fw-bold" style="font-size: 1.8rem; color: #FFD700;">8</div>
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

                                <div class="text-center mb-5">
                                    <h2 class="fw-bold mb-3" style="color: #000000; font-size: 2rem;">
                                        <!-- Cue stick icon before title -->
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: -3px;">
                                            <line x1="3" y1="21" x2="19" y2="5" />
                                            <circle cx="19" cy="5" r="2" fill="#D4AF37" />
                                        </svg>
                                        Create Account
                                    </h2>
                                    <p class="text-muted mb-0">Fill in your details to get started</p>
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

                                <form method="POST" action="{{ route('register') }}" class="mt-4">
        @csrf

                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="name" class="form-label fw-semibold mb-2" style="color: #000000; font-size: 0.95rem;">
                                                <i class="bi bi-person me-2" style="color: #D4AF37;"></i>Full Name
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 10px 14px;">
                                                    <i class="bi bi-person" style="color: #D4AF37;"></i>
                                                </span>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                                       class="form-control border-start-0 border-end-0" required autofocus autocomplete="name"
                                                       placeholder="Enter your full name"
                                                       style="border: 2px solid #D4AF37; padding: 10px 14px; font-size: 0.95rem;">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="initials" class="form-label fw-semibold mb-2" style="color: #000000; font-size: 0.95rem;">
                                                <i class="bi bi-type me-2" style="color: #D4AF37;"></i>Initials
                                            </label>
                                            <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                                <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 10px 14px;">
                                                    <i class="bi bi-type" style="color: #D4AF37;"></i>
                                                </span>
                                                <input id="initials" type="text" name="initials" value="{{ old('initials') }}"
                                                       class="form-control border-start-0 border-end-0 text-uppercase" required 
                                                       maxlength="10" placeholder="JD"
                                                       style="text-transform: uppercase; border: 2px solid #D4AF37; padding: 10px 14px; font-size: 0.95rem;">
                                            </div>
                                            <small class="text-muted">e.g., JD for John Doe</small>
                                        </div>
        </div>

        <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold mb-2" style="color: #000000; font-size: 0.95rem;">
                                            <i class="bi bi-envelope me-2" style="color: #D4AF37;"></i>Email Address
                                        </label>
                                        <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                            <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 10px 14px;">
                                                <i class="bi bi-envelope-at" style="color: #D4AF37;"></i>
                                            </span>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                                   class="form-control border-start-0 border-end-0" required autocomplete="username"
                                                   placeholder="Enter your email address"
                                                   style="border: 2px solid #D4AF37; padding: 10px 14px; font-size: 0.95rem;">
                                        </div>
        </div>

        <div class="mb-3">
                                        <label for="phone" class="form-label fw-semibold mb-2" style="color: #000000; font-size: 0.95rem;">
                                            <i class="bi bi-telephone me-2" style="color: #D4AF37;"></i>Phone Number <small class="text-muted">(Optional)</small>
                                        </label>
                                        <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                            <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 10px 14px;">
                                                <i class="bi bi-phone" style="color: #D4AF37;"></i>
                                            </span>
                                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                                   class="form-control border-start-0 border-end-0" autocomplete="tel"
                                                   placeholder="Enter your phone number"
                                                   style="border: 2px solid #D4AF37; padding: 10px 14px; font-size: 0.95rem;">
                                        </div>
        </div>

        <div class="mb-3">
                                        <label for="password" class="form-label fw-semibold mb-2" style="color: #000000; font-size: 0.95rem;">
                                            <i class="bi bi-lock me-2" style="color: #D4AF37;"></i>Password
                                        </label>
                                        <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                            <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 10px 14px;">
                                                <i class="bi bi-key" style="color: #D4AF37;"></i>
                                            </span>
                                            <input id="password" type="password" name="password"
                                                   class="form-control border-start-0 border-end-0" required autocomplete="new-password"
                                                   placeholder="Create a password"
                                                   style="border: 2px solid #D4AF37; padding: 10px 14px; font-size: 0.95rem;">
                                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePassword" style="border: 2px solid #D4AF37; border-left: none !important; color: #D4AF37; padding: 10px 14px;">
                                                <i class="bi bi-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label fw-semibold mb-2" style="color: #000000; font-size: 0.95rem;">
                                            <i class="bi bi-lock-fill me-2" style="color: #D4AF37;"></i>Confirm Password
                                        </label>
                                        <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                            <span class="input-group-text bg-white border-end-0" style="border: 2px solid #D4AF37; border-right: none !important; padding: 10px 14px;">
                                                <i class="bi bi-key-fill" style="color: #D4AF37;"></i>
                                            </span>
            <input id="password_confirmation" type="password" name="password_confirmation"
                                                   class="form-control border-start-0 border-end-0" required autocomplete="new-password"
                                                   placeholder="Confirm your password"
                                                   style="border: 2px solid #D4AF37; padding: 10px 14px; font-size: 0.95rem;">
                                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePasswordConfirmation" style="border: 2px solid #D4AF37; border-left: none !important; color: #D4AF37; padding: 10px 14px;">
                                                <i class="bi bi-eye" id="eyeIconConfirmation"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-grid mb-4">
                                        <button type="submit" class="btn btn-primary fw-semibold shadow-lg border-0" 
                                                style="background: #D4AF37; color: #000000; border-radius: 12px; padding: 14px; font-size: 1.05rem; transition: all 0.3s ease; transform: translateY(0); border: 2px solid #D4AF37 !important;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(212, 175, 55, 0.4)'; this.style.background='#c5a030'; this.style.color='#000000';"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(212, 175, 55, 0.3)'; this.style.background='#D4AF37'; this.style.color='#000000';">
                                            <i class="bi bi-person-plus me-2"></i>Create Account
                                        </button>
        </div>

                                    <div class="text-center pt-3 border-top">
                                        <p class="text-muted mb-0">
                                            Already have an account?
                                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #D4AF37;">
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

    #initials {
        text-transform: uppercase;
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
