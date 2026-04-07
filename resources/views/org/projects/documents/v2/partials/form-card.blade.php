@php
    $doc = $form['document'] ?? null;
    $status = $doc?->status ?? 'draft';
    $currentApprover = $doc?->currentPendingSignature();
@endphp

<div class="border rounded-xl p-4 hover:shadow-md transition bg-white">

    <div class="space-y-2">

        {{-- TITLE --}}
        <div class="flex justify-between items-start gap-2">

            <div class="text-sm font-semibold text-slate-800">
                {{ $form['name'] }}
            </div>

            {{-- BADGES --}}
            @if($type === 'required')
                <span class="text-[10px] px-2 py-0.5 bg-red-100 text-red-700 rounded-full">
                    REQUIRED
                </span>
            @elseif($type === 'optional')
                <span class="text-[10px] px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full">
                    OPTIONAL
                </span>
            @endif

        </div>

        {{-- STATUS --}}
        <div class="text-xs font-medium
            {{ $status === 'approved_by_sacdev' ? 'text-emerald-700' : '' }}
            {{ $status === 'submitted' ? 'text-blue-700' : '' }}
            {{ $status === 'draft' ? 'text-slate-600' : '' }}
            {{ $status === 'returned' ? 'text-rose-700' : '' }}
        ">
            {{ strtoupper(str_replace('_',' ', $status)) }}
        </div>

        {{-- INFO --}}
        <div class="text-xs text-slate-500">

            @if($status === 'approved_by_sacdev')
                Completed

            @elseif($status === 'submitted' && $currentApprover)
                Awaiting {{ str_replace('_',' ', $currentApprover->role) }}

            @elseif($status === 'draft')
                Not yet started

            @elseif($status === 'returned')
                Needs revision

            @endif

        </div>

        {{-- ACTION --}}
        <div class="pt-2">

            @if($form['can_create'] && $form['create_url'])
                <a href="{{ $form['create_url'] }}"
                   class="text-xs px-3 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Start
                </a>

            @elseif($form['can_edit'] && $form['edit_url'])
                <a href="{{ $form['edit_url'] }}"
                   class="text-xs px-3 py-1.5 bg-amber-100 text-amber-800 rounded-md hover:bg-amber-200">
                    Continue
                </a>

            @elseif($form['can_review'] && $form['view_url'])
                <a href="{{ $form['view_url'] }}"
                   class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Review
                </a>

            @elseif($form['document'] && $form['view_url'])
                <a href="{{ $form['view_url'] }}"
                   class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-md hover:bg-slate-300">
                    View
                </a>
            @endif

        </div>

    </div>

</div>

