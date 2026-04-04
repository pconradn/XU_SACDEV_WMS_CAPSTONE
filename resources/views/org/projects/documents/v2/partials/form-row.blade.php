@php
    $approvalOrder = [
        'project_head',
        'treasurer',
        'finance_officer',
        'president',
        'moderator',
        'sacdev_admin',
    ];

    $signatures = collect($form['document']->signatures ?? [])
        ->sortBy(fn($sig) => array_search($sig->role, $approvalOrder));

    $status = strtolower($form['status_label'] ?? '');

    $isCompleted = $status === 'approved by sacdev' || $status === 'approved_by_sacdev';
    $isReturned = str_contains($status, 'returned');
    $isPending = str_contains($status, 'pending') || str_contains($status, 'submitted');

    $rowStateClass = match (true) {
        $isCompleted => 'border-l-emerald-500 bg-emerald-50/30',
        $isReturned => 'border-l-rose-500 bg-rose-50/40',
        $isPending => 'border-l-indigo-500 bg-indigo-50/30',
        default => '',
    };
@endphp

<div 
    x-data="{ open: false }"
    class="border bg-white overflow-hidden rounded-xl border-slate-200
        {{ ($form['is_required'] ?? false) ? 'border-l-4 border-l-blue-500 bg-blue-50/20' : '' }}
        {{ $rowStateClass }}
">

    {{-- MAIN ROW --}}
    <div 
        @click="open = !open"
        class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-slate-50 transition"
    >

        {{-- LEFT --}}
        <div class="flex items-start gap-2 min-w-0">

            {{-- STATUS DOT --}}
            <div class="w-2 h-2 mt-1 rounded-full
                {{ $isCompleted ? 'bg-emerald-500' : '' }}
                {{ $isPending ? 'bg-indigo-500' : '' }}
                {{ $isReturned ? 'bg-rose-500' : '' }}
                {{ (!$isCompleted && !$isPending && !$isReturned) ? 'bg-slate-400' : '' }}
            "></div>

            <div class="min-w-0">

                {{-- TITLE --}}
                <div class="text-sm font-medium text-slate-800 truncate flex items-center gap-2">

                    {{ $form['name'] }}


                </div>

                {{-- META --}}
                <div class="text-[10px] text-slate-500 flex flex-wrap gap-2 items-center">

                    {{-- PHASE --}}
                    <span class="text-slate-400">
                        {{ ucfirst(str_replace('_',' ', $form['phase'])) }}
                    </span>

                    @if($form['waiting_for'])
                        <span>•</span>
                        <span>Waiting: {{ $form['waiting_for'] }}</span>
                    @endif

                    @if(optional($form['document'])->updated_at)
                        <span>•</span>
                        <span>
                            {{ \Carbon\Carbon::parse($form['document']->updated_at)->format('M d') }}
                        </span>
                    @endif

                    {{-- ACTION --}}
                    @if($isReturned || ($form['is_action_required'] ?? false))
                        <span class="text-amber-600 font-semibold">
                            • Action required
                        </span>
                    @endif

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="flex items-center gap-2 shrink-0">

            {{-- APPROVAL DOTS --}}
            @if($signatures->isNotEmpty())
                <div class="hidden md:flex gap-1">
                    @foreach($signatures as $sig)
                        <div class="w-2 h-2 rounded-full
                            {{ $sig->status === 'signed'
                                ? 'bg-emerald-500'
                                : 'bg-slate-300' }}">
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- OPEN --}}
            @if($form['view_url'])
                <a href="{{ $form['view_url'] }}"
                   class="text-[10px] px-2 py-1 bg-slate-800 text-white rounded hover:bg-slate-700">
                    Open
                </a>
            @endif

        </div>
    </div>

    {{-- EXPANDED --}}
    <div x-show="open" x-collapse class="border-t bg-slate-50 px-3 py-3 space-y-3">

        {{-- STATUS INFO --}}
        <div class="text-[11px] text-slate-600 flex flex-wrap gap-2">

            <span>
                Status:
                <span class="font-medium text-slate-800">
                    {{ $form['status_label'] }}
                </span>
            </span>

            <span>•</span>

            <span>
                Phase:
                <span class="font-medium text-slate-700">
                    {{ ucfirst(str_replace('_',' ', $form['phase'])) }}
                </span>
            </span>

            @if($form['is_required'] ?? false)
                <span>•</span>
                <span class="text-blue-700 font-semibold">
                    Required for workflow progress
                </span>
            @endif

        </div>

        {{-- ACTIONS --}}
  

        {{-- REMARKS --}}
        @if(optional($form['document'])->remarks)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 text-[11px] px-3 py-2 rounded-lg">
                {{ $form['document']->remarks }}
            </div>
        @endif

        {{-- APPROVAL FLOW --}}
        @if($signatures->isNotEmpty())
            <div class="text-[11px] text-slate-500">

                <div class="mb-1 font-medium text-slate-600">
                    Approval Flow
                </div>

                <div class="flex flex-wrap gap-1">
                    @foreach($signatures as $sig)
                        <span class="px-2 py-0.5 rounded-full text-[10px]
                            {{ $sig->status === 'signed'
                                ? 'bg-emerald-100 text-emerald-700'
                                : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst(str_replace('_',' ', $sig->role)) }}
                        </span>
                    @endforeach
                </div>

            </div>
        @endif

    </div>

</div>