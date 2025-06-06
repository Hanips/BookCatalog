<div class="container-fluid fixed-top px-0 wow fadeIn" data-wow-delay="0.1s">
    <nav class="navbar navbar-expand-lg navbar-light py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container-fluid d-flex justify-content-between">
            <!-- Logo di atas dan ditengah pada tampilan mobile -->
            <a href="{{ url('/') }}" class="navbar-brand">
                <h1 class="fw text-primary m-0 text-center">Book<span class="text-secondary">Pedia</span></h1>
            </a>
            <!-- Button burger untuk sidebar mobile -->
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                <div class="custom-burger-icon">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </button>

            <!-- Search Bar Mobile di bawah logo -->
            <div class="mobile-search w-100 d-lg-none mt-2">
                <form action="{{ route('landingpage.ebook') }}" method="GET" class="d-flex align-items-center justify-content-center">
                    <div class="input-group" style="background-color: #f4f4f4; border-radius: 15px; width: 90%;">
                        <input type="text" class="form-control border-0" name="search" placeholder="Cari judul buku" value="{{ $search }}" style="background-color: #f4f4f4; border-radius: 15px;">
                        <button type="submit" class="btn border-0 bg-transparent">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        
            <!-- Navbar Collapse untuk Desktop -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <form action="{{ route('landingpage.ebook') }}" method="GET" class="d-flex align-items-center me-2 w-100">
                        <div class="input-group" style="background-color: #f4f4f4; border-radius: 15px;">
                            <input type="text" class="form-control border-0" name="search" placeholder="Cari judul buku" value="{{ $search }}" style="background-color: #f4f4f4; border-radius: 15px;">
                            <button type="submit" class="btn border-0 bg-transparent">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <a href="{{ url('/') }}" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                    <a href="{{ url('/ebook') }}" class="nav-item nav-link {{ request()->is('ebook') ? 'active' : '' }}">Ebook</a>
                    <a href="{{ url('/promo') }}" class="nav-item nav-link {{ request()->is('promo') ? 'active' : '' }}">Promo</a>
                </div>
                <ul class="navbar-nav ms-auto p-4 p-lg-0">
                    @guest
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-md bg-white btn-outline-dark rounded-pill me-3 wow fadeIn" data-wow-delay="0.1s">
                                <i class="fa fa-user text-body"></i> | Login
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown btn btn-md bg-white btn-outline-dark rounded-pill me-3 wow fadeIn" data-wow-delay="0.1s" data-bs-toggle="dropdown">
                                <!-- Logika untuk Gambar Profil -->
                                @empty(Auth::user()->foto)
                                    <img src="{{ asset('landingpage/img/noimg.png') }}" alt="avatar" class="rounded-circle img-fluid" style="width: 30px; height: 30px;">
                                @else
                                    <img src="{{ url('landingpage/img/' . Auth::user()->foto) }}" class="rounded-circle img-fluid" style="width: 30px; height: 30px;">
                                @endempty
                                | {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-custom m-0" style="border-radius: 10px;">
                                <a href="{{ url('/profile') }}" class="dropdown-item {{ request()->is('profile') ? 'active' : '' }}">Profil</a>
                                <a href="{{ url('/keranjang') }}" class="dropdown-item {{ request()->is('keranjang') ? 'active' : '' }}">Keranjang</a>
                                <a href="{{ url('/pustaka') }}" class="dropdown-item {{ request()->is('pustaka') ? 'active' : '' }}">Pustaka</a>
                                <hr class="dropdown-divider">
                                @if (Auth::user()->role != 'Pelanggan')
                                    <a href="{{ url('/admin') }}" class="dropdown-item">Dashboard</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar Mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header header-with-border">
            <a href="{{ url('/') }}" class="navbar-brand ms-4 ms-lg-0">
                <h1 class="fw text-primary m-0 text-center">Book<span class="text-secondary">Pedia</span></h1>
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            @guest
            @else
                <div class="sidebar-name">
                    <h5>Halo, {{ ucwords(strtolower(Auth::user()->name)) }}</h5>
                </div>
                @if (Auth::user()->role != 'Pelanggan')
                    <div class="sidebar-menu">
                        <a href="{{ url('/admin') }}" class="nav-link">Dashboard</a>
                    </div>
                @endif
            @endguest
            <div class="sidebar-menu mb-3">
                <a class="nav-link" href="{{ url('/') }}">Home</a>
                <a class="nav-link" href="{{ url('/ebook') }}">Ebook</a>
                <a class="nav-link" href="{{ url('/promo') }}">Promo</a>
                @guest
                    <a class="nav-link" href="{{ route('login') }}">
                        Masuk
                    </a>
                @else
                    <a class="nav-link" href="{{ url('/profile') }}">Profil Saya</a>
                    <a class="nav-link" href="{{ url('/keranjang') }}">Keranjang Saya</a>
                    <a class="nav-link" href="{{ url('/pustaka') }}">Pustaka Saya</a>
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Keluar
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </div>
        </div>
    </div>
</div>
