@if(session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
        <div class="font-semibold">Success</div>
        <div class="text-sm mt-1">{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
        <div class="font-semibold">Error</div>
        <div class="text-sm mt-1">{{ session('error') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
        <div class="font-semibold">Please fix the errors below.</div>
        <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif