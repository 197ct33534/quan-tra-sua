<!DOCTYPE html>
<html lang="vi" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $pageTitle = trim($__env->yieldContent('title', 'Trà sữa & Bánh tráng'));
        $fullTitle = $pageTitle.' — '.config('app.name');
        $metaDescription = trim($__env->yieldContent('meta_description', 'Trà sữa thơm béo, bánh tráng trộn và bánh tráng nướng. Đặt online, giao tận nơi hoặc nhận tại quán.'));
        $ogTitle = trim($__env->yieldContent('og_title', $fullTitle));
        $ogImage = trim($__env->yieldContent('og_image', asset('images/og-default.png')));
        if ($ogImage !== '' && ! str_starts_with($ogImage, 'http')) {
            $ogImage = url($ogImage);
        }
    @endphp
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="vi_VN">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="{{ $ogTitle }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
@php($showStickyCart = $cartCount > 0 && ! request()->routeIs('cart.*', 'checkout.*'))
@php($liftStickyCart = $showStickyCart && request()->routeIs('products.show'))
<body class="bg-background text-on-surface min-h-screen flex flex-col {{ $liftStickyCart ? 'pb-40 md:pb-0' : ($showStickyCart ? 'pb-24 md:pb-0' : '') }}">
    @php($navClass = 'text-on-surface-variant hover:text-primary transition-colors text-body-md')
    @php($navActive = 'text-primary border-b-2 border-primary pb-1 font-bold text-body-md')
    <header class="bg-surface sticky top-0 z-50 shadow-soft w-full">
        <nav class="flex justify-between items-center h-20 px-md md:px-lg max-w-[1200px] mx-auto w-full">
            <a href="{{ route('home') }}" class="flex items-center gap-base text-headline-md font-bold text-primary">
                <span class="material-symbols-outlined" style="font-size:32px">local_cafe</span>
                <span class="hidden sm:inline">{{ config('app.name') }}</span>
            </a>
            <form action="{{ route('menu') }}" method="GET" class="hidden lg:flex items-center" role="search">
                <label for="header-q" class="sr-only">Tìm món</label>
                <input id="header-q" type="search" name="q" value="{{ request()->routeIs('menu') ? request('q') : '' }}" placeholder="Tìm món..."
                       class="min-h-11 w-44 xl:w-56 rounded-full border border-outline-variant/40 bg-surface-container px-md text-body-md">
            </form>
            <div class="hidden md:flex items-center gap-lg">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? $navActive : $navClass }}">Trang chủ</a>
                <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') || request()->routeIs('products.show') ? $navActive : $navClass }}">Thực đơn</a>
                <a href="{{ route('track.form') }}" class="{{ request()->routeIs('track.*') ? $navActive : $navClass }}">Tra cứu đơn</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? $navActive : $navClass }}">Đơn hàng</a>
                @endauth
            </div>
            <div class="flex items-center gap-xs sm:gap-sm">
                <a href="{{ route('cart.index') }}" aria-label="Giỏ hàng" class="relative p-2 text-primary hover:bg-primary-container/30 rounded-full transition-all active:scale-95">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 bg-primary text-on-primary text-[11px] font-bold rounded-full flex items-center justify-center ring-2 ring-surface">{{ $cartCount }}</span>
                    @endif
                </a>
                <button type="button" id="nav-toggle" class="md:hidden p-2 text-primary rounded-full hover:bg-primary-container/30" aria-expanded="false" aria-controls="mobile-nav" aria-label="Mở menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                @auth
                    <div class="relative group hidden md:block">
                        <button class="flex items-center gap-xs p-2 text-primary hover:bg-primary-container/30 rounded-full transition-all">
                            <span class="material-symbols-outlined">account_circle</span>
                            <span class="hidden sm:inline text-label-md font-semibold max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-52 bg-surface-container-lowest rounded-xl shadow-soft border border-outline-variant/30 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            @if(auth()->user()->canOperate())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-sm px-md py-sm text-body-md hover:bg-surface-container">
                                    <span class="material-symbols-outlined text-[20px]">dashboard</span> Khu quản trị</a>
                                <div class="h-px bg-outline-variant/30 my-1"></div>
                            @endif
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-sm px-md py-sm text-body-md hover:bg-surface-container">
                                <span class="material-symbols-outlined text-[20px]">receipt_long</span> Đơn hàng của tôi</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-sm px-md py-sm text-body-md text-error hover:bg-error-container/40 text-left">
                                    <span class="material-symbols-outlined text-[20px]">logout</span> Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline px-md py-sm text-label-md font-semibold text-primary hover:underline">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="hidden md:inline px-md py-sm rounded-full bg-primary-container text-on-primary-container text-label-md font-bold hover:opacity-90 active:scale-95 transition-all">Đăng ký</a>
                @endauth
            </div>
        </nav>
        <div id="mobile-nav" class="hidden md:hidden border-t border-outline-variant/30 px-md pb-md bg-surface">
            <form action="{{ route('menu') }}" method="GET" class="pt-sm" role="search">
                <label for="mobile-q" class="sr-only">Tìm món</label>
                <input id="mobile-q" type="search" name="q" value="{{ request()->routeIs('menu') ? request('q') : '' }}" placeholder="Tìm trà sữa, bánh tráng..."
                       class="min-h-11 w-full rounded-full border border-outline-variant/40 bg-surface-container px-md text-body-md">
            </form>
            <div class="flex flex-col py-sm">
                <a href="{{ route('home') }}" class="py-sm text-body-md {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-on-surface' }}">Trang chủ</a>
                <a href="{{ route('menu') }}" class="py-sm text-body-md {{ request()->routeIs('menu') || request()->routeIs('products.show') ? 'text-primary font-bold' : 'text-on-surface' }}">Thực đơn</a>
                <a href="{{ route('track.form') }}" class="py-sm text-body-md {{ request()->routeIs('track.*') ? 'text-primary font-bold' : 'text-on-surface' }}">Tra cứu đơn</a>
                <a href="{{ route('cart.index') }}" class="py-sm text-body-md">Giỏ hàng @if($cartCount > 0)({{ $cartCount }})@endif</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="py-sm text-body-md">Đơn hàng của tôi</a>
                    @if(auth()->user()->canOperate())
                        <a href="{{ route('admin.dashboard') }}" class="py-sm text-body-md">Khu quản trị</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="py-sm text-body-md text-error">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="py-sm text-body-md">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="py-sm text-body-md text-primary font-bold">Đăng ký</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow w-full">
        <div class="max-w-[1200px] mx-auto px-md md:px-lg">
            @include('partials.flash')
        </div>
        @yield('content')
    </main>

    <footer class="bg-surface-container mt-lg border-t border-outline-variant/20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md px-md md:px-lg py-xl max-w-[1200px] mx-auto">
            <div class="flex flex-col gap-sm">
                <span class="text-headline-sm font-bold text-primary flex items-center gap-xs">
                    <span class="material-symbols-outlined">local_cafe</span> {{ config('app.name') }}</span>
                <p class="text-on-surface-variant text-label-md max-w-[220px]">Trà sữa thơm béo, bánh tráng trộn & bánh tráng nướng chuẩn vị — đặt online, giao tận nơi.</p>
            </div>
            <div class="flex flex-col gap-sm">
                <h4 class="text-label-md text-on-surface font-bold uppercase tracking-wider">Khám phá</h4>
                <a href="{{ route('menu') }}" class="text-on-surface-variant hover:text-primary transition-colors text-label-md">Thực đơn</a>
                <a href="{{ route('cart.index') }}" class="text-on-surface-variant hover:text-primary transition-colors text-label-md">Giỏ hàng</a>
                <a href="{{ route('track.form') }}" class="text-on-surface-variant hover:text-primary transition-colors text-label-md">Tra cứu đơn</a>
            </div>
            <div class="flex flex-col gap-sm">
                <h4 class="text-label-md text-on-surface font-bold uppercase tracking-wider">Liên hệ</h4>
                <a href="tel:{{ preg_replace('/\s+/', '', config('shop.phone')) }}" class="text-on-surface-variant text-label-md flex items-center gap-xs hover:text-primary">
                    <span class="material-symbols-outlined text-[18px]">call</span> {{ config('shop.phone') }}</a>
                <a href="{{ config('shop.maps') }}" target="_blank" rel="noopener" class="text-on-surface-variant text-label-md flex items-start gap-xs hover:text-primary">
                    <span class="material-symbols-outlined text-[18px]">location_on</span> {{ config('shop.address') }}</a>
            </div>
            <div class="flex flex-col gap-sm">
                <h4 class="text-label-md text-on-surface font-bold uppercase tracking-wider">Giờ mở cửa</h4>
                <p class="text-on-surface-variant text-label-md flex items-center gap-xs"><span class="material-symbols-outlined text-[18px]">schedule</span> {{ config('shop.hours') }}</p>
                <div class="flex gap-sm pt-xs">
                    <a href="{{ config('shop.facebook') }}" target="_blank" rel="noopener" aria-label="Facebook" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-surface text-primary hover:bg-primary hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined">public</span></a>
                    <a href="{{ config('shop.instagram') }}" target="_blank" rel="noopener" aria-label="Instagram" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-surface text-primary hover:bg-primary hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined">photo_camera</span></a>
                    <a href="{{ config('shop.zalo') }}" target="_blank" rel="noopener" aria-label="Zalo" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-surface text-primary hover:bg-primary hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined">chat</span></a>
                    <a href="{{ config('shop.maps') }}" target="_blank" rel="noopener" aria-label="Bản đồ" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-surface text-primary hover:bg-primary hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined">map</span></a>
                </div>
            </div>
        </div>
        <div class="max-w-[1200px] mx-auto px-md md:px-lg pb-lg border-t border-outline-variant/20 pt-md">
            <p class="text-center text-on-surface-variant text-label-md">© {{ date('Y') }} {{ config('app.name') }}. Đặt online, giao tận nơi hoặc nhận tại quán.</p>
        </div>
    </footer>

    @if($showStickyCart)
        <a href="{{ route('cart.index') }}"
           class="md:hidden fixed inset-x-4 z-40 flex min-h-12 items-center justify-between rounded-full bg-primary px-md text-on-primary shadow-soft {{ $liftStickyCart ? 'bottom-24' : 'bottom-4' }}">
            <span class="text-label-md font-bold">Giỏ hàng · {{ $cartCount }} món</span>
            <span class="text-label-md font-bold">Xem giỏ</span>
        </a>
    @endif

    @stack('scripts')
</body>
</html>
