<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') — {{ config('app.name') }}</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
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
                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                           href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Danh mục</a>
                    </li>
                @endif
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
@stack('scripts')
</body>
</html>
