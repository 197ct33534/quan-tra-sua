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
        <div class="flex justify-between items-center">
            <span class="text-headline-md text-primary">@vnd($product->price)</span>
            <a href="{{ route('products.show', $product->slug) }}"
               class="bg-secondary-container text-secondary w-10 h-10 rounded-full flex items-center justify-center hover:bg-secondary hover:text-on-secondary transition-all active:scale-90">
                <span class="material-symbols-outlined">add</span>
            </a>
        </div>
    </div>
</div>
