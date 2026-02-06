<aside class="sidebar" id="sidebar">
    <a class="sidebar-brand" href="{{ route('dashboard') }}">
        @if(file_exists(public_path('logo.svg')))
            <img src="{{ asset('logo.svg') }}" alt="QBASH Logo" style="height: 32px; width: auto;">
        @endif
        <span>{{ config('app.name', 'QBASH') }}</span>
    </a>

    @auth
        <div class="sidebar-section">
            @if(auth()->user() && auth()->user()->role === 'admin')
                <div class="sidebar-title">Admin</div>
                <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>Admin Dashboard
                </a>
                <a class="sidebar-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
                    <i class="bi bi-people"></i>Members
                </a>
                <a class="sidebar-link {{ request()->routeIs('contributions.*') ? 'active' : '' }}" href="{{ route('contributions.index') }}">
                    <i class="bi bi-cash-stack"></i>Contributions
                </a>
                <a class="sidebar-link {{ request()->routeIs('calendar-activities.*') ? 'active' : '' }}" href="{{ route('calendar-activities.index') }}">
                    <i class="bi bi-calendar-event"></i>Calendar
                </a>
                <a class="sidebar-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                    <i class="bi bi-receipt"></i>Expenses
                </a>
            @elseif(auth()->user())
                <div class="sidebar-title">Member</div>
                <a class="sidebar-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}" href="{{ route('member.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>My Dashboard
                </a>
                <a class="sidebar-link {{ request()->routeIs('member.contributions') ? 'active' : '' }}" href="{{ route('member.contributions') }}">
                    <i class="bi bi-cash-stack"></i>My Contributions
                </a>
                <a class="sidebar-link {{ request()->routeIs('member.calendar') ? 'active' : '' }}" href="{{ route('member.calendar') }}">
                    <i class="bi bi-calendar-event"></i>Calendar
                </a>
                <a class="sidebar-link {{ request()->routeIs('member.qpl.*') || request()->routeIs('qpl-games.standings') ? 'active' : '' }}" href="{{ route('member.qpl.standings') }}">
                    <i class="bi bi-trophy"></i>QPL Standings
                </a>
            @else
                <div class="sidebar-title">Menu</div>
                <a class="sidebar-link" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>Dashboard
                </a>
            @endif
        </div>
    @endauth

    <div class="sidebar-footer">
        @auth
            <div class="small text-uppercase" style="color: rgba(255, 255, 255, 0.6); letter-spacing: 0.08em;">Account</div>
            <div class="d-flex align-items-center gap-2 mt-2">
                <i class="bi bi-person-circle"></i>
                <div class="small">{{ auth()->user()->name }}</div>
            </div>
            <a class="sidebar-link mt-3" href="{{ route('profile.edit') }}">
                <i class="bi bi-person"></i>Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-right"></i>Logout
                </button>
            </form>
        @else
            <a class="sidebar-link" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i>Login
            </a>
            <a class="sidebar-link mt-2" href="{{ route('register') }}">
                <i class="bi bi-person-plus"></i>Register
            </a>
        @endauth
    </div>
</aside>
