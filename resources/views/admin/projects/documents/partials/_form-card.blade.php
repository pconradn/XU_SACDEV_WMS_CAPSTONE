@php
    $isUrgent = $form['is_pending_for_me'];
    $isPending = $form['is_pending'];
    $isApproved = $form['is_approved'];
@endphp

<div class="rounded-2xl border 
    {{ $isUrgent ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-white' }}
    p-5 shadow-sm hover:shadow-md transition">

<div class="flex justify-between gap-4">

    {{-- LEFT --}}
    <div class="min-w-0 space-y-2">

        {{-- NAME --}}
        <div class="text-sm font-semibold text-slate-900 break-words leading-snug">
            {{ $form['name'] }}
        </div>

        {{-- STATUS + ACTION --}}
        <div class="flex items-center gap-2 flex-wrap">

            {{-- STATUS --}}
            <span class="px-2 py-1 text-xs font-medium rounded {{ $form['status_class'] }}">
                {{ $form['status_label'] }}
            </span>

            {{-- ACTION REQUIRED --}}
            @if($isUrgent)
                <span class="px-2 py-0.5 text-[10px] rounded-full bg-amber-200 text-amber-900 font-semibold">
                    Action Required
                </span>
            @endif

        </div>

        {{-- AWAITING --}}
        @if($isPending)
            <div class="text-xs text-slate-600 leading-snug">

                Awaiting 
                @php
                    $roleLabels = [
                        'president' => 'President',
                        'project_head' => 'Project Head',
                        'treasurer' => 'Treasurer',
                        'finance_officer' => 'Budget and Finance Officer',
                        'moderator' => 'Moderator',
                        'sacdev_admin' => 'SACDEV',
                        'osa_admin' => 'OSA',
                        'auditor' => 'Auditor',
                    ];
                @endphp

                <span class="font-semibold text-slate-800">
                    {{ $roleLabels[$form['waiting_for']] ?? strtoupper(str_replace('_',' ', $form['waiting_for'])) }}
                </span>

                @if($form['pending_user_name'])
                    <div class="text-slate-500 mt-0.5">
                        {{ $form['pending_user_name'] }}
                    </div>
                @endif

            </div>
        @endif

        {{-- APPROVED --}}
        @if($isApproved)
            <div class="text-xs text-emerald-600 font-medium">
                Approved
            </div>
        @endif

    </div>

    {{-- RIGHT ACTIONS --}}
    <div class="flex flex-col items-end gap-2 shrink-0">

        {{-- REVIEW --}}
        @if($form['view_url'])
            <a href="{{ $form['view_url'] }}"
               class="px-3 py-1.5 text-xs font-medium rounded-lg 
               {{ $isUrgent 
                    ? 'text-amber-700 bg-amber-100 hover:bg-amber-200' 
                    : 'text-blue-700 bg-blue-50 hover:bg-blue-100' }}">
                {{ $isUrgent ? 'Review' : 'Open' }}
            </a>
        @endif

        {{-- PRINT --}}
        @if($form['print_url'])
            <a href="{{ $form['print_url'] }}"
               target="_blank"
               class="px-3 py-1.5 text-xs font-medium rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200">
                Print
            </a>
        @endif

    </div>

</div>

</div>