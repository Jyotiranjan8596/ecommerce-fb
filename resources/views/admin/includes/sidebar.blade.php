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
                    <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" aria-current="page"
                        href="{{ route('admin.index') }}">
                        <span data-feather="home" class="align-text-bottom"></span>
                        Dashboard
                    </a>
                </li>
                @can('user_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href=""
                            id="usersMenuToggle">
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
                                  Add Customer
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('permission_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}"
                            href="{{ route('admin.permissions.index') }}">
                            <span data-feather="shield" class="align-text-bottom"></span>
                            Permissions
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}"
                            href="{{ route('admin.roles.index') }}">
                            <span data-feather="disc" class="align-text-bottom"></span>
                            Roles
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/pos*') ? 'active' : '' }}"
                            href="{{ route('admin.pos_system.index') }}" id="posMenuToggle">
                            <span data-feather="user" class="align-text-bottom"></span>
                            POS
                        </a>
                        <ul class="nav-subitem {{ request()->is('admin/pos*') ? 'open' : '' }}" id="posSubmenu">
                            <li>
                                <a class="nav-link {{ request()->is('admin/pos') ? 'active' : '' }}"
                                    href="{{ route('admin.pos_system.index') }}">
                                    POS LIST
                                </a>
                            </li>
                            <li>
                                <a class="nav-link {{ request()->is('admin/pos/add') ? 'active' : '' }}"
                                    href="{{ route('admin.pos_system.create') }}">
                                    ADD POS
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/dsr*') ? 'active' : '' }}"
                            href="{{ route('admin.dsr') }}">
                            <span data-feather="shield" class="align-text-bottom"></span>
                            DSR
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/msr*') ? 'active' : '' }}"
                            href="{{ route('admin.msr') }}">
                            <span data-feather="user" class="align-text-bottom"></span>
                            MSR
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/wallet*') ? 'active' : '' }}"
                            href="{{ route('admin.wallet') }}">
                            <span data-feather="credit-card" class="align-text-bottom"></span>
                            WALLET
                        </a>
                    </li>
                @endcan


                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/banner*') ? 'active' : '' }}"
                            href="{{ route('admin.banner.index') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            Banner
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/sectors*') ? 'active' : '' }}"
                            href="{{ route('admin.sector.index') }}">
                            <span data-feather="briefcase" class="align-text-bottom"></span>
                            Category
                        </a>
                    </li>
                @endcan
                {{-- @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/subsectors*') ? 'active' : '' }}"
                            href="{{ route('admin.subsector.index') }}">
                            <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                            SubCategory
                        </a>
                    </li>
                @endcan 
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/blog_categories*') ? 'active' : '' }}"
                            href="{{ route('admin.blog_category.index') }}">
                            <span data-feather="book" class="align-text-bottom"></span>
                            Blog Category
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/blogs*') ? 'active' : '' }}"
                            href="{{ route('admin.blog.index') }}">
                            <span data-feather="file-text" class="align-text-bottom"></span>
                            Blog
                        </a>
                    </li>
                @endcan

                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/event_categories*') ? 'active' : '' }}"
                            href="{{ route('admin.event_category.index') }}">
                            <span data-feather="archive" class="align-text-bottom"></span>
                            Event Category
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/events*') ? 'active' : '' }}"
                            href="{{ route('admin.event.index') }}">
                            <span data-feather="message-circle" class="align-text-bottom"></span>
                            Event
                        </a>
                    </li>
                @endcan
                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/info_graphics*') ? 'active' : '' }}"
                            href="{{ route('admin.info_graphic.index') }}">
                            <span data-feather="pen-tool" class="align-text-bottom"></span>
                            Info Graphics
                        </a>
                    </li>
                @endcan --}}

                @can('role_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}"
                            href="{{ route('admin.product.index') }}">
                            <span data-feather="package" class="align-text-bottom"></span>
                            Products
                        </a>
                    </li>
                @endcan

                @can('post_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/posts*') ? 'active' : '' }}"
                            href="{{ route('admin.posts.index') }}">
                            <span data-feather="file" class="align-text-bottom"></span>
                            Posts
                        </a>
                    </li>
                @endcan
                @can('category_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}"
                            href="{{ route('admin.categories.index') }}">
                            <span data-feather="list" class="align-text-bottom"></span>
                            Categories
                        </a>
                    </li>
                @endcan
                @can('tag_access')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/tags*') ? 'active' : '' }}"
                            href="{{ route('admin.tags.index') }}">
                            <span data-feather="tag" class="align-text-bottom"></span>
                            Tags
                        </a>
                    </li>
                @endcan
            </ul>

            <h6
                class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                <span>Setting</span>
                <a class="link-secondary" href="#" aria-label="Add a new report">
                    <span data-feather="plus-circle" class="align-text-bottom"></span>
                </a>
            </h6>
            <ul class="nav flex-column mb-2">
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span data-feather="settings" class="align-text-bottom"></span>
                        App Setting
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}"
                        href="{{ route('admin.profile.index') }}">
                        <span data-feather="user" class="align-text-bottom"></span>
                        Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/change-password') ? 'active' : '' }}"
                        href="{{ route('admin.password.index') }}">
                        <span data-feather="key" class="align-text-bottom"></span>
                        Change Password
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>

<script>
    document.getElementById('posMenuToggle').addEventListener('click', function(event) {
        event.preventDefault();
        var submenu = document.getElementById('posSubmenu');
        submenu.classList.toggle('open');
    });
    document.getElementById('usersMenuToggle').addEventListener('click', function(event) {
        event.preventDefault();
        var submenu = document.getElementById('usersSubmenu');
        submenu.classList.toggle('open');
    });
</script>
