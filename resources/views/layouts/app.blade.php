<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'QBASH'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Optional: your compiled assets (if you still use them) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --qbash-black: #0f0f0f;
            --qbash-gold: #FFD700;
            --qbash-white: #ffffff;
            --qbash-gray-50: #f6f6f6;
            --qbash-gray-200: #e6e6e6;
            --qbash-gray-600: #6b6b6b;
            --sidebar-width: 240px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--qbash-gray-50);
            font-size: 0.95rem;
        }

        /* Override Bootstrap primary colors */
        .btn-primary {
            background-color: var(--qbash-black) !important;
            border-color: var(--qbash-gold) !important;
            color: var(--qbash-white) !important;
        }
        .btn-primary:hover {
            background-color: var(--qbash-gold) !important;
            border-color: var(--qbash-gold) !important;
            color: var(--qbash-black) !important;
        }

        .bg-primary {
            background-color: var(--qbash-black) !important;
        }

        .text-primary {
            color: var(--qbash-gold) !important;
        }

        .border-primary {
            border-color: var(--qbash-gold) !important;
        }

        .badge.bg-primary {
            background-color: var(--qbash-black) !important;
            color: var(--qbash-white) !important;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--qbash-black);
            color: var(--qbash-white);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 1030;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--qbash-white);
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-section {
            padding: 1rem 0.75rem;
        }

        .sidebar-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.55);
            padding: 0 0.75rem 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(212, 175, 55, 0.18);
            color: var(--qbash-gold);
        }

        .sidebar-link.active {
            background: var(--qbash-gold);
            color: var(--qbash-black);
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .app-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .mobile-topbar {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: var(--qbash-white);
            border-bottom: 1px solid var(--qbash-gray-200);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1020;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.2s ease;
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .app-content {
                margin-left: 0;
            }

            .mobile-topbar {
                display: flex;
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }
        }

        .card {
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08) !important;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }

        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            border: none;
            font-weight: 600;
        }

        h1, .h1 { font-size: 1.55rem; }
        h2, .h2 { font-size: 1.35rem; }
        h3, .h3 { font-size: 1.2rem; }
        h4, .h4 { font-size: 1.05rem; }
        h5, .h5 { font-size: 0.95rem; }

        .lead { font-size: 0.98rem; }
        .nav-link, .dropdown-item { font-size: 0.9rem; }
        .badge { font-size: 0.72rem; }
        .btn { font-size: 0.9rem; }
        .form-label { font-size: 0.9rem; }
        .form-control, .form-select { font-size: 0.9rem; }
        .table td, .table th { font-size: 0.9rem; }

        .alert-success,
        .alert-danger {
            background: rgba(255, 215, 0, 0.12);
            color: var(--qbash-black);
            border: 1px solid rgba(255, 215, 0, 0.4);
        }

        .alert-success .btn-close,
        .alert-danger .btn-close {
            filter: brightness(0.4);
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <div class="app-shell">
        @include('layouts.navigation')

        <div class="app-content">
            <div class="mobile-topbar">
                <button class="btn btn-sm btn-outline-secondary" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i> Menu
                </button>
                <span class="fw-semibold">{{ config('app.name', 'QBASH') }}</span>
            </div>

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
        </div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Bootstrap JS (for navbar, alerts, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggle) {
                toggle.addEventListener('click', () => {
                    document.body.classList.toggle('sidebar-open');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    document.body.classList.remove('sidebar-open');
                });
            }
        });
    </script>
</body>
</html>
