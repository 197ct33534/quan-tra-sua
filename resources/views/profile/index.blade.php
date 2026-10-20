@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="max-w-[800px] mx-auto px-md md:px-lg py-lg">
    <div class="mb-lg">
        <h1 class="text-headline-xl text-on-surface">Hồ sơ cá nhân</h1>
        <p class="text-body-md text-on-surface-variant mt-xs">Quản lý thông tin tài khoản của bạn.</p>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-xs border-b border-outline-variant/30 mb-lg overflow-x-auto hide-scrollbar">
        <a href="{{ route('profile.show') }}"
           class="px-md py-sm text-label-md font-semibold border-b-2 border-primary text-primary whitespace-nowrap">
            Thông tin cá nhân
        </a>
        <a href="{{ route('profile.change-password') }}"
           class="px-md py-sm text-label-md font-semibold border-b-2 border-transparent text-on-surface-variant hover:text-primary whitespace-nowrap transition-colors">
            Đổi mật khẩu
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-xl shadow-soft p-md md:p-lg">
        <div class="flex items-center gap-md mb-lg">
            <div class="w-20 h-20 rounded-full bg-primary-container flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary" style="font-size:40px">account_circle</span>
            </div>
            <div>
                <p class="text-headline-md text-on-surface">{{ $user->name }}</p>
                <p class="text-body-md text-on-surface-variant">{{ $user->email }}</p>
                <span class="text-label-sm px-sm py-xs rounded-full mt-xs inline-block
                    {{ $user->isAdmin() ? 'bg-primary-container text-on-primary-container' : 'bg-secondary-container text-on-secondary-container' }}">
                    {{ $user->isAdmin() ? 'Admin' : 'Khách hàng' }}
                </span>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-md">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Họ và tên <span class="text-error">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-md py-sm rounded-xl border border-outline-variant bg-surface-container text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all @error('name') border-error @enderror">
                @error('name') <p class="text-error text-label-sm mt-xs">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                    class="w-full px-md py-sm rounded-xl border border-outline-variant/50 bg-surface-container-high text-on-surface-variant text-body-md cursor-not-allowed">
                <p class="text-label-sm text-on-surface-variant mt-xs">Email không thể thay đổi.</p>
            </div>

            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-md py-sm rounded-xl border border-outline-variant bg-surface-container text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all"
                    placeholder="VD: 0901 234 567">
            </div>

            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Địa chỉ giao hàng</label>
                <textarea name="address" rows="3"
                    class="w-full px-md py-sm rounded-xl border border-outline-variant bg-surface-container text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 text-body-md transition-all resize-none"
                    placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="flex gap-sm pt-sm">
                <button type="submit"
                    class="px-lg py-sm rounded-full bg-primary text-on-primary text-label-md font-bold hover:opacity-90 active:scale-95 transition-all">
                    Lưu thay đổi
                </button>
                <a href="{{ route('orders.index') }}"
                    class="px-lg py-sm rounded-full bg-surface-container-high text-on-surface-variant text-label-md font-bold hover:bg-surface-container-highest transition-all">
                    Xem đơn hàng
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
