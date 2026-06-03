@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="max-w-[1200px] mx-auto px-md md:px-lg py-xl flex items-center justify-center">
    <div class="w-full max-w-[480px] bg-surface-container-lowest rounded-xl p-lg shadow-soft border border-surface-container-high relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary-container opacity-20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-secondary-container opacity-30 rounded-full blur-3xl"></div>

        <div class="text-center relative">
            <span class="material-symbols-outlined text-primary" style="font-size:40px">local_cafe</span>
            <h1 class="text-headline-lg text-on-surface mt-xs">Chào mừng trở lại</h1>
            <p class="text-body-md text-on-surface-variant">Đăng nhập để đặt món yêu thích của bạn.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-md mt-lg relative">
            @csrf
            @php($field = 'w-full pl-[52px] pr-md py-md rounded-lg bg-surface-container border-none focus:ring-2 focus:ring-primary-container text-body-md')
            <div class="flex flex-col gap-xs">
                <label class="text-label-md text-on-surface-variant px-xs">Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">mail</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="{{ $field }}" placeholder="email@vidu.com">
                </div>
            </div>
            <div class="flex flex-col gap-xs">
                <label class="text-label-md text-on-surface-variant px-xs">Mật khẩu</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock</span>
                    <input type="password" name="password" required class="{{ $field }}" placeholder="••••••••">
                </div>
            </div>
            <label class="flex items-center gap-xs text-label-md text-on-surface-variant px-xs cursor-pointer">
                <input type="checkbox" name="remember" class="rounded text-primary focus:ring-primary-container"> Ghi nhớ đăng nhập
            </label>
            <button type="submit" class="w-full bg-primary-container text-on-primary-container text-[18px] font-bold py-md rounded-lg hover:scale-[0.98] active:scale-95 transition-all">
                Đăng nhập</button>
        </form>

        <p class="text-center text-body-md text-on-surface-variant mt-md relative">
            Chưa có tài khoản? <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Đăng ký ngay</a>
        </p>
        <p class="text-center text-label-sm text-on-surface-variant mt-sm opacity-70 relative">
            Demo Admin: admin@trasua.test / password
        </p>
    </div>
</div>
@endsection
