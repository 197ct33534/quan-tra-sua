@extends('layouts.app')

@section('title', 'Đơn ' . $order->order_code)

@php
    $statusPill = [
        'pending' => 'bg-tertiary-container text-on-tertiary-container',
        'confirmed' => 'bg-secondary-container text-on-secondary-container',
        'preparing' => 'bg-primary-container text-on-primary-container',
        'delivering' => 'bg-primary-container text-on-primary-container',
        'completed' => 'bg-secondary text-on-secondary',
        'cancelled' => 'bg-error-container text-on-error-container',
    ];
@endphp

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-lg">
    <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-xs text-primary text-label-md hover:underline mb-md">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span> Danh sách đơn</a>

    <div class="flex flex-wrap justify-between items-center gap-sm mb-lg">
        <h1 class="text-headline-xl text-on-surface">Đơn <span class="text-primary">{{ $order->order_code }}</span></h1>
        <span class="text-label-md {{ $statusPill[$order->status] ?? 'bg-surface-container text-on-surface-variant' }} px-md py-sm rounded-full uppercase tracking-wider">{{ $order->status_label }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg items-start">
        {{-- Items --}}
        <div class="lg:col-span-7">
            <div class="bg-surface-container-lowest rounded-xl shadow-soft overflow-hidden">
                <h2 class="text-headline-md text-on-surface px-md pt-md pb-sm">Món đã đặt</h2>
                <ul class="divide-y divide-outline-variant/30">
                    @foreach($order->items as $item)
                        <li class="px-md py-sm flex justify-between gap-sm">
                            <div>
                                <p class="text-body-md text-on-surface font-semibold">{{ $item->product_name }} <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                                <div class="flex flex-wrap gap-xs mt-xs">
                                    @if($item->size)
                                        <span class="px-sm py-1 bg-tertiary-container text-on-tertiary-container rounded-full text-label-sm">Size {{ $item->size }}</span>
                                    @endif
                                    @foreach(($item->toppings ?? []) as $tp)
                                        <span class="px-sm py-1 bg-primary-container text-on-primary-container rounded-full text-label-sm">+ {{ $tp['name'] }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <span class="text-body-md text-primary whitespace-nowrap">@vnd($item->line_total)</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Info + totals --}}
        <aside class="lg:col-span-5 space-y-md">
            <div class="bg-surface-container-lowest rounded-xl p-md shadow-soft">
                <h2 class="text-headline-sm text-on-surface mb-sm">Thông tin giao hàng</h2>
                <p class="text-body-md text-on-surface-variant flex items-center gap-xs"><span class="material-symbols-outlined text-[20px] text-primary">person</span> {{ $order->customer_name }}</p>
                <p class="text-body-md text-on-surface-variant flex items-center gap-xs"><span class="material-symbols-outlined text-[20px] text-primary">call</span> {{ $order->phone }}</p>
                <p class="text-body-md text-on-surface-variant flex items-center gap-xs"><span class="material-symbols-outlined text-[20px] text-primary">location_on</span> {{ $order->address }}</p>
                @if($order->note)
                    <p class="text-body-md text-on-surface-variant flex items-center gap-xs"><span class="material-symbols-outlined text-[20px] text-primary">chat</span> {{ $order->note }}</p>
                @endif
            </div>
            <div class="bg-surface-container-high rounded-xl p-md shadow-soft">
                <div class="space-y-sm text-body-md">
                    <div class="flex justify-between text-on-surface-variant"><span>Tạm tính</span><span>@vnd($order->subtotal)</span></div>
                    <div class="flex justify-between text-on-surface-variant"><span>Phí giao hàng</span><span>@vnd($order->shipping_fee)</span></div>
                    <div class="pt-sm border-t border-outline-variant/30 flex justify-between items-center">
                        <span class="text-headline-md text-on-surface">Tổng cộng</span>
                        <span class="text-headline-md text-primary">@vnd($order->total)</span>
                    </div>
                </div>
                <p class="text-label-sm text-on-surface-variant mt-md">Thanh toán: COD · Đặt lúc {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </aside>
    </div>
</div>
@endsection
