{{-- new Side Bar  --}}

<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
        <div
            class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-4">
                    <img src="{{ asset('images/' . auth()->user()->image) ?? asset('assets/img/team/profile-picture-3.jpg') }}"
                        class="card-img-top rounded-circle border-white" alt="Bonnie Green" />
                </div>
                <div class="d-block">
                    <h2 class="h5 mb-3">Hi, {{ auth()->user()->name }}</h2>
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
        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item">
                <a href="{{ route('frontend.index') }}" class="nav-link d-flex align-items-center sidebar-brand">
                    <span class="sidebar-brand-mark">
                        <img src="{{ asset('assets/images/freebazar-sidebar-logo.png') }}" alt="" aria-hidden="true">
                    </span>
                    <span class="sidebar-text">FREEBAZAR</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
                <a href="{{ route('admin.index') }}" class="nav-link">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            @can('role_access')
                @php
                    $reportMenuOpen = request()->is('admin/dsr*')
                        || request()->is('admin/new-msr*')
                        || request()->is('admin/user/all/transaction');
                @endphp
                <li class="nav-item {{ $reportMenuOpen ? 'open' : '' }}">
                    <button type="button"
                        class="nav-link admin-report-sidebar-trigger {{ $reportMenuOpen ? 'active' : 'collapsed' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#admin-report-submenu"
                        aria-expanded="{{ $reportMenuOpen ? 'true' : 'false' }}" aria-controls="admin-report-submenu">
                        <span>
                            <span class="sidebar-icon">
                                <i class="fas fa-chart-bar" aria-hidden="true"></i>
                            </span>
                            <span class="sidebar-text">Report</span>
                        </span>
                        <span class="link-arrow">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </button>
                    <div class="multi-level collapse {{ $reportMenuOpen ? 'show' : '' }}" id="admin-report-submenu">
                        <ul class="flex-column nav">
                            <li class="nav-item">
                                <a href="{{ route('admin.user.all.transaction') }}"
                                    class="nav-link {{ request()->is('admin/user/all/transaction') ? 'active' : '' }}"
                                    {{ request()->is('admin/user/all/transaction') ? 'aria-current=page' : '' }}>
                                    <span class="sidebar-text">Today's Sales</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.dsr') }}"
                                    class="nav-link {{ request()->is('admin/dsr') || request()->is('admin/dsr/*') ? 'active' : '' }}">
                                    <span class="sidebar-text">DSR</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.new.msr') }}"
                                    class="nav-link {{ request()->is('admin/new-msr*') ? 'active' : '' }}">
                                    <span class="sidebar-text">MSR</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            @can('role_access')
                <li class="nav-item {{ request()->is('admin/wallet*') || request()->is('admin/msr*') ? 'open' : '' }}">
                    <span
                        class="nav-link {{ request()->is('admin/wallet*') || request()->is('admin/msr*') ? 'active' : '' }} {{ request()->is('admin/wallet*') || request()->is('admin/msr*') ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#submenu-app-payback"
                        aria-expanded="{{ request()->is('admin/wallet*') || request()->is('admin/msr*') ? 'true' : 'false' }}">
                        <span>
                            <span class="sidebar-icon">
                                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                    <path fill-rule="evenodd"
                                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span class="sidebar-text">Payback</span>
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
                    <div class="multi-level collapse {{ request()->is('admin/wallet*') || request()->is('admin/msr*') ? 'show' : '' }}"
                        id="submenu-app-payback">
                        <ul class="flex-column nav">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/wallet/upload') ? 'active' : '' }}"
                                    href="{{ route('admin.wallet.upload.page') }}">
                                    <span class="sidebar-text">Payback Upload</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/wallet') ? 'active' : '' }}"
                                    href="{{ route('admin.wallet') }}">
                                    <span class="sidebar-text">Payback Report</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/msr*') ? 'active' : '' }}"
                                    href="{{ route('admin.msr') }}">
                                    <span class="sidebar-text">Payback Run Data</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            @can('user_access')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->is('admin/users*') && !request()->is('admin/users/create') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Customer Management</span>
                    </a>
                </li>
            @endcan

            @can('role_access')
                <li class="nav-item">
                    <a href="{{ route('admin.pos_system.index') }}"
                        class="nav-link {{ request()->is('admin/pos*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">POS Management</span>
                    </a>
                </li>
            @endcan

            <li class="nav-item {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                <a href="{{ route('admin.notifications.index') }}"
                    class="nav-link {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <i class="fas fa-bell" aria-hidden="true"></i>
                    </span>
                    <span class="sidebar-text">Notification Management</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/whatsapp*') ? 'active' : '' }}">
                <a href="{{ route('admin.whatsapp.index') }}"
                    class="nav-link {{ request()->is('admin/whatsapp*') ? 'active' : '' }}">
                    <span class="sidebar-icon">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                    </span>
                    <span class="sidebar-text">WhatsApp Management</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.wallet.manage', ['user_id' => '']) }}"
                    class="nav-link {{ request()->is('admin/walletmanage*') ? 'active' : '' }}"
                    {{ request()->is('admin/walletmanage*') ? 'aria-current=page' : '' }}>
                    <span class="sidebar-icon">
                        <i class="fas fa-wallet" aria-hidden="true"></i>
                    </span>
                    <span class="sidebar-text">Wallet Management</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.offers.index') }}"
                    class="nav-link {{ request()->is('admin/offer*') ? 'active' : '' }}"
                    {{ request()->is('admin/offer*') ? 'aria-current=page' : '' }}>
                    <span class="sidebar-icon">
                        <i class="fas fa-tags" aria-hidden="true"></i>
                    </span>
                    <span class="sidebar-text">Offer Management</span>
                </a>
            </li>

            @php
                $accountMenuOpen = request()->is('admin/payment*')
                    || request()->is('admin/payment-summaries*')
                    || request()->is('admin/dsr-summary*')
                    || request()->is('admin/settlement*')
                    || request()->is('admin/account/ledger*');
            @endphp
            <li class="nav-item admin-account-sidebar-menu {{ $accountMenuOpen ? 'open' : '' }}">
                <span class="nav-link {{ $accountMenuOpen ? 'active' : 'collapsed' }} admin-account-sidebar-trigger d-flex justify-content-between align-items-center"
                    role="button" data-bs-toggle="collapse" data-bs-target="#admin-account-submenu"
                    aria-expanded="{{ $accountMenuOpen ? 'true' : 'false' }}" aria-controls="admin-account-submenu">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Account</span>
                    </span>

                    <span class="link-arrow">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                </span>

                <div class="multi-level collapse {{ $accountMenuOpen ? 'show' : '' }} admin-account-sidebar-submenu" id="admin-account-submenu">
                    <ul class="flex-column nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/payment/index') ? 'active' : '' }}" href="{{ route('admin.payment.index') }}">
                                <i class="fas fa-credit-card" aria-hidden="true"></i><span class="sidebar-text">Payment</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.dsr-summary.index') }}"
                                class="nav-link {{ request()->is('admin/dsr-summary*') ? 'active' : '' }}"
                                {{ request()->is('admin/dsr-summary*') ? 'aria-current=page' : '' }}>
                                <i class="fas fa-file-alt" aria-hidden="true"></i><span class="sidebar-text">DSR Summary</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/payment-summaries*') ? 'active' : '' }}"
                                href="{{ route('admin.payment-summaries.index') }}"
                                {{ request()->is('admin/payment-summaries*') ? 'aria-current=page' : '' }}>
                                <i class="fas fa-list-alt" aria-hidden="true"></i><span class="sidebar-text">Settelment Summary</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/settlement*') ? 'active' : '' }}" href="{{ route('admin.settlement.index') }}">
                                <i class="fas fa-receipt" aria-hidden="true"></i><span class="sidebar-text">Receipt Journal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/account/ledger*') ? 'active' : '' }}" href="{{ route('admin.ledger.index') }}">
                                <i class="fas fa-book-open" aria-hidden="true"></i><span class="sidebar-text">Account Ledger</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @canany(['permission_access', 'role_access', 'permission_create'])
                @php
                    $systemUserMenuOpen = request()->is('admin/permissions*')
                        || request()->is('admin/roles*')
                        || request()->is('admin/users/create');
                @endphp
                <li class="nav-item {{ $systemUserMenuOpen ? 'open' : '' }}">
                    <button type="button"
                        class="nav-link admin-wallet-sidebar-trigger {{ $systemUserMenuOpen ? 'active' : 'collapsed' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#admin-system-user-submenu"
                        aria-expanded="{{ $systemUserMenuOpen ? 'true' : 'false' }}"
                        aria-controls="admin-system-user-submenu">
                        <span>
                            <span class="sidebar-icon">
                                <i class="fas fa-user-cog" aria-hidden="true"></i>
                            </span>
                            <span class="sidebar-text">System User</span>
                        </span>
                        <span class="link-arrow">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </button>
                    <div class="multi-level collapse {{ $systemUserMenuOpen ? 'show' : '' }}"
                        id="admin-system-user-submenu">
                        <ul class="flex-column nav">
                            @can('permission_create')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.create') }}"
                                        class="nav-link {{ request()->is('admin/users/create') ? 'active' : '' }}">
                                        <span class="sidebar-text">Create User</span>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                        <span class="sidebar-text">Roles</span>
                                    </a>
                                </li>
                            @endcan
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}"
                                        class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                        <span class="sidebar-text">Permissions</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            @can('role_access')
                <li class="nav-item">
                    <a href="{{ route('admin.banner.index') }}"
                        class="nav-link {{ request()->is('admin/banner*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd"
                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Banner</span>
                    </a>
                </li>
            @endcan

            @can('role_access')
                <li class="nav-item">
                    <a href="{{ route('admin.sector.index') }}"
                        class="nav-link {{ request()->is('admin/sectors*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd"
                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Category</span>
                    </a>
                </li>
            @endcan

            @can('role_access')
                <li class="nav-item">
                    <a href="{{ route('admin.product.index') }}"
                        class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd"
                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Products</span>
                    </a>
                </li>
            @endcan

            {{-- not showing  --}}

            @can('post_access')
                <li class="nav-item">
                    <a href="{{ route('admin.posts.index') }}"
                        class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd"
                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Posts</span>
                    </a>
                </li>
            @endcan

            @can('category_access')
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd"
                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Categories</span>
                    </a>
                </li>
            @endcan

            @can('tag_access')
                <li class="nav-item">
                    <a href="{{ route('admin.tags.index') }}"
                        class="nav-link {{ request()->is('admin/tags*') ? 'active' : '' }}">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                <path fill-rule="evenodd"
                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Tags</span>
                    </a>
                </li>
            @endcan

            {{-- not showing ends  --}}



            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>


            <li class="nav-item">
                <span class="nav-link collapsed d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#submenu-app-setting">
                    <span>
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Setting</span>
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
                <div class="multi-level collapse" role="list" id="submenu-app-setting" aria-expanded="false">
                    <ul class="flex-column nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}"
                                href="{{ route('admin.profile.index') }}">
                                <span class="sidebar-text">Profile</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="multi-level collapse" role="list" id="submenu-app-setting" aria-expanded="false">
                    <ul class="flex-column nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/change-password') ? 'active' : '' }}"
                                href="{{ route('admin.password.index') }}">
                                <span class="sidebar-text">Change Password</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


        </ul>
    </div>
</nav>
