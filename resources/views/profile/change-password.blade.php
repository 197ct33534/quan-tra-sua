@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="max-w-[800px] mx-auto px-md md:px-lg py-lg">
    <div class="mb-lg">
        <h1 class="text-headline-xl text-on-surface">Hồ sơ cá nhân</h1>
        <p class="text-body-md text-on-surface-variant mt-xs">Quản lý thông tin tài khoản của bạn.</p>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-xs border-b border-outline-variant/30 mb-lg overflow-x-auto hide-scrollbar">
        <a href="{{ route('profile.show') }}"
           class="px-md py-sm text-label-md font-semibold border-b-2 border-transparent text-on-surface-variant hover:text-primary whitespace-nowrap transition-colors">
            Thông tin cá nhân
        </a>
        <a href="{{ route('profile.change-password') }}"
           class="px-md py-sm text-label-md font-semibold border-b-2 border-primary text-primary whitespace-nowrap">
            Đổi mật khẩu
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-soft p-md md:p-lg">
        <h2 class="text-headline-md text-on-surface mb-md">Đổi mật khẩu</h2>

        <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-md max-w-[480px]">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Mật khẩu hiện tại <span class="text-error">*</span></label>
                <input type="password" name="current_password" required
                    class="w-full px-md py-sm rounded-xl border border-outline-variant bg-surface-container text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all @error('current_password') border-error @enderror">
                @error('current_password') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Mật khẩu mới <span class="text-error">*</span></label>
                <input type="password" name="password" required
                    class="w-full px-md py-sm rounded-xl border border-outline-variant bg-surface-container text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all @error('password') border-error @enderror">
                <p class="text-label-sm text-on-surface-variant mt-xs">Tối thiểu 8 ký tự.</p>
                @error('password') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Xác nhận mật khẩu mới <span class="text-error">*</span></label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-md py-sm rounded-xl border border-outline-variant bg-surface-container text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all">
            </div>

            <div class="pt-sm">
                <button type="submit"
                    class="px-lg py-sm rounded-full bg-primary text-on-primary text-label-md font-bold hover:opacity-90 active:scale-95 transition-all">
                    Cập nhật mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
