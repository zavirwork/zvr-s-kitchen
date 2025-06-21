<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
    <div class="container-fluid py-1 px-3">
        <!-- Bagian kiri (judul halaman) -->
        <nav aria-label="breadcrumb">
            <h6 class="font-weight-bolder text-white mb-0">@yield('page-title', '-')</h6>
        </nav>
        
        <!-- Bagian kanan -->
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="d-flex align-items-center ms-auto">  <!-- gunakan ms-auto untuk mendorong ke kanan -->
                <ul class="navbar-nav">
                    <!-- Nama User -->
                    <li class="nav-item d-flex align-items-center">
                        <a href="#" class="nav-link text-white font-weight-bold px-0" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fa fa-user me-sm-1"></i>
                            <span class="d-sm-inline d-none">{{auth()->user()->name}}</span>
                        </a>
                    </li>
                    
                    <!-- Tombol Toggle Mobile -->
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                                <i class="sidenav-toggler-line bg-white"></i>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>