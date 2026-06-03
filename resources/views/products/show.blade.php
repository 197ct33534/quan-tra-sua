@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-lg">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-xs text-on-surface-variant mb-lg text-label-md">
        <a href="{{ route('home') }}" class="hover:text-primary">Trang chủ</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <a href="{{ route('menu', ['category' => $product->category->slug]) }}" class="hover:text-primary">{{ $product->category->name }}</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-primary font-bold">{{ $product->name }}</span>
    </nav>

    <form action="{{ route('cart.add') }}" method="POST">
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
                    <span class="text-primary text-headline-md" id="base-price" data-price="{{ $product->price }}">@vnd($product->price)</span>
                </div>

                @if($product->has_options)
                    {{-- Size --}}
                    <section>
                        <h4 class="text-label-md text-on-surface mb-md uppercase tracking-wide">Kích cỡ</h4>
                        <div class="grid grid-cols-3 gap-sm">
                            @foreach([['S','Nhỏ','S',0],['M','Vừa','M',5000],['L','Lớn','L',10000]] as $i => $sz)
                                <div>
                                    <input type="radio" name="size" id="size{{ $sz[0] }}" value="{{ $sz[0] }}" class="peer hidden" {{ $i === 0 ? 'checked' : '' }}>
                                    <label for="size{{ $sz[0] }}"
                                           class="cursor-pointer flex flex-col items-center justify-center p-md rounded-xl border-2 border-surface-container-high transition-all active:scale-95
                                                  peer-checked:border-primary peer-checked:bg-primary-container/20 peer-checked:text-on-primary-container">
                                        <span class="material-symbols-outlined" style="font-size:{{ 24 + $i*6 }}px">local_cafe</span>
                                        <span class="text-label-md mt-xs">{{ $sz[1] }}</span>
                                        <span class="text-[11px] opacity-70">{{ $sz[3] > 0 ? '+'.number_format($sz[3],0,',','.').'đ' : 'Tiêu chuẩn' }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Toppings --}}
                    @if($toppings->isNotEmpty())
                        <section>
                            <h4 class="text-label-md text-on-surface mb-md uppercase tracking-wide">Topping (tùy chọn)</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-sm">
                                @foreach($toppings as $topping)
                                    <div>
                                        <input type="checkbox" name="toppings[]" id="topping{{ $topping->id }}" value="{{ $topping->id }}" class="peer hidden">
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
                @endif

                {{-- Quantity + Add --}}
                <div class="mt-auto p-md bg-surface-container-high rounded-xl flex items-center gap-md">
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
    function changeQty(d) {
        const el = document.getElementById('qty');
        let v = parseInt(el.value || '1', 10) + d;
        if (v < 1) v = 1; if (v > 50) v = 50;
        el.value = v;
    }
</script>
@endpush
@endsection
