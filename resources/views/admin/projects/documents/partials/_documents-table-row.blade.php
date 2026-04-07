@php
    $docId = optional($form['document'])->id;
@endphp

<div 
    class="px-5 py-3 flex items-center justify-between hover:bg-slate-50 transition
    {{ $docId && $docId == $focusDocId ? 'ring-2 ring-blue-500 rounded-xl bg-blue-50' : '' }}"
    
    @if($docId && $docId == $focusDocId)
        data-doc-focus="true"
    @endif
>

  
    <div class="min-w-0">

        <div class="text-sm font-medium text-slate-800">
            {{ $form['name'] }}
        </div>

        <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">

      
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $form['status_class'] }}">
                {{ $form['status_label'] }}
            </span>

     
            @if($form['waiting_for'])
                <span class="text-slate-400">
                    → {{ str_replace('_',' ', $form['waiting_for']) }}
                </span>
            @endif

        </div>

    </div>


    {{-- RIGHT ACTIONS --}}
    <div class="flex items-center gap-2 shrink-0">

        {{-- VIEW --}}
        @if($form['view_url'])
            <a href="{{ $form['view_url'] }}"
               class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 hover:bg-slate-100 transition">
                View
            </a>
        @endif

        {{-- PRINT --}}
        @if($form['print_url'])
            <a href="{{ $form['print_url'] }}"
               class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                Print
            </a>
        @endif

        {{-- ACTION REQUIRED --}}
        @if($form['is_pending_for_me'])
            <span class="px-2 py-1 text-[10px] rounded-full bg-rose-100 text-rose-700 font-semibold">
                Needs Action
            </span>
        @endif

    </div>

</div>