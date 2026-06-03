<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar { background: #2b2118; min-height: 100vh; }
        .sidebar .nav-link { color: #d8cfc4; border-radius: .375rem; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar .nav-link.active { background: #b5651d; color: #fff; }
        .sidebar .brand { color: #f3c98b; font-weight: 700; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3">
            <a href="{{ route('admin.dashboard') }}" class="brand d-block fs-5 mb-4 text-decoration-none">
                <i class="bi bi-cup-straw"></i> {{ config('app.name') }}
            </a>
            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tổng quan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                       href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i> Đơn hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                       href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i> Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                       href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Danh mục</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.toppings.*') ? 'active' : '' }}"
                       href="{{ route('admin.toppings.index') }}"><i class="bi bi-droplet"></i> Topping</a>
                </li>
                <li><hr class="text-secondary"></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-shop"></i> Về trang bán hàng</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="nav-link border-0 bg-transparent w-100 text-start" type="submit">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </nav>

        {{-- Main --}}
        <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">@yield('heading', 'Quản trị')</h3>
                <span class="text-muted"><i class="bi bi-person-circle"></i> {{ auth()->user()->name }}</span>
            </div>
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
