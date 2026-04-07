@php
    $classes = [
        'pre_implementation' => 'bg-emerald-100 text-emerald-700',
        'off-campus' => 'bg-purple-100 text-purple-700',
        'post_implementation' => 'bg-blue-100 text-blue-700',
        'notice' => 'bg-rose-100 text-rose-700',
        'completion' => 'bg-emerald-100 text-emerald-800', // NEW
        'default' => 'bg-amber-100 text-amber-700',
    ];
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">

        <div class="flex items-center gap-2">
            {{-- Lucide --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M9 11l3 3L22 4"/>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>

            <div>
                <div class="text-xs font-semibold text-slate-900">
                    Project Approvals
                </div>
                <div class="text-[10px] text-slate-500">
                    Pending approvals and completion-ready projects
                </div>
            </div>
        </div>

        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
            {{ $projectApprovals->count() }}
        </span>

    </div>

    {{-- LIST --}}
    <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto">

        @forelse($projectApprovals as $task)

            @php
                $isCompletion = $task->is_completion ?? false;
            @endphp

            <a href="{{ $task->form_route   }}"
               class="block px-4 py-3 transition
                      {{ $isCompletion ? 'bg-emerald-50 hover:bg-emerald-100/50' : 'hover:bg-slate-50' }}">

                {{-- TOP --}}
                <div class="flex items-start justify-between gap-2">

                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-slate-900 truncate">
                            {{ $task->project->title }}
                        </div>

                        <div class="text-[10px] text-slate-500">
                            {{ $task->organization->name ?? '' }}
                        </div>
                    </div>

                    {{-- RIGHT BADGE --}}
                    @if($isCompletion)
                        <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                            Ready
                        </span>
                    @elseif($task->count > 1)
                        <span class="text-[9px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-semibold">
                            {{ $task->count }} pending
                        </span>
                    @endif

                </div>

                {{-- META --}}
                <div class="flex items-center justify-between mt-1">

                    <div class="text-[10px] text-slate-400">
                        {{ optional($task->project->implementation_start_date)->format('M d, Y') }}
                    </div>

                    <div class="text-slate-300">
                        →
                    </div>

                </div>

                {{-- FORMS --}}
                <div class="flex flex-wrap gap-1 mt-2">

                    @foreach($task->forms as $form)
                        <span class="text-[9px] px-2 py-0.5 rounded-full font-semibold
                            {{ $classes[$form['phase']] ?? $classes['default'] }}">
                            {{ $form['name'] }}
                        </span>
                    @endforeach

                </div>

            </a>

        @empty
            <div class="px-4 py-3 text-xs text-slate-500">
                No pending approvals
            </div>
        @endforelse

    </div>

</div>