<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'QBASH'))</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Optional: your compiled assets (if you still use them) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .navbar {
            position: sticky !important;
            top: 0 !important;
            z-index: 1030 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
        }
        .navbar-nav {
            display: flex !important;
            flex-direction: row !important;
        }
        .nav-link {
            color: white !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease !important;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-radius: 5px !important;
            color: white !important;
        }
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5) !important;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }
        .navbar-nav {
            display: flex !important;
            flex-direction: row !important;
        }
        .nav-item {
            display: flex !important;
            align-items: center !important;
        }
        .nav-link {
            display: block !important;
            color: white !important;
        }
        .collapse.navbar-collapse {
            display: flex !important;
        }
        @media (max-width: 991px) {
            .collapse.navbar-collapse {
                display: none !important;
            }
            .collapse.navbar-collapse.show {
                display: block !important;
            }
            .navbar-nav {
                flex-direction: column !important;
            }
        }
        .card {
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead th {
            border: none;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    @include('layouts.navigation')

    @isset($header)
        <header class="bg-white border-bottom shadow-sm">
            <div class="container py-3">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="flex-grow-1 py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Support both component slots (<x-app-layout>) and @section("content") --}}
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-top text-center py-3 mt-auto small text-muted">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </footer>

    <!-- Bootstrap JS (for navbar, alerts, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    
    <script>
        // Ensure navbar is visible
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('mainNavbar');
            if (navbar) {
                navbar.style.display = 'flex';
            }
            
            // Force show nav items
            const navItems = document.querySelectorAll('.navbar-nav .nav-item');
            navItems.forEach(item => {
                item.style.display = 'flex';
                item.style.visibility = 'visible';
            });
            
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            navLinks.forEach(link => {
                link.style.display = 'block';
                link.style.visibility = 'visible';
                link.style.color = 'white';
            });
        });
    </script>
</body>
</html>
