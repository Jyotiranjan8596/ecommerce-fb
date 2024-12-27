<style>
    .nav-subitem {
        display: none;
    }

    .nav-subitem.open {
        display: block;
    }
</style>
<div class="sidebar position-fixed border-right col-md-3 col-lg-2 p-0 bg-body-tertiary" style="z-index: 9999;">
    <div class="offcanvas-md offcanvas-start bg-body-tertiary" tabindex="-1" id="sidebarMenu"
        aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">{{ config('devstarit.app_name') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body position-static sidebar-sticky d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto"
            style="background-color: #202C46 !important;">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('user') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('user.index') }}">
                        <span data-feather="home" class="align-text-bottom"></span>
                        Dashboard
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('user/profile') ? 'active' : '' }}"
                        href="">
                        <span data-feather="user" class="bi bi-cart"></span>
                       SHOP NOW
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('add') ? 'active' : '' }}" aria-current="page" href="#"
                        id="usersMenuToggle">
                        <span data-feather="users" class="align-text-bottom"></span>
                        User Management
                    </a>
                    <ul class="nav-subitem {{ request()->is('user*') ? 'active' : '' }}" id="usersSubmenu">
                        <li>
                            <a class="nav-link {{ request()->is('add') ? 'active' : '' }}"
                                href="{{ route('user.add') }}">
                                Add User
                            </a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->is('user/management/list') ? 'active' : '' }}"
                                href="{{ route('user.sponsor.list') }}">
                                My Sponsor List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('pos/list') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('user.pos.list') }}">
                        <span data-feather="dollar-sign" class="align-text-bottom"></span>
                        POS LIST
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('my/wallet') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('user.wallet') }}">
                        <span data-feather="credit-card" class="align-text-bottom"></span>
                        MY WALLET
                    </a>
                </li>
               

                {{-- @can('user_access')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                        href="" id="usersMenuToggle">
                        <span data-feather="users" class="align-text-bottom"></span>
                        Users
                    </a>
                    <ul class="nav-subitem {{ request()->is('admin/users*') ? 'open' : '' }}" id="usersSubmenu">
                        <li>
                            <a class="nav-link {{ request()->is('admin/users/list') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                 User List
                            </a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->is('admin/users/system') ? 'active' : '' }}"
                                href="{{ route('admin.users.create') }}">
                                System User
                            </a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->is('admin/users/custom') ? 'active' : '' }}"
                                href="{{ route('admin.user.customer') }}">
                                Custom User
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan --}}



                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/change-password') ? 'active' : '' }}"
                        href="">
                        <span data-feather="key" class="align-text-bottom"></span>
                        Change Password
                    </a>
                </li> --}}

            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var usersMenuToggle = document.getElementById('usersMenuToggle');
        var usersSubmenu = document.getElementById('usersSubmenu');

        usersMenuToggle.addEventListener('click', function(event) {
            event.preventDefault(); 
            usersSubmenu.classList.toggle('open'); 
        });

        document.addEventListener('click', function(event) {
            if (!usersMenuToggle.contains(event.target) && !usersSubmenu.contains(event.target)) {
                usersSubmenu.classList.remove('open');
            }
        });
    });
</script>
