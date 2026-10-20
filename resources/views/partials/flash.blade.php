@if(session('success') || session('error') || $errors->any())
    <div id="app-toast" class="pointer-events-none fixed inset-x-4 z-[60] md:left-auto md:right-6 md:w-[380px] {{ ! empty($liftStickyCart) ? 'bottom-40' : (! empty($showStickyCart) ? 'bottom-24' : 'bottom-4') }}">
        @if(session('success'))
            <div class="pointer-events-auto mb-sm flex items-start gap-sm rounded-xl bg-secondary-container px-md py-sm text-on-secondary-container shadow-soft">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="text-body-md flex-grow">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="pointer-events-auto mb-sm flex items-start gap-sm rounded-xl bg-error-container px-md py-sm text-on-error-container shadow-soft">
                <span class="material-symbols-outlined">error</span>
                <p class="text-body-md flex-grow">{{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="pointer-events-auto flex items-start gap-sm rounded-xl bg-error-container px-md py-sm text-on-error-container shadow-soft">
                <span class="material-symbols-outlined">warning</span>
                <ul class="text-body-md flex-grow list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <script>
        setTimeout(function () {
            var toast = document.getElementById('app-toast');
            if (toast) toast.remove();
        }, 4500);
    </script>
@endif
