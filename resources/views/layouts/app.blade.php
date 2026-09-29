<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kue Ku Marinda - @yield('title', 'Katalog Produk')</title>

    <!-- Framework CSS: Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #F8FAFC; color: #0F172A; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .navbar-custom { background-color: #0F172A; }
        .btn-teal { background-color: #0D9488; color: #ffffff; }
        .btn-teal:hover { background-color: #0F766E; color: #ffffff; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar Shell Utama -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-info" href="{{ url('/') }}">KueKu<span class="text-white">Marinda</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">Katalog Produk</a>
                    </li>

                    @auth
                        @php $role = Auth::user()->role; @endphp

                        @if ($role === 'PEMBELI')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}" href="{{ route('pesanan.index') }}">Pesanan Saya</a></li>
                        @endif

                        @if ($role === 'ADMIN')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">Kategori</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}" href="{{ route('pesanan.index') }}">Pesanan Masuk</a></li>
                        @endif

                        @if ($role === 'KURIR')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('pengiriman.*') ? 'active' : '' }}" href="{{ route('pengiriman.index') }}">Daftar Pengantaran</a></li>
                        @endif

                        @if ($role === 'OWNER')
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">Laporan Penjualan</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">Kelola Admin</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}" href="{{ route('pesanan.index') }}">Semua Pesanan</a></li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-teal btn-sm ms-lg-2" href="{{ route('register') }}">Daftar</a></li>
                    @endguest

                    @auth
                        <li class="nav-item"><span class="navbar-text text-white me-lg-3">{{ Auth::user()->nama }} ({{ Auth::user()->role }})</span></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">Keluar</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Halaman -->
    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-center text-muted small py-4">
        &copy; {{ date('Y') }} Kue Ku Marinda - Sistem Bakery Pre-Order
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>