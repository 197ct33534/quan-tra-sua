@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
@php
    $field = 'w-full pl-[52px] pr-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md';
    $choice = 'cursor-pointer flex items-center gap-sm p-md rounded-lg border-2 border-surface-container-high peer-checked:border-primary peer-checked:bg-primary-container/15';
@endphp
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-lg">
    <div class="mb-lg">
        <h1 class="text-headline-xl text-on-surface">Đặt hàng</h1>
        <p class="text-body-md text-on-surface-variant mt-xs">Điền thông tin để hoàn tất đơn. Không cần tài khoản.</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
            <div class="lg:col-span-7 space-y-md">
                <div class="bg-surface-container-lowest rounded-xl p-md md:p-lg shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Phương thức nhận hàng</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-sm">
                        <div>
                            <input type="radio" name="fulfillment" id="fulfillment-delivery" value="delivery" class="peer hidden" @checked(old('fulfillment', $fulfillment) === 'delivery')>
                            <label for="fulfillment-delivery" class="{{ $choice }}">
                                <span class="material-symbols-outlined text-primary">local_shipping</span>
                                <span>
                                    <span class="block text-body-md font-bold text-on-surface">Giao tận nơi</span>
                                    <span class="block text-label-md text-on-surface-variant">Phí 15.000đ, miễn phí từ 150.000đ</span>
                                </span>
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="fulfillment" id="fulfillment-takeaway" value="takeaway" class="peer hidden" @checked(old('fulfillment', $fulfillment) === 'takeaway')>
                            <label for="fulfillment-takeaway" class="{{ $choice }}">
                                <span class="material-symbols-outlined text-primary">storefront</span>
                                <span>
                                    <span class="block text-body-md font-bold text-on-surface">Nhận tại quán</span>
                                    <span class="block text-label-md text-on-surface-variant">Không tính phí giao hàng</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    @error('fulfillment')<p class="text-error text-label-md mt-sm">{{ $message }}</p>@enderror
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-md md:p-lg shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Thông tin nhận hàng</h2>
                    <div class="space-y-md">
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md text-on-surface-variant px-xs">Họ tên người nhận *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">person</span>
                                <input type="text" name="customer_name" value="{{ old('customer_name', $user?->name) }}" required class="{{ $field }}">
                            </div>
                            @error('customer_name')<p class="text-error text-label-md">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md text-on-surface-variant px-xs">Số điện thoại *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">call</span>
                                <input type="text" name="phone" value="{{ old('phone', $user?->phone) }}" required class="{{ $field }}">
                            </div>
                            @error('phone')<p class="text-error text-label-md">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex flex-col gap-xs" id="address-field">
                            <label class="text-label-md text-on-surface-variant px-xs">Địa chỉ giao hàng *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">location_on</span>
                                <input type="text" name="address" id="address" value="{{ old('address', $user?->address) }}" required class="{{ $field }}">
                            </div>
                            @error('address')<p class="text-error text-label-md">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md text-on-surface-variant px-xs">Ghi chú</label>
                            <textarea name="note" rows="2" placeholder="Ghi chú thêm cho quán" class="w-full px-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-md md:p-lg shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Phương thức thanh toán</h2>
                    <div class="space-y-sm">
                        <div>
                            <input type="radio" name="payment_method" id="pay-cod" value="cod" class="peer hidden" @checked(old('payment_method', 'cod') === 'cod')>
                            <label for="pay-cod" class="{{ $choice }}">
                                <span class="material-symbols-outlined text-primary">payments</span>
                                <span>
                                    <span class="block text-body-md font-bold text-on-surface">Tiền mặt khi nhận hàng</span>
                                    <span class="block text-label-md text-on-surface-variant">Trả khi nhận món.</span>
                                </span>
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="payment_method" id="pay-vietqr" value="vietqr" class="peer hidden" @checked(old('payment_method') === 'vietqr')>
                            <label for="pay-vietqr" class="{{ $choice }}">
                                <span class="material-symbols-outlined text-primary">qr_code_2</span>
                                <span>
                                    <span class="block text-body-md font-bold text-on-surface">Chuyển khoản VietQR</span>
                                    <span class="block text-label-md text-on-surface-variant">Mã QR hiện sau khi đặt, nội dung là mã đơn.</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    @error('payment_method')<p class="text-error text-label-md mt-sm">{{ $message }}</p>@enderror
                </div>
            </div>

            <aside class="lg:col-span-5 lg:sticky lg:top-28">
                <div id="order-summary"
                     data-subtotal="{{ $quote['subtotal'] }}"
                     data-discount="{{ $quote['discount'] }}"
                     data-free-from="{{ \App\Services\PricingService::FREE_SHIPPING_FROM }}"
                     data-ship-fee="{{ \App\Services\PricingService::SHIPPING_FEE }}"
                     class="bg-surface-container-high rounded-xl p-md md:p-lg shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Đơn hàng của bạn</h2>
                    @if($quote['notice'])
                        <p class="text-label-md text-error mb-sm">{{ $quote['notice'] }}</p>
                    @endif
                    <ul class="divide-y divide-outline-variant/30 mb-md">
                        @foreach($items as $item)
                            <li class="py-sm flex justify-between gap-sm">
                                <div>
                                    <p class="text-body-md text-on-surface font-semibold">{{ $item['name'] }} <span class="text-on-surface-variant">× {{ $item['quantity'] }}</span></p>
                                    @include('partials.cart-variant-summary', ['item' => $item])
                                </div>
                                <span class="text-body-md whitespace-nowrap">@vnd($item['line_total'])</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="space-y-sm text-body-md">
                        <div class="flex justify-between text-on-surface-variant"><span>Tạm tính</span><span id="sum-subtotal">@vnd($quote['subtotal'])</span></div>
                        <div class="flex justify-between text-on-surface-variant"><span>Giảm giá @if($quote['coupon'])({{ $quote['coupon']->code }})@endif</span><span id="sum-discount">-@vnd($quote['discount'])</span></div>
                        <div class="flex justify-between text-on-surface-variant"><span>Phí giao hàng</span><span id="sum-shipping">@vnd($quote['shipping_fee'])</span></div>
                        <div class="pt-sm border-t border-outline-variant/30 flex justify-between items-center">
                            <span class="text-headline-md text-on-surface">Tổng cộng</span>
                            <span id="sum-total" class="text-headline-md text-primary">@vnd($quote['total'])</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-lg bg-primary text-on-primary py-md rounded-xl text-headline-sm shadow-soft hover:opacity-90 transition-all active:scale-95 flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined">check_circle</span> Đặt hàng</button>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const box = document.getElementById('order-summary');
        if (!box) return;
        const subtotal = parseInt(box.dataset.subtotal || '0', 10);
        const discount = parseInt(box.dataset.discount || '0', 10);
        const freeFrom = parseInt(box.dataset.freeFrom || '150000', 10);
        const shipFee = parseInt(box.dataset.shipFee || '15000', 10);
        const addressWrap = document.getElementById('address-field');
        const addressInput = document.getElementById('address');

        function formatVnd(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        }

        function render() {
            const selected = document.querySelector('input[name="fulfillment"]:checked');
            const takeaway = selected && selected.value === 'takeaway';
            const shipping = takeaway || subtotal >= freeFrom ? 0 : shipFee;
            const total = Math.max(0, subtotal - discount) + shipping;
            document.getElementById('sum-shipping').textContent = formatVnd(shipping);
            document.getElementById('sum-total').textContent = formatVnd(total);
            addressWrap.classList.toggle('hidden', takeaway);
            if (takeaway) {
                addressInput.removeAttribute('required');
            } else {
                addressInput.setAttribute('required', 'required');
            }
        }

        document.querySelectorAll('input[name="fulfillment"]').forEach(function (el) {
            el.addEventListener('change', render);
        });
        render();
    })();
</script>
@endpush
