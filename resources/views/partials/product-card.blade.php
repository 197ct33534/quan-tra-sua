<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-soft card-hover group flex flex-col">
    <a href="{{ route('products.show', $product->slug) }}" class="block h-52 overflow-hidden relative">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
        @if($product->has_options)
            <span class="absolute top-sm right-sm bg-primary-container text-on-primary-container px-sm py-xs rounded-full text-label-sm">Tùy chọn</span>
        @endif
    </a>
    <div class="p-md flex flex-col flex-grow">
        <h3 class="text-headline-sm text-on-surface mb-xs">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-primary transition-colors">{{ $product->name }}</a>
        </h3>
        <p class="text-body-md text-on-surface-variant mb-md flex-grow line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
        <div class="mt-auto flex flex-col gap-sm">
            <span class="text-headline-sm text-primary">@vnd($product->price)</span>
            <a href="{{ route('products.show', $product->slug) }}"
               class="inline-flex min-h-11 w-full items-center justify-center rounded-full bg-primary px-md text-label-md font-bold text-on-primary hover:opacity-90 active:scale-[0.98] transition-all">
                {{ $product->has_options ? 'Chọn món' : 'Thêm' }}
            </a>
        </div>
    </div>
</div>
