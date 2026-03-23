<div class="bg-white border rounded-2xl p-6 shadow-sm flex justify-between items-start">

    {{-- LEFT --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            {{ $header['title'] }}
        </h1>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-2">

        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full ring-1 {{ $header['status_class'] }}">
            {{ $header['status_label'] }}
        </span>

    </div>

</div>