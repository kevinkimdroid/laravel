@extends('layouts.guest')

@section('content')
    <div class="row g-0">
        <div class="col-md-5 d-none d-md-block">
            <div class="h-100 w-100 rounded-start"
                 style="background-image: url('https://images.pexels.com/photos/4968391/pexels-photo-4968391.jpeg?auto=compress&cs=tinysrgb&w=800');
                        background-size: cover;
                        background-position: center;">
                <div class="h-100 w-100 d-flex flex-column justify-content-end p-3"
                     style="background: linear-gradient(180deg, rgba(0,0,0,0.2), rgba(0,0,0,0.7));">
                    <h5 class="text-white mb-1">{{ config('app.name', 'Laravel') }}</h5>
                    <p class="text-white-50 small mb-0">Secure member and contribution management portal.</p>
                </div>
            </div>
        </div>

        <div class="col-md-7 p-4">
            <h4 class="mb-3 text-center">Welcome back</h4>
            <p class="text-muted text-center mb-4 small">Sign in to access your dashboard.</p>

            @if (session('status'))
                <div class="alert alert-info">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-lg" required autofocus autocomplete="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password"
                           class="form-control form-control-lg" required autocomplete="current-password">
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label small" for="remember_me">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="small" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Log in
                    </button>
                </div>

                @if (Route::has('register'))
                    <div class="mt-2 text-center">
                        <span class="small">Don't have an account?</span>
                        <a href="{{ route('register') }}" class="small">Register</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
