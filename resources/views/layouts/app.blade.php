<!DOCTYPE html>
<html lang="vi" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trà sữa & Bánh tráng') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "surface": "#fff8f6",
                        "surface-dim": "#e1d8d5",
                        "surface-bright": "#fff8f6",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#fbf2ef",
                        "surface-container": "#f5ece9",
                        "surface-container-high": "#efe6e3",
                        "surface-container-highest": "#e9e1de",
                        "on-surface": "#1e1b19",
                        "on-surface-variant": "#514347",
                        "outline": "#837377",
                        "outline-variant": "#d5c2c6",
                        "primary": "#864d61",
                        "on-primary": "#ffffff",
                        "primary-container": "#ffb7ce",
                        "on-primary-container": "#7b4458",
                        "primary-fixed": "#ffd9e3",
                        "primary-fixed-dim": "#fab3ca",
                        "on-primary-fixed-variant": "#6a364a",
                        "secondary": "#3a675a",
                        "on-secondary": "#ffffff",
                        "secondary-container": "#bceddc",
                        "on-secondary-container": "#406d60",
                        "secondary-fixed": "#bceddc",
                        "on-secondary-fixed-variant": "#214e43",
                        "tertiary": "#625e56",
                        "on-tertiary": "#ffffff",
                        "tertiary-container": "#d0cac1",
                        "on-tertiary-container": "#59554e",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a",
                        "background": "#fff8f6",
                        "on-background": "#1e1b19",
                    },
                    borderRadius: { DEFAULT: "0.5rem", lg: "1rem", xl: "1.5rem", full: "9999px" },
                    spacing: { xs: "4px", base: "8px", sm: "12px", gutter: "16px", md: "24px", lg: "40px", xl: "64px" },
                    fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"] },
                    fontSize: {
                        "headline-xl": ["40px", { lineHeight: "48px", letterSpacing: "-0.02em", fontWeight: "800" }],
                        "headline-lg": ["32px", { lineHeight: "40px", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "headline-md": ["24px", { lineHeight: "32px", fontWeight: "700" }],
                        "headline-sm": ["20px", { lineHeight: "28px", fontWeight: "700" }],
                        "body-lg": ["18px", { lineHeight: "28px", fontWeight: "500" }],
                        "body-md": ["16px", { lineHeight: "24px", fontWeight: "500" }],
                        "label-md": ["14px", { lineHeight: "20px", letterSpacing: "0.05em", fontWeight: "600" }],
                        "label-sm": ["12px", { lineHeight: "16px", fontWeight: "700" }],
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .shadow-soft { box-shadow: 0px 10px 30px rgba(74, 69, 67, 0.05); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .card-hover { transition: transform .2s ease; }
        .card-hover:hover { transform: translateY(-4px); }
        .card-hover:active { transform: scale(0.98); }
    </style>
    @stack('head')
</head>
<body class="bg-background text-on-surface min-h-screen flex flex-col">
    @php($navClass = 'text-on-surface-variant hover:text-primary transition-colors text-body-md')
    @php($navActive = 'text-primary border-b-2 border-primary pb-1 font-bold text-body-md')
    <header class="bg-surface sticky top-0 z-50 shadow-soft w-full">
        <nav class="flex justify-between items-center h-20 px-md md:px-lg max-w-[1200px] mx-auto w-full">
            <a href="{{ route('home') }}" class="flex items-center gap-base text-headline-md font-bold text-primary">
                <span class="material-symbols-outlined" style="font-size:32px">local_cafe</span>
                {{ config('app.name') }}
            </a>
            <div class="hidden md:flex items-center gap-lg">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? $navActive : $navClass }}">Trang chủ</a>
                <a href="{{ route('menu') }}" class="{{ request()->routeIs('menu') || request()->routeIs('products.show') ? $navActive : $navClass }}">Thực đơn</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? $navActive : $navClass }}">Đơn hàng</a>
                @endauth
            </div>
            <div class="flex items-center gap-sm">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-primary hover:bg-primary-container/30 rounded-full transition-all active:scale-95">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-primary text-on-primary text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-xs p-2 text-primary hover:bg-primary-container/30 rounded-full transition-all">
                            <span class="material-symbols-outlined">account_circle</span>
                            <span class="hidden sm:inline text-label-md font-semibold max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="absolute right-0 mt-2 w-52 bg-surface-container-lowest rounded-xl shadow-soft border border-outline-variant/30 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            @if(auth()->user()->isAdmin())
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
                    <a href="{{ route('login') }}" class="hidden sm:inline px-md py-sm text-label-md font-semibold text-primary hover:underline">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="px-md py-sm rounded-full bg-primary-container text-on-primary-container text-label-md font-bold hover:opacity-90 active:scale-95 transition-all">Đăng ký</a>
                @endauth
            </div>
        </nav>
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
            </div>
            <div class="flex flex-col gap-sm">
                <h4 class="text-label-md text-on-surface font-bold uppercase tracking-wider">Liên hệ</h4>
                <p class="text-on-surface-variant text-label-md flex items-center gap-xs"><span class="material-symbols-outlined text-[18px]">call</span> 0900 000 000</p>
                <p class="text-on-surface-variant text-label-md flex items-center gap-xs"><span class="material-symbols-outlined text-[18px]">location_on</span> 123 Đường ABC, Q.1, TP.HCM</p>
            </div>
            <div class="flex flex-col gap-sm">
                <h4 class="text-label-md text-on-surface font-bold uppercase tracking-wider">Theo dõi</h4>
                <div class="flex gap-md text-primary">
                    <span class="material-symbols-outlined cursor-pointer hover:scale-110 transition-transform">photo_camera</span>
                    <span class="material-symbols-outlined cursor-pointer hover:scale-110 transition-transform">public</span>
                    <span class="material-symbols-outlined cursor-pointer hover:scale-110 transition-transform">alternate_email</span>
                </div>
            </div>
        </div>
        <div class="max-w-[1200px] mx-auto px-md md:px-lg pb-lg border-t border-outline-variant/20 pt-md">
            <p class="text-center text-on-surface-variant text-label-md">© {{ date('Y') }} {{ config('app.name') }}. Demo Laravel · Thiết kế theo phong cách Boba Bliss.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
