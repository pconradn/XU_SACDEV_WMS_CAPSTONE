<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

@php
    $actorLabel = 'Project Head';

    if ($roles->contains('treasurer')) {
        $actorLabel = 'Treasurer';
    } elseif ($roles->contains('moderator')) {
        $actorLabel = 'Moderator';
    } elseif ($roles->contains('finance_officer')) {
        $actorLabel = 'Budget and Finance Officer';
    } elseif ($roles->contains('president')) {
        $actorLabel = 'President';
    }
@endphp

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b flex items-center justify-between">

        <div class="flex items-center gap-2">
            <i data-lucide="check-square" class="w-4 h-4 text-slate-400"></i>

            <h3 class="text-sm font-semibold text-slate-900">
                Your Tasks
            </h3>

            @if(($pendingCount ?? 0) > 0)
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 font-semibold">
                    {{ $pendingCount }} pending
                </span>
            @endif
        </div>

        @if(($pendingCount ?? 0) > 0)
            <span class="text-xs text-slate-400">
                Needs attention
            </span>
        @endif

    </div>


    {{-- LIST --}}
    <div class="divide-y max-h-[420px] overflow-y-auto">

        @forelse($pendingTasks as $task)

            @php
                $project = $task->project ?? null;

                $isApproval = $task->category === 'approval';
                $isRereg = $task->category === 'rereg';

                $pending = $isApproval ? $task->currentPendingSignature() : null;

                $color = $isApproval
                    ? 'rose'
                    : (($task->state ?? null) === 'revision' ? 'amber' : 'blue');
            @endphp


            <div class="px-5 py-4 flex items-center justify-between hover:bg-slate-50 transition">

                {{-- LEFT CONTENT --}}
                <div class="flex items-start gap-3">

                    {{-- LEFT INDICATOR --}}
                    <div class="mt-1 w-1.5 h-10 rounded-full 
                        @if($color === 'rose') bg-rose-400
                        @elseif($color === 'amber') bg-amber-400
                        @else bg-blue-400
                        @endif
                    "></div>

                    <div class="space-y-1">

                        {{-- NAME --}}
                        <p class="text-sm font-semibold text-slate-900">
                            {{ $task->category === 'approval'
                                ? $task->formType->name
                                : $task->form_name }}
                        </p>

                        {{-- PROJECT --}}
                        <p class="text-xs text-slate-500">
                            @if($isRereg)
                                Re-registration
                            @else
                                Project: {{ $project->title ?? '—' }}
                            @endif
                        </p>

                        {{-- STATUS --}}
                        <div class="flex items-center gap-2 flex-wrap">

                            @if($task->category === 'approval')

                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full ring-1 {{ $task->status_badge_class }}">
                                    {{ $task->status_label }}
                                </span>

                                <span class="text-[10px] font-semibold text-rose-700">
                                    Awaiting approval
                                </span>

                            @elseif(($task->state ?? null) === 'revision')

                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full bg-amber-100 text-amber-700">
                                    Returned
                                </span>

                                <span class="text-[10px] font-semibold text-amber-700">
                                    Needs revision
                                </span>

                            @else

                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full bg-blue-100 text-blue-700">
                                    Required
                                </span>

                                <span class="text-[10px] font-semibold text-blue-700">
                                    Action needed ({{ $actorLabel }})
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="shrink-0">
                    <a href="{{ $task->link }}"
                       class="inline-flex items-center gap-1 text-[11px] px-3 py-2 rounded-lg font-semibold text-white transition
                       {{ $task->category === 'approval'
                            ? 'bg-rose-600 hover:bg-rose-700'
                            : (($task->state ?? null) === 'revision'
                                ? 'bg-amber-600 hover:bg-amber-700'
                                : 'bg-blue-600 hover:bg-blue-700') }}">
                        
                        @if($task->category === 'approval')
                            Review
                        @elseif(($task->state ?? null) === 'revision')
                            Fix
                        @else
                            Complete
                        @endif
                    </a>
                </div>

            </div>

        @empty

            <div class="px-5 py-8 text-center">

                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-400 mx-auto mb-2"></i>

                <div class="text-sm font-semibold text-slate-700">
                    You're all caught up
                </div>

                <div class="text-xs text-slate-500">
                    No pending tasks at the moment.
                </div>

            </div>

        @endforelse

    </div>

</div>