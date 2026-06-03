@if(session('success'))
    <div class="mt-md flex items-start gap-sm bg-secondary-container text-on-secondary-container px-md py-sm rounded-xl shadow-soft">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="text-body-md flex-grow">{{ session('success') }}</p>
    </div>
@endif
@if(session('error'))
    <div class="mt-md flex items-start gap-sm bg-error-container text-on-error-container px-md py-sm rounded-xl shadow-soft">
        <span class="material-symbols-outlined">error</span>
        <p class="text-body-md flex-grow">{{ session('error') }}</p>
    </div>
@endif
@if($errors->any())
    <div class="mt-md flex items-start gap-sm bg-error-container text-on-error-container px-md py-sm rounded-xl shadow-soft">
        <span class="material-symbols-outlined">warning</span>
        <ul class="text-body-md flex-grow list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
