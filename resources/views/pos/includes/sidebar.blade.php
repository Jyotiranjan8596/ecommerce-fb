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
                    <a class="nav-link {{ request()->is('pos') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('pos.index') }}">
                        <span data-feather="home" class="align-text-bottom"></span>
                        Dashboard
                    </a>
                </li>               
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('userlist') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('pos.user.list') }}">
                        <span data-feather="credit-card" class="align-text-bottom"></span>
                        Wallet Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dsr') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('pos.dsr') }}">
                        <span data-feather="file-text" class="align-text-bottom"></span>
                       DSR
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('msr') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('pos.msr') }}">
                        <span data-feather="file-text" class="align-text-bottom"></span>
                       MSR
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('journal') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('pos.journal') }}">
                        <span data-feather="book" class="align-text-bottom"></span>
                      Journal
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('unverified/user') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('pos.unverified.user') }}">
                        <span data-feather="user" class="align-text-bottom"></span>
                     Unverified Customer
                    </a>
                </li>
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
