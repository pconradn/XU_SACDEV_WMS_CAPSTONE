@php
    $orderedSignatures = collect();

    if (!empty($form['document']) && $form['document']->signatures) {
        $orderedSignatures = collect($form['document']->signatures)->sortBy(function ($sig) {
            return match($sig->role) {
                'project_head' => 1,
                'treasurer' => 2,
                'president' => 3,
                'moderator' => 4,
                'sacdev_admin' => 5,
                default => 99,
            };
        });
    }
@endphp



<div 
    x-data="{ open: false }"
    class="border border-slate-200 bg-white overflow-hidden rounded-xl
        {{ ($form['is_required'] ?? false) ? 'border-l-4 border-l-emerald-500' : '' }}
        {{ 
            str_contains(strtolower($form['status_label']), 'returned') ||
            str_contains(strtolower($form['status_label']), 'pending') ||
            str_contains(strtolower($form['status_label']), 'action')
            ? 'ring-1 ring-rose-300 bg-rose-50/40'
            : ''
        }}
">

    {{-- MAIN ROW --}}
    <div 
        @click="open = !open"
        class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-slate-50 transition"
    >

        {{-- LEFT --}}
        <div class="flex items-start gap-2 min-w-0">

            {{-- STATUS DOT --}}
            <div class="w-2 h-2 mt-1 rounded-full shrink-0
                {{ str_contains($form['status_class'], 'emerald') ? 'bg-emerald-500' : '' }}
                {{ str_contains($form['status_class'], 'blue') ? 'bg-blue-500' : '' }}
                {{ str_contains($form['status_class'], 'rose') ? 'bg-rose-500' : '' }}
                {{ str_contains($form['status_class'], 'slate') ? 'bg-slate-400' : '' }}
            "></div>

            <div class="min-w-0">

                {{-- TITLE --}}
                <div class="text-sm font-medium text-slate-800 truncate flex items-center gap-2">

                    {{ $form['name'] }}

                    {{-- REQUIRED BADGE --}}
                    @if($form['is_required'] ?? false)
                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-medium">
                            Required
                        </span>
                    @endif

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
                    @if(
                        str_contains(strtolower($form['status_label']), 'returned') ||
                        str_contains(strtolower($form['status_label']), 'pending')
                    )
                        <span class="text-rose-600 font-medium">
                            • Action required
                        </span>
                    @endif

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="flex items-center gap-2 shrink-0">

            {{-- MINI APPROVAL PROGRESS --}}
            @if($form['document'] && $form['document']->signatures)
                <div class="hidden md:flex gap-1">
                    @foreach($orderedSignatures as $sig)
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
                <span class="text-emerald-700 font-medium">
                    Required for workflow completion
                </span>
            @endif

        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-wrap gap-2">

            @if($form['can_create'])
                <a href="{{ $form['create_url'] }}"
                class="px-2.5 py-1 text-[10px] rounded bg-blue-600 text-white hover:bg-blue-700">
                    Create
                </a>
            @endif

            @if($form['can_review'])
                <a href="{{ $form['view_url'] }}"
                class="px-2.5 py-1 text-[10px] rounded bg-emerald-600 text-white hover:bg-emerald-700">
                    Review
                </a>
            @endif

            @php
                $isNotice = in_array($form['code'] ?? '', [
                    'POSTPONEMENT_NOTICE',
                    'CANCELLATION_NOTICE'
                ]);

                $doc = $form['document'] ?? null;
            @endphp

            @if(
                $isNotice &&
                $doc &&
                $doc->status === 'draft'
            )
                <form method="POST"
                    action="{{ route('org.projects.documents.notices.archive', [$project, $doc]) }}"
                    onsubmit="return confirm('This will remove this draft notice. Continue?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="px-2.5 py-1 text-[10px] rounded
                            bg-rose-600 text-white hover:bg-rose-700">
                        Cancel Draft
                    </button>
                </form>
            @endif

        </div>

        {{-- REMARKS --}}
        @if(optional($form['document'])->remarks)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 text-[11px] px-3 py-2 rounded-lg">
                {{ $form['document']->remarks }}
            </div>
        @endif

        {{-- SIGNATURE PROGRESS --}}
        @if($orderedSignatures->isNotEmpty())
            <div class="text-[11px] text-slate-500">

                <div class="mb-1 font-medium text-slate-600">
                    Approval Progress
                </div>

                <div class="flex flex-wrap gap-1">
                    @foreach($orderedSignatures as $sig)
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