@php
    $docId = optional($form['document'])->id;
@endphp

<div 
    class="group px-4 py-3 flex items-center justify-between gap-3
           hover:bg-slate-50 transition
           {{ $docId && $docId == $focusDocId ? 'ring-2 ring-blue-500 rounded-xl bg-blue-50' : '' }}"

    @if($docId && $docId == $focusDocId)
        data-doc-focus="true"
    @endif
>

    {{-- LEFT --}}
    <div class="min-w-0 space-y-1">

        <div class="text-sm font-medium text-slate-800 truncate">
            {{ $form['name'] }}
        </div>

        <div class="flex flex-wrap items-center gap-2 text-[11px] text-slate-500">

            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $form['status_class'] }}">
                {{ $form['status_label'] }}
            </span>

            @if($form['waiting_for'])
                <span class="text-slate-400">
                    • {{ str_replace('_',' ', $form['waiting_for']) }}
                </span>
            @endif

            @if($form['is_pending_for_me'])
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700">
                    Needs Action
                </span>
            @endif

        </div>

    </div>


    {{-- RIGHT --}}
    <div class="flex items-center gap-2 shrink-0">

        @if($form['view_url'])
            <a href="{{ $form['view_url'] }}"
               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                      border border-slate-200 bg-white text-slate-700
                      hover:bg-slate-100 transition">
                <i data-lucide="eye" class="w-3 h-3"></i>
                View
            </a>
        @endif

        @if($form['print_url'])
            <a href="{{ $form['print_url'] }}"
               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                      border border-blue-200 bg-blue-50 text-blue-700
                      hover:bg-blue-100 transition">
                <i data-lucide="printer" class="w-3 h-3"></i>
                Print
            </a>
        @endif

    </div>

</div>