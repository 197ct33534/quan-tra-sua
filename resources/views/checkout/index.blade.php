@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-lg">
    <div class="mb-lg">
        <h1 class="text-headline-xl text-on-surface">Đặt hàng</h1>
        <p class="text-body-md text-on-surface-variant mt-xs">Điền thông tin giao hàng để hoàn tất đơn.</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
            {{-- Form --}}
            <div class="lg:col-span-7 space-y-md">
                <div class="bg-surface-container-lowest rounded-xl p-md md:p-lg shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Thông tin giao hàng</h2>
                    <div class="space-y-md">
                        @php($field = 'w-full pl-[52px] pr-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md')
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md text-on-surface-variant px-xs">Họ tên người nhận *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">person</span>
                                <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" required class="{{ $field }}">
                            </div>
                        </div>
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md text-on-surface-variant px-xs">Số điện thoại *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">call</span>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="{{ $field }}">
                            </div>
                        </div>
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md text-on-surface-variant px-xs">Địa chỉ giao hàng *</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">location_on</span>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" required class="{{ $field }}">
                            </div>
                        </div>
                        <div class="flex flex-col gap-xs">
                            <label class="text-label-md text-on-surface-variant px-xs">Ghi chú</label>
                            <textarea name="note" rows="2" placeholder="Ví dụ: ít đá, ít đường, ít cay..." class="w-full px-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest rounded-xl p-md md:p-lg shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Phương thức thanh toán</h2>
                    <div class="flex items-center gap-sm p-md rounded-lg border-2 border-primary bg-primary-container/15">
                        <span class="material-symbols-outlined text-primary">payments</span>
                        <div>
                            <p class="text-body-md font-bold text-on-surface">Thanh toán khi nhận hàng (COD)</p>
                            <p class="text-label-md text-on-surface-variant">Trả tiền mặt cho shipper khi nhận món.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <aside class="lg:col-span-5 lg:sticky lg:top-28">
                <div class="bg-surface-container-high rounded-xl p-md md:p-lg shadow-soft">
                    <h2 class="text-headline-md text-on-surface mb-md">Đơn hàng của bạn</h2>
                    <ul class="divide-y divide-outline-variant/30 mb-md">
                        @foreach($items as $item)
                            <li class="py-sm flex justify-between gap-sm">
                                <div>
                                    <p class="text-body-md text-on-surface font-semibold">{{ $item['name'] }} <span class="text-on-surface-variant">× {{ $item['quantity'] }}</span></p>
                                    @if($item['size_label'] || !empty($item['toppings']))
                                        <p class="text-label-sm text-on-surface-variant">
                                            {{ $item['size_label'] }}{{ $item['size_label'] && !empty($item['toppings']) ? ' · ' : '' }}{{ collect($item['toppings'])->pluck('name')->join(', ') }}
                                        </p>
                                    @endif
                                </div>
                                <span class="text-body-md whitespace-nowrap">@vnd($item['line_total'])</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="space-y-sm text-body-md">
                        <div class="flex justify-between text-on-surface-variant"><span>Tạm tính</span><span>@vnd($subtotal)</span></div>
                        <div class="flex justify-between text-on-surface-variant"><span>Phí giao hàng</span><span>@vnd($shippingFee)</span></div>
                        <div class="pt-sm border-t border-outline-variant/30 flex justify-between items-center">
                            <span class="text-headline-md text-on-surface">Tổng cộng</span>
                            <span class="text-headline-md text-primary">@vnd($subtotal + $shippingFee)</span>
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
