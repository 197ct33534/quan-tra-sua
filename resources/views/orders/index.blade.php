@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

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
    <div class="mb-lg">
        <h1 class="text-headline-xl text-on-surface">Đơn hàng của tôi</h1>
        <p class="text-body-lg text-on-surface-variant mt-xs">Theo dõi đơn đang xử lý hoặc xem lại đơn cũ.</p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-surface-container-low rounded-xl p-xl text-center">
            <span class="material-symbols-outlined text-on-surface-variant mb-sm" style="font-size:56px">receipt_long</span>
            <p class="text-body-lg text-on-surface-variant mb-md">Bạn chưa có đơn hàng nào.</p>
            <a href="{{ route('menu') }}" class="inline-block px-lg py-md rounded-full bg-primary text-on-primary text-label-md font-bold hover:opacity-90 transition-all">Đặt món ngay</a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-md">
            @foreach($orders as $order)
                <div class="bg-surface-container-lowest rounded-xl p-md shadow-soft flex flex-col md:flex-row gap-md items-start md:items-center card-hover">
                    <div class="w-16 h-16 rounded-lg bg-primary-container/40 flex items-center justify-center flex-shrink-0 text-primary">
                        <span class="material-symbols-outlined" style="font-size:32px">local_mall</span>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-sm mb-xs flex-wrap">
                            <span class="text-label-sm {{ $statusPill[$order->status] ?? 'bg-surface-container text-on-surface-variant' }} px-sm py-xs rounded-full uppercase tracking-wider">{{ $order->status_label }}</span>
                            <span class="text-label-sm text-on-surface-variant">Mã {{ $order->order_code }} • {{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <h3 class="text-headline-sm text-on-surface">{{ $order->items_count }} món</h3>
                        <p class="text-body-md text-primary font-bold">@vnd($order->total)</p>
                    </div>
                    <div class="flex gap-sm w-full md:w-auto">
                        <a href="{{ route('orders.show', $order->order_code) }}" class="flex-1 md:flex-none md:w-36 text-center bg-primary-container text-on-primary-container text-label-md font-bold py-sm px-md rounded-full hover:opacity-90 active:scale-95 transition-all">Xem chi tiết</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-lg">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
