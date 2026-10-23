<style>
    .sidebar-footer {
        position: relative;
        bottom: 0;
        width: 100%;
        padding: 16px;
        /* Dark background */
        text-align: center;
    }

    .sidebar-footer a {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background: linear-gradient(135deg, #2e7dff, #9c27b0);
        /* Gradient */
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .sidebar-footer a:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
    }
</style>
@php
    $user_profile = auth()->user();
@endphp
<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse">
    <div class="sidebar-inner px-4 pt-3">
        <div class="user-card d-none">
            <div class="d-flex align-items-center">
                <span class="dashboard-sidebar-avatar me-3 {{ $user_profile->is_prime_member ? 'prime-profile-image-frame' : '' }}" role="img" aria-label="{{ $user_profile->name }}">
                    @if ($user_profile->image)
                        <img src="{{ asset('images/' . $user_profile->image) }}"
                            alt="Profile photo of {{ $user_profile->name }}"
                            onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                        <span aria-hidden="true" hidden>{{ strtoupper(substr($user_profile->name ?? '?', 0, 1)) }}</span>
                    @else
                        <span aria-hidden="true">{{ strtoupper(substr($user_profile->name ?? '?', 0, 1)) }}</span>
                    @endif
                </span>

                <div class="d-block">
                    <h2 class="h5 mb-3">Hi, {{ auth()->user()->name }}
                        @if ($user_profile->is_prime_member)
                            <span class="prime-badge ms-1">Prime</span>
                        @endif
                    </h2>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-secondary btn-sm d-inline-flex align-items-center">
                        <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Sign Out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
        <div class="sidebar-mobile-controls d-flex d-lg-none justify-content-end">
            <button type="button" class="sidebar-mobile-close" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
                aria-label="Close navigation">
                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item">
                <a href="{{ route('frontend.index') }}" class="nav-link d-flex align-items-center sidebar-brand">
                    <span class="sidebar-brand-mark">
                        <img src="{{ asset('assets/images/freebazar-sidebar-logo.png') }}" alt="" aria-hidden="true">
                    </span>
                    <span class="sidebar-text">FREEBAZAR</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('user') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="nav-link">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">{{ $user_profile->is_prime_member ? 'Prime Dashboard' : 'Dashboard' }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.pos.list.index') }}"
                    class="nav-link {{ request()->routeIs('user.pos.list.index') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd"
                                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">POS LIST</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.order') }}"
                    class="nav-link {{ request()->routeIs('user.order*') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3h14v2H3zM3 8h14v2H3zM3 13h9v2H3z"></path>
                            <path d="M14 13h3v3h-3z"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">My Order</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.offers.index') }}"
                    class="nav-link {{ request()->routeIs('user.offers.*') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M3 8h14v10H3zM2 5h16v3H2zM10 5v13M10 5C7 5 5 4 5 2.8S7.5 1.5 9 3.2L10 5zm0 0c3 0 5-1 5-2.2S12.5 1.5 11 3.2L10 5z">
                            </path>
                        </svg>
                    </span>
                    <span class="sidebar-text">Offers</span>
                </a>
            </li>

            <li class="nav-item">
                <span class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('user.add', 'user.sponsor.list') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" data-bs-target="#submenu-user-management"
                    aria-controls="submenu-user-management"
                    aria-expanded="{{ request()->routeIs('user.add', 'user.sponsor.list') ? 'true' : 'false' }}">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">User Management</span>
                    </span>
                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>
                <div class="multi-level collapse {{ request()->routeIs('user.add', 'user.sponsor.list') ? 'show' : '' }}"
                    role="list" id="submenu-user-management"
                    aria-expanded="{{ request()->routeIs('user.add', 'user.sponsor.list') ? 'true' : 'false' }}">
                    <ul class="flex-column nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.add') ? 'active' : '' }}"
                                href="{{ route('user.add') }}">
                                <span class="sidebar-text">Add User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.sponsor.list') ? 'active' : '' }}"
                                href="{{ route('user.sponsor.list') }}">
                                <span class="sidebar-text">My Referrals</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.wallet') }}"
                    class="nav-link {{ request()->routeIs('user.wallet') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd"
                                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">My Payback</span>
                </a>
            </li>

            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

        </ul>




    </div>
    <footer class="sidebar-footer">
        <a href="https://play.google.com/store/apps/details?id=com.dreamplug.androidapp">Install CRED</a>
    </footer>
</nav>
