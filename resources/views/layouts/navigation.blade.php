<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 60px; position: sticky; top: 0; z-index: 1030;">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center fw-bold text-white" href="{{ route('dashboard') }}" style="font-size: 1.5rem;">
            <span>{{ config('app.name', 'QBASH') }}</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" 
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation" style="border-color: rgba(255,255,255,0.5) !important;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 py-2" href="{{ route('dashboard') }}" style="font-weight: 500; display: block;">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>

                    @if(auth()->user() && auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2" href="{{ route('members.index') }}" style="font-weight: 500; display: block;">
                                <i class="bi bi-people me-1"></i>Members
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2" href="{{ route('contributions.index') }}" style="font-weight: 500; display: block;">
                                <i class="bi bi-cash-stack me-1"></i>Contributions
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2" href="{{ route('expenses.index') }}" style="font-weight: 500; display: block;">
                                <i class="bi bi-receipt me-1"></i>Expenses
                            </a>
                        </li>
                    @elseif(auth()->user())
                        <li class="nav-item">
                            <a class="nav-link text-white px-3 py-2" href="{{ route('member.contributions') }}" style="font-weight: 500; display: block;">
                                <i class="bi bi-cash-stack me-1"></i>My Contributions
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center px-3 py-2" href="#" id="userDropdown" 
                           role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 500; display: block;">
                            <i class="bi bi-person-circle me-2" style="font-size: 1.2rem;"></i>
                            <span>{{ auth()->user()->name }}</span>
                            @if(auth()->user()->role === 'admin')
                                <span class="badge bg-warning text-dark ms-2">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm px-3" style="white-space: nowrap;">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 py-2" href="{{ route('login') }}" style="font-weight: 500; display: block;">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 py-2" href="{{ route('register') }}" style="font-weight: 500; display: block;">
                            Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
