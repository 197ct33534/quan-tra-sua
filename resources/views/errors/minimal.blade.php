@extends('layouts.app')

@section('title', $code . ' — ' . $title)

@section('content')
<section class="max-w-[1200px] mx-auto px-md md:px-lg py-xl">
    <div class="flex flex-col items-center text-center gap-md py-xl">
        <div class="w-28 h-28 rounded-full bg-primary-container/40 flex items-center justify-center">
            <span class="material-symbols-outlined text-primary" style="font-size:64px">{{ $icon ?? 'sentiment_dissatisfied' }}</span>
        </div>
        <p class="text-headline-xl text-primary">{{ $code }}</p>
        <h1 class="text-headline-md text-on-surface">{{ $title }}</h1>
        <p class="text-body-md text-on-surface-variant max-w-[480px]">{{ $message }}</p>
        <div class="flex flex-wrap gap-sm justify-center mt-base">
            <a href="{{ route('home') }}" class="px-md py-sm rounded-full bg-primary text-on-primary text-label-md font-bold hover:opacity-90 active:scale-95 transition-all flex items-center gap-xs">
                <span class="material-symbols-outlined text-[20px]">home</span> Về trang chủ
            </a>
            <a href="{{ route('menu') }}" class="px-md py-sm rounded-full bg-primary-container text-on-primary-container text-label-md font-bold hover:opacity-90 active:scale-95 transition-all flex items-center gap-xs">
                <span class="material-symbols-outlined text-[20px]">local_cafe</span> Xem thực đơn
            </a>
        </div>
    </div>
</section>
@endsection
