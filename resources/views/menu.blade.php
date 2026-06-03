@extends('layouts.app')

@section('title', $activeCategory->name ?? 'Thực đơn')

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-lg">
    <div class="mb-lg">
        <h1 class="text-headline-xl text-on-surface">{{ $activeCategory->name ?? 'Khám phá thực đơn' }}</h1>
        <p class="text-body-md text-on-surface-variant mt-xs">
            @if(request('q')) Kết quả cho "{{ request('q') }}" @else Chọn món yêu thích của bạn từ danh sách tuyển chọn. @endif
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
        {{-- Sidebar categories --}}
        <aside class="lg:col-span-3">
            <div class="bg-surface-container-lowest rounded-xl p-sm shadow-soft lg:sticky lg:top-28">
                <h3 class="text-label-md text-on-surface-variant uppercase tracking-wider px-md pt-sm pb-xs">Danh mục</h3>
                <div class="flex lg:flex-col gap-xs overflow-x-auto hide-scrollbar">
                    <a href="{{ route('menu') }}"
                       class="flex-none lg:flex-auto px-md py-sm rounded-full lg:rounded-lg text-body-md transition-all {{ !$activeCategory ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                        Tất cả món</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('menu', ['category' => $cat->slug]) }}"
                           class="flex-none lg:flex-auto px-md py-sm rounded-full lg:rounded-lg text-body-md transition-all whitespace-nowrap {{ $activeCategory && $activeCategory->id === $cat->id ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container' }}">
                            {{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Products grid --}}
        <div class="lg:col-span-9">
            @if($products->isEmpty())
                <div class="bg-surface-container-low rounded-xl p-xl text-center text-on-surface-variant">
                    <span class="material-symbols-outlined mb-sm" style="font-size:48px">search_off</span>
                    <p class="text-body-lg">Không tìm thấy sản phẩm nào.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-md">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-lg">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
