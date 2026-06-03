@extends('layouts.app')

@section('title', 'Trang chủ')

@php
    $catIcons = ['local_cafe', 'icecream', 'lunch_dining', 'bakery_dining', 'emoji_food_beverage', 'cookie'];
    $catBgs = ['bg-primary-fixed text-primary', 'bg-secondary-container text-secondary', 'bg-tertiary-container text-tertiary', 'bg-primary-fixed-dim text-on-primary-fixed-variant', 'bg-surface-container-highest text-on-surface-variant', 'bg-secondary-fixed text-on-secondary-fixed-variant'];
@endphp

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg">
    {{-- Hero --}}
    <section class="mt-md relative rounded-xl overflow-hidden min-h-[420px] flex items-center shadow-soft
                    bg-gradient-to-br from-primary-fixed via-primary-container to-secondary-container">
        <div class="absolute -right-10 -bottom-10 opacity-30 hidden md:block">
            <span class="material-symbols-outlined text-primary" style="font-size:340px">local_cafe</span>
        </div>
        <div class="relative z-10 p-md md:p-xl max-w-[620px]">
            <span class="inline-block px-md py-xs bg-surface/70 backdrop-blur rounded-full text-label-md text-on-primary-container mb-md">Tươi mới mỗi ngày 🧋</span>
            <h1 class="text-headline-xl text-on-surface mb-md">Tìm ly trà sữa hoàn hảo của bạn</h1>
            <p class="text-body-lg text-on-surface-variant mb-lg max-w-[480px]">Trà sữa thơm béo, bánh tráng trộn chua cay & bánh tráng nướng giòn rụm — đặt online, giao tận nơi nhanh chóng.</p>
            <div class="flex flex-wrap gap-md">
                <a href="{{ route('menu') }}" class="px-lg py-md rounded-full bg-primary text-on-primary text-label-md font-bold shadow-soft hover:opacity-90 active:scale-95 transition-all">
                    Xem thực đơn</a>
                <a href="{{ route('menu', ['category' => $categories->first()->slug ?? '']) }}" class="px-lg py-md rounded-full border-2 border-secondary text-secondary text-label-md font-bold hover:bg-secondary-container/40 active:scale-95 transition-all">
                    Món nổi bật</a>
            </div>
        </div>
    </section>

    {{-- Browse Categories --}}
    <section class="mt-xl">
        <div class="flex justify-between items-end mb-md">
            <h2 class="text-headline-md text-on-surface">Danh mục</h2>
            <a href="{{ route('menu') }}" class="text-primary text-label-md hover:underline">Xem tất cả</a>
        </div>
        <div class="flex gap-lg overflow-x-auto hide-scrollbar pb-base">
            @foreach($categories as $i => $cat)
                <a href="{{ route('menu', ['category' => $cat->slug]) }}" class="flex-none flex flex-col items-center gap-base group">
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
            <a href="{{ route('menu') }}" class="inline-block px-lg py-md rounded-full bg-primary text-on-primary text-label-md font-bold shadow-soft hover:opacity-90 active:scale-95 transition-all">Đặt ngay</a>
        </div>
        <div class="flex-1 w-full h-[300px] rounded-xl overflow-hidden shadow-soft bg-gradient-to-br from-primary-container to-secondary-container flex items-center justify-center">
            <span class="material-symbols-outlined text-on-primary-container" style="font-size:160px">local_cafe</span>
        </div>
    </section>
</div>
@endsection
