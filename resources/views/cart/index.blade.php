@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-lg">
    <div class="mb-lg">
        <h1 class="text-headline-xl text-on-surface">Giỏ hàng của bạn</h1>
        <p class="text-body-md text-on-surface-variant mt-xs">Kiểm tra lại các món trước khi đặt nhé.</p>
    </div>

    @if(empty($items))
        <div class="bg-surface-container-low rounded-xl p-xl text-center">
            <span class="material-symbols-outlined text-on-surface-variant mb-sm" style="font-size:56px">shopping_cart_off</span>
            <p class="text-body-lg text-on-surface-variant mb-md">Giỏ hàng đang trống.</p>
            <a href="{{ route('menu') }}" class="inline-block px-lg py-md rounded-full bg-primary text-on-primary text-label-md font-bold hover:opacity-90 transition-all">Xem thực đơn</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
            {{-- Items --}}
            <div class="lg:col-span-8 space-y-md">
                @foreach($items as $rowId => $item)
                    <div class="bg-surface-container-lowest p-md rounded-xl flex gap-md shadow-soft">
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start gap-sm">
                                    <h3 class="text-headline-sm text-on-surface">{{ $item['name'] }}</h3>
                                    <span class="text-headline-sm text-primary whitespace-nowrap">@vnd($item['line_total'])</span>
                                </div>
                                <div class="flex flex-wrap gap-xs mt-sm">
                                    @if($item['size_label'])
                                        <span class="px-sm py-1 bg-tertiary-container text-on-tertiary-container rounded-full text-label-sm">{{ $item['size_label'] }}</span>
                                    @endif
                                    @foreach($item['toppings'] as $tp)
                                        <span class="px-sm py-1 bg-primary-container text-on-primary-container rounded-full text-label-sm">+ {{ $tp['name'] }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-md">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center bg-surface-container rounded-full px-xs py-xs border border-outline-variant/40">
                                    @csrf
                                    <input type="hidden" name="row_id" value="{{ $rowId }}">
                                    <button type="button" onclick="stepQty(this,-1)" class="w-8 h-8 flex items-center justify-center text-primary hover:bg-white rounded-full transition-all">
                                        <span class="material-symbols-outlined text-[18px]">remove</span></button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="50"
                                           class="w-10 text-center bg-transparent border-none focus:ring-0 font-bold" onchange="this.form.submit()">
                                    <button type="button" onclick="stepQty(this,1)" class="w-8 h-8 flex items-center justify-center text-primary hover:bg-white rounded-full transition-all">
                                        <span class="material-symbols-outlined text-[18px]">add</span></button>
                                </form>
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="row_id" value="{{ $rowId }}">
                                    <button type="submit" class="text-on-surface-variant hover:text-error flex items-center gap-xs transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                        <span class="text-label-md hidden sm:inline">Xóa</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                <a href="{{ route('menu') }}" class="inline-flex items-center gap-xs text-primary text-label-md hover:underline">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span> Tiếp tục chọn món</a>
            </div>

            {{-- Summary --}}
            <aside class="lg:col-span-4 lg:sticky lg:top-28">
                <div class="bg-surface-container-high p-md rounded-xl shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Tóm tắt đơn</h2>
                    <div class="space-y-sm">
                        <div class="flex justify-between text-on-surface-variant text-body-md">
                            <span>Tạm tính ({{ $cartCount }} món)</span><span>@vnd($subtotal)</span>
                        </div>
                        <div class="flex justify-between text-on-surface-variant text-body-md">
                            <span>Phí giao hàng</span><span class="opacity-70">Tính khi thanh toán</span>
                        </div>
                        <div class="pt-sm border-t border-outline-variant/30 flex justify-between items-center mt-md">
                            <span class="text-headline-md text-on-surface">Tạm tính</span>
                            <span class="text-headline-md text-primary">@vnd($subtotal)</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="w-full mt-lg bg-primary text-on-primary py-md rounded-xl text-headline-sm shadow-soft hover:opacity-90 transition-all active:scale-95 flex items-center justify-center gap-sm">
                        Tiến hành đặt hàng <span class="material-symbols-outlined">arrow_forward</span></a>
                    <div class="mt-md flex items-center justify-center gap-xs text-on-surface-variant opacity-60">
                        <span class="material-symbols-outlined text-[16px]">lock</span>
                        <span class="text-label-sm">Đặt hàng an toàn · Thanh toán COD</span>
                    </div>
                </div>
            </aside>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function stepQty(btn, d) {
        const input = btn.parentElement.querySelector('input[name=quantity]');
        let v = parseInt(input.value || '0', 10) + d;
        if (v < 0) v = 0; if (v > 50) v = 50;
        input.value = v;
        btn.form.submit();
    }
</script>
@endpush
@endsection
