<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('logo.png') }}" alt="QBASH" height="40" class="me-2" onerror="this.style.display='none';">
            <span>{{ config('app.name', 'QBASH') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    @if (Auth::user()->hasRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('members*') ? 'active' : '' }}"
                               href="{{ route('members.index') }}">
                                Members
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contributions*') ? 'active' : '' }}"
                               href="{{ route('contributions.index') }}">
                                Contributions
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}"
                               href="{{ route('expenses.index') }}">
                                Expenses
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('member.contributions*') ? 'active' : '' }}"
                               href="{{ route('member.contributions') }}">
                                My Contributions
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                           href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                           href="{{ route('register') }}">
                            Register
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Profile
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                Logout
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
