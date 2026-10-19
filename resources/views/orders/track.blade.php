@extends('layouts.app')

@section('title', 'Tra cứu đơn')

@section('content')
<div class="max-w-[560px] mx-auto px-md md:px-lg py-lg">
    <h1 class="text-headline-xl text-on-surface mb-sm">Tra cứu đơn hàng</h1>
    <p class="text-body-md text-on-surface-variant mb-lg">Nhập đúng mã đơn và số điện thoại đã dùng khi đặt.</p>

    <form action="{{ route('track.store') }}" method="POST" class="bg-surface-container-lowest rounded-xl p-md md:p-lg shadow-soft space-y-md">
        @csrf
        <div class="flex flex-col gap-xs">
            <label for="order_code" class="text-label-md text-on-surface-variant">Mã đơn hàng</label>
            <input id="order_code" name="order_code" value="{{ old('order_code') }}" required maxlength="32"
                   class="w-full px-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md uppercase">
            @error('order_code')<p class="text-error text-label-md">{{ $message }}</p>@enderror
        </div>
        <div class="flex flex-col gap-xs">
            <label for="phone" class="text-label-md text-on-surface-variant">Số điện thoại</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" required maxlength="20"
                   class="w-full px-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md">
            @error('phone')<p class="text-error text-label-md">{{ $message }}</p>@enderror
        </div>
        <button class="w-full bg-primary text-on-primary py-md rounded-xl text-headline-sm">Xem đơn</button>
    </form>
</div>
@endsection
