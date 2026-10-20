@extends('layouts.app')

@section('title', 'Trang chủ')

@php
    $catIcons = ['local_cafe', 'icecream', 'lunch_dining', 'bakery_dining', 'emoji_food_beverage', 'cookie'];
    $catBgs = ['bg-primary-fixed text-primary', 'bg-secondary-container text-secondary', 'bg-tertiary-container text-tertiary', 'bg-primary-fixed-dim text-on-primary-fixed-variant', 'bg-surface-container-highest text-on-surface-variant', 'bg-secondary-fixed text-on-secondary-fixed-variant'];
@endphp

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg">
    {{-- Hero --}}
    <section class="mt-md relative rounded-xl overflow-hidden min-h-[420px] flex items-end md:items-center shadow-soft">
        <img src="{{ asset('images/hero-tra-sua.jpg') }}" alt="Ly trà sữa trân châu" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-surface via-surface/80 to-surface/10 md:bg-gradient-to-r md:from-surface md:via-surface/85 md:to-transparent"></div>
        <div class="relative z-10 p-md md:p-xl max-w-[620px]">
            <span class="inline-block px-md py-xs bg-surface/80 rounded-full text-label-md text-on-primary-container mb-md">Pha mới mỗi ngày</span>
            <h1 class="text-headline-xl text-on-surface mb-md">Tìm ly trà sữa hoàn hảo của bạn</h1>
            <p class="text-body-lg text-on-surface-variant mb-lg max-w-[480px]">Trà sữa thơm béo, bánh tráng trộn chua cay và bánh tráng nướng giòn rụm. Đặt online, giao tận nơi hoặc nhận tại quán.</p>
            <div class="flex flex-wrap gap-sm">
                <a href="{{ route('menu', ['category' => 'tra-sua']) }}" class="inline-flex min-h-14 items-center px-xl rounded-full bg-primary text-on-primary text-body-lg font-bold shadow-soft hover:bg-[#6d3d4e] hover:-translate-y-0.5 active:scale-95 transition-all">
                    Đặt trà sữa ngay</a>
                <a href="{{ route('menu') }}" class="inline-flex min-h-14 items-center px-xl rounded-full border-2 border-secondary bg-surface/90 text-secondary text-body-lg font-bold hover:bg-secondary hover:text-on-secondary hover:-translate-y-0.5 active:scale-95 transition-all">
                    Xem thực đơn</a>
            </div>
        </div>
    </section>

    {{-- Browse Categories --}}
    <section class="mt-xl">
        <div class="flex justify-between items-end mb-md">
            <h2 class="text-headline-md text-on-surface">Danh mục</h2>
            <a href="{{ route('menu') }}" class="text-primary text-label-md hover:underline">Xem tất cả</a>
        </div>
        <div class="flex gap-lg overflow-x-auto hide-scrollbar snap-x snap-mandatory touch-pan-x pb-base">
            @foreach($categories as $i => $cat)
                <a href="{{ route('menu', ['category' => $cat->slug]) }}" class="flex-none snap-start flex flex-col items-center gap-base group">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center transition-all group-hover:scale-105 group-hover:shadow-soft {{ $catBgs[$i % count($catBgs)] }}">
                        <span class="material-symbols-outlined" style="font-size:32px">{{ $catIcons[$i % count($catIcons)] }}</span>
                    </div>
                    <span class="text-label-md text-on-surface text-center max-w-[96px]">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Popular Now --}}
    <section class="mt-xl">
        <h2 class="text-headline-md text-on-surface mb-lg">Phổ biến nhất</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-md">
            @foreach($featured as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- Per-category sections --}}
    @foreach($byCategory as $cat)
        @if($cat->availableProducts->isNotEmpty())
            <section class="mt-xl">
                <div class="flex justify-between items-end mb-md">
                    <h2 class="text-headline-md text-on-surface">{{ $cat->name }}</h2>
                    <a href="{{ route('menu', ['category' => $cat->slug]) }}" class="text-primary text-label-md hover:underline">Xem tất cả →</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-md">
                    @foreach($cat->availableProducts->take(4) as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    {{-- Ritual section --}}
    <section class="mt-xl mb-xl bg-tertiary-container/40 rounded-xl p-md md:p-xl flex flex-col md:flex-row items-center gap-lg">
        <div class="flex-1">
            <h2 class="text-headline-lg text-on-surface mb-md">Pha chế tận tâm trong từng ly</h2>
            <p class="text-body-lg text-on-tertiary-container mb-md">Mỗi đơn được pha theo yêu cầu, trân châu nấu mới mỗi vài giờ để giữ độ dai ngon. Nguyên liệu chọn lọc, hương vị chuẩn quán.</p>
            <ul class="space-y-sm mb-lg">
                @foreach(['Nguyên liệu chất lượng cao', 'Trân châu nấu thủ công', 'Giao hàng nhanh tận nơi'] as $point)
                    <li class="flex items-center gap-sm text-label-md text-secondary">
                        <span class="material-symbols-outlined">check_circle</span> {{ $point }}</li>
                @endforeach
            </ul>
            <a href="{{ route('menu', ['category' => 'tra-sua']) }}" class="inline-flex min-h-14 items-center px-xl rounded-full bg-primary text-on-primary text-body-lg font-bold shadow-soft hover:bg-[#6d3d4e] hover:-translate-y-0.5 active:scale-95 transition-all">Đặt trà sữa ngay</a>
        </div>
        <div class="flex-1 w-full h-[300px] md:h-[360px] rounded-xl overflow-hidden shadow-soft">
            <img src="{{ asset('images/pha-che.jpg') }}" alt="Không gian quán và quầy pha chế" class="h-full w-full object-cover">
        </div>
    </section>
</div>
@endsection
