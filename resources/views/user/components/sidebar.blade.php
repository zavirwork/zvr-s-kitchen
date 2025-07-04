<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <!-- Close button (visible on mobile) -->
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>

        <!-- Brand logo -->
        <a class="navbar-brand m-0" href="{{ route('user.dashboard') }}">
            <img src="{{ asset('assets/img/logo.png') }}" style="height: 100px; width: 40px;" class="navbar-brand-img" alt="main_logo">
            <span class="ms-1 font-weight-bold">Zvr's Kitchen</span>
        </a>
    </div>

    <hr class="horizontal dark mt-0">

    <!-- Menu items -->
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}"
                    href="{{ route('user.dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-chart-bar-32 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('user/orders') ? 'active' : '' }}"
                    href="{{ route('user.orders.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-cart text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">My Order</span>
                </a>
            </li>
            <!-- Tombol Logout Mobile -->
            <li class="nav-item d-block d-xl-none mt-auto">
                <hr class="horizontal dark mb-2">
                <a class="nav-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-user-run text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
