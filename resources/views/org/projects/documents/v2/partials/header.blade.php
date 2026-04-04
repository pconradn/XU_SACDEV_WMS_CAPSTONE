<div class="bg-white border rounded-xl px-4 py-3 shadow-sm flex flex-col lg:flex-row justify-between gap-3">

    {{-- LEFT --}}
    <div>
        <h1 class="text-base font-semibold text-slate-800 leading-tight">
            {{ $header['title'] }}
        </h1>

        <p class="text-[11px] text-slate-500 mt-0.5">
            {{ $header['org'] ?? '—' }} • {{ $header['school_year'] ?? '' }}
        </p>

        <p class="text-[11px] text-slate-600 mt-1">
            Project Head:
            <span class="font-medium text-slate-800">
                {{ $header['project_head'] ?? '—' }}
            </span>
        </p>
    </div>

    {{-- RIGHT --}}
    <div class="flex flex-col items-start lg:items-end gap-2">

        {{-- STATUS --}}
        <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $header['status_class'] }}">
            {{ $header['status_label'] }}
        </span>



    </div>

</div>