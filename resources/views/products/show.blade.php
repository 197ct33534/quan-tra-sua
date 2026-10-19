@extends('layouts.app')

@section('title', $product->name)
@section('og_type', 'product')
@php
    $shareImage = $product->image_url;
    $shareImage = str_starts_with($shareImage, 'http') ? $shareImage : url($shareImage);
    $sharePrice = number_format((int) $product->price, 0, ',', '.').'đ';
@endphp
@section('og_title', $product->name.' — '.$sharePrice)
@section('og_image', $shareImage)
@section('meta_description', $product->name.' giá '.$sharePrice.'. '.($product->description ?: 'Đặt trà sữa và bánh tráng online, giao tận nơi hoặc nhận tại quán.'))

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-lg pb-28 md:pb-lg">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-xs text-on-surface-variant mb-lg text-label-md">
        <a href="{{ route('home') }}" class="hover:text-primary">Trang chủ</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <a href="{{ route('menu', ['category' => $product->category->slug]) }}" class="hover:text-primary">{{ $product->category->name }}</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-primary font-bold">{{ $product->name }}</span>
    </nav>

    <form id="product-form" action="{{ route('cart.add') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg lg:gap-xl">
            {{-- Left: image + description --}}
            <div class="lg:col-span-6">
                <div class="rounded-xl overflow-hidden shadow-soft bg-surface-container-lowest">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full aspect-[4/5] object-cover">
                </div>
                <div class="mt-lg p-md bg-surface-container-low rounded-xl">
                    <h3 class="text-headline-md text-primary mb-sm">Mô tả</h3>
                    <p class="text-on-surface-variant text-body-md leading-relaxed">{{ $product->description ?: 'Món ngon chuẩn vị, được pha chế/làm mới theo từng đơn hàng.' }}</p>
                </div>
            </div>

            {{-- Right: options --}}
            <div class="lg:col-span-6 flex flex-col gap-lg">
                <div>
                    <div class="inline-flex items-center gap-xs px-sm py-xs bg-secondary-container text-on-secondary-container rounded-full text-label-md mb-sm">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">storefront</span>
                        {{ $product->category->name }}
                    </div>
                    <h1 class="text-headline-xl text-on-background mb-xs">{{ $product->name }}</h1>
                    @php $initialFee = $product->has_options ? (int) ($product->sizes->first()->price ?? 0) : 0; @endphp
                    <span class="text-primary text-headline-md" id="base-price" data-price="{{ (int) $product->price }}">@vnd((int) $product->price + $initialFee)</span>
                </div>

                @if($product->has_options)
                    <section>
                        <h4 class="text-label-md text-on-surface mb-md uppercase tracking-wide">Kích cỡ</h4>
                        @if($product->sizes->isEmpty())
                            <p class="text-body-md text-on-surface-variant">Món này chưa có kích cỡ. Vui lòng báo quán.</p>
                        @else
                            <div class="grid grid-cols-3 gap-sm">
                                @foreach($product->sizes as $i => $size)
                                    <div>
                                        <input type="radio" name="size" id="size{{ $size->code }}" value="{{ $size->code }}"
                                               data-fee="{{ (int) $size->price }}" class="peer hidden"
                                               @checked(old('size', $product->sizes->first()->code) == $size->code)>
                                        <label for="size{{ $size->code }}"
                                               class="cursor-pointer flex flex-col items-center justify-center p-md rounded-xl border-2 border-surface-container-high transition-all active:scale-95
                                                      peer-checked:border-primary peer-checked:bg-primary-container/20 peer-checked:text-on-primary-container">
                                            <span class="material-symbols-outlined" style="font-size:{{ 24 + $i * 6 }}px">local_cafe</span>
                                            <span class="text-label-md mt-xs">{{ $size->label }}</span>
                                            <span class="text-[11px] opacity-70">@if((int) $size->price > 0)+@vnd($size->price)@else Tiêu chuẩn @endif</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section>
                        <h4 class="text-label-md text-on-surface mb-md uppercase tracking-wide">Mức đường</h4>
                        <div class="flex flex-wrap gap-sm">
                            @foreach(\App\Models\OrderItem::SUGAR_LEVELS as $level)
                                <div>
                                    <input type="radio" name="sugar" id="sugar{{ $level }}" value="{{ $level }}" class="peer hidden"
                                           @checked((string) old('sugar', 100) === (string) $level)>
                                    <label for="sugar{{ $level }}"
                                           class="cursor-pointer px-md py-sm rounded-full border-2 border-surface-container-high text-label-md transition-all
                                                  peer-checked:border-primary peer-checked:bg-primary-container/20 peer-checked:text-on-primary-container">
                                        {{ $level === 0 ? 'Không đường' : $level.'%' }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section>
                        <h4 class="text-label-md text-on-surface mb-md uppercase tracking-wide">Mức đá</h4>
                        <div class="flex flex-wrap gap-sm">
                            @foreach(\App\Models\OrderItem::ICE_LEVELS as $level)
                                <div>
                                    <input type="radio" name="ice" id="ice{{ $level }}" value="{{ $level }}" class="peer hidden"
                                           @checked((string) old('ice', 100) === (string) $level)>
                                    <label for="ice{{ $level }}"
                                           class="cursor-pointer px-md py-sm rounded-full border-2 border-surface-container-high text-label-md transition-all
                                                  peer-checked:border-primary peer-checked:bg-primary-container/20 peer-checked:text-on-primary-container">
                                        {{ $level === 0 ? 'Không đá' : $level.'%' }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    @if($toppings->isNotEmpty())
                        <section>
                            <h4 class="text-label-md text-on-surface mb-md uppercase tracking-wide">Topping (tùy chọn)</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-sm">
                                @foreach($toppings as $topping)
                                    <div>
                                        <input type="checkbox" name="toppings[]" id="topping{{ $topping->id }}" value="{{ $topping->id }}"
                                               data-price="{{ (int) $topping->price }}" class="peer hidden"
                                               @checked(in_array($topping->id, array_map('intval', (array) old('toppings', []))))>
                                        <label for="topping{{ $topping->id }}"
                                               class="cursor-pointer flex items-center gap-xs p-sm rounded-xl border-2 border-surface-container-high transition-all active:scale-95
                                                      peer-checked:border-primary peer-checked:bg-primary-container/20 peer-checked:text-on-primary-container">
                                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                                            <span class="text-label-md leading-tight">{{ $topping->name }}<br><span class="text-[11px] opacity-70">+@vnd($topping->price)</span></span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @else
                    <section>
                        <h4 class="text-label-md text-on-surface mb-md uppercase tracking-wide">Mức cay</h4>
                        <div class="flex flex-wrap gap-sm">
                            @foreach(\App\Models\OrderItem::SPICE_LEVELS as $code => $label)
                                <div>
                                    <input type="radio" name="spice" id="spice{{ $code }}" value="{{ $code }}" class="peer hidden"
                                           @checked(old('spice', 'none') === $code)>
                                    <label for="spice{{ $code }}"
                                           class="cursor-pointer px-md py-sm rounded-full border-2 border-surface-container-high text-label-md transition-all
                                                  peer-checked:border-primary peer-checked:bg-primary-container/20 peer-checked:text-on-primary-container">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Quantity + Add --}}
                <div class="mt-auto p-md bg-surface-container-high rounded-xl hidden md:flex items-center gap-md">
                    <div class="flex items-center bg-surface rounded-full p-xs border border-outline-variant">
                        <button type="button" onclick="changeQty(-1)" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-surface-container transition-all active:scale-90">
                            <span class="material-symbols-outlined">remove</span></button>
                        <input type="number" name="quantity" id="qty" value="1" min="1" max="50"
                               class="w-12 text-center bg-transparent border-none focus:ring-0 font-bold text-body-lg">
                        <button type="button" onclick="changeQty(1)" class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-surface-container transition-all active:scale-90">
                            <span class="material-symbols-outlined">add</span></button>
                    </div>
                    <button type="submit" class="flex-grow bg-primary text-on-primary py-md rounded-full text-headline-sm flex items-center justify-center gap-sm hover:opacity-90 active:scale-[0.98] transition-all shadow-soft">
                        <span class="material-symbols-outlined">shopping_cart</span> Thêm vào giỏ
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="md:hidden fixed inset-x-0 bottom-0 z-40 border-t border-outline-variant/40 bg-surface shadow-soft px-md py-sm flex items-center gap-sm">
        <div class="min-w-[96px]">
            <p class="text-label-sm text-on-surface-variant">Tổng tiền</p>
            <p id="sticky-price" class="text-headline-sm text-primary">@vnd((int) $product->price + $initialFee)</p>
        </div>
        <div class="flex items-center bg-surface-container rounded-full p-xs border border-outline-variant">
            <button type="button" onclick="changeQty(-1)" class="w-9 h-9 rounded-full flex items-center justify-center" aria-label="Giảm số lượng">
                <span class="material-symbols-outlined">remove</span></button>
            <span id="sticky-qty" class="w-6 text-center font-bold">1</span>
            <button type="button" onclick="changeQty(1)" class="w-9 h-9 rounded-full flex items-center justify-center" aria-label="Tăng số lượng">
                <span class="material-symbols-outlined">add</span></button>
        </div>
        <button type="submit" form="product-form" class="flex-1 bg-primary text-on-primary py-sm rounded-full text-label-md font-bold">
            Thêm vào giỏ
        </button>
    </div>

    {{-- Related --}}
    @if($related->isNotEmpty())
        <section class="mt-xl">
            <h2 class="text-headline-lg text-on-background mb-lg">Có thể bạn cũng thích</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-md">
                @foreach($related as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
    function formatVnd(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
    }

    function refreshPrice() {
        const priceEl = document.getElementById('base-price');
        if (!priceEl) return;
        const base = parseInt(priceEl.dataset.price || '0', 10);
        const size = document.querySelector('input[name="size"]:checked');
        const sizeFee = size ? parseInt(size.dataset.fee || '0', 10) : 0;
        let toppingFee = 0;
        document.querySelectorAll('input[name="toppings[]"]:checked').forEach(function (el) {
            toppingFee += parseInt(el.dataset.price || '0', 10);
        });
        const qtyEl = document.getElementById('qty');
        let qty = parseInt(qtyEl && qtyEl.value ? qtyEl.value : '1', 10);
        if (qty < 1) qty = 1;
        if (qty > 50) qty = 50;
        const total = formatVnd((base + sizeFee + toppingFee) * qty);
        priceEl.textContent = total;
        const stickyPrice = document.getElementById('sticky-price');
        const stickyQty = document.getElementById('sticky-qty');
        if (stickyPrice) stickyPrice.textContent = total;
        if (stickyQty) stickyQty.textContent = String(qty);
    }

    function changeQty(d) {
        const el = document.getElementById('qty');
        let v = parseInt(el.value || '1', 10) + d;
        if (v < 1) v = 1;
        if (v > 50) v = 50;
        el.value = v;
        refreshPrice();
    }

    document.querySelectorAll('input[name="size"], input[name="toppings[]"], #qty').forEach(function (el) {
        el.addEventListener('change', refreshPrice);
    });
</script>
@endpush
@endsection
