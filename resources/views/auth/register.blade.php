@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-xl flex items-center justify-center">
    <div class="w-full max-w-[560px] bg-surface-container-lowest rounded-xl p-lg shadow-soft border border-surface-container-high relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary-container opacity-20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-secondary-container opacity-30 rounded-full blur-3xl"></div>

        <div class="text-center relative">
            <span class="material-symbols-outlined text-primary" style="font-size:40px">person_add</span>
            <h1 class="text-headline-lg text-on-surface mt-xs">Tạo tài khoản</h1>
            <p class="text-body-md text-on-surface-variant">Đăng ký để bắt đầu đặt món.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-md mt-lg relative">
            @csrf
            @php($field = 'w-full px-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md')
            <div class="flex flex-col gap-xs">
                <label class="text-label-md text-on-surface-variant px-xs">Họ tên *</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="{{ $field }}">
            </div>
            <div class="flex flex-col gap-xs">
                <label class="text-label-md text-on-surface-variant px-xs">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="{{ $field }}">
            </div>
            <div class="flex flex-col gap-xs">
                <label class="text-label-md text-on-surface-variant px-xs">Số điện thoại *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required class="{{ $field }}">
            </div>
            <div class="flex flex-col gap-xs">
                <label class="text-label-md text-on-surface-variant px-xs">Địa chỉ</label>
                <input type="text" name="address" value="{{ old('address') }}" class="{{ $field }}">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                <div class="flex flex-col gap-xs">
                    <label class="text-label-md text-on-surface-variant px-xs">Mật khẩu *</label>
                    <input type="password" name="password" required class="{{ $field }}">
                </div>
                <div class="flex flex-col gap-xs">
                    <label class="text-label-md text-on-surface-variant px-xs">Xác nhận mật khẩu *</label>
                    <input type="password" name="password_confirmation" required class="{{ $field }}">
                </div>
            </div>
            <button type="submit" class="w-full bg-primary-container text-on-primary-container text-[18px] font-bold py-md rounded-lg hover:scale-[0.98] active:scale-95 transition-all">
                Đăng ký</button>
        </form>

        <p class="text-center text-body-md text-on-surface-variant mt-md relative">
            Đã có tài khoản? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
