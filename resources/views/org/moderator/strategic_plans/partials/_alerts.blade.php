@if(session('success'))
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
        <div class="font-semibold">Success</div>
        <div class="mt-1">{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 text-sm">
        <div class="font-semibold">Action blocked</div>
        <div class="mt-1">{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900 text-sm">
        <div class="font-semibold mb-1">Please fix the following:</div>
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
