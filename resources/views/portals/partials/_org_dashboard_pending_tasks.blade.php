<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
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
            <h3 class="text-sm font-semibold text-slate-900">
                Your Tasks
            </h3>

            @if(($pendingCount ?? 0) > 0)
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold">
                    {{ $pendingCount }} pending
                </span>
            @endif
        </div>

        @if(($pendingCount ?? 0) > 0)
            <span class="text-xs text-slate-400">
                Requires your attention
            </span>
        @endif

    </div>

    <div class="divide-y max-h-[400px] overflow-y-auto">

        @forelse($pendingTasks as $task)

            @php
                $project = $task->project ?? null;

                $isApproval = $task->type === 'approval';
                $isRereg = str_starts_with($task->type, 'rereg');

                $pending = $isApproval ? $task->currentPendingSignature() : null;
            @endphp


            <div class="px-5 py-4 flex items-center justify-between 
                {{ 
                    $task->type === 'approval' 
                        ? 'bg-red-50/40' 
                        : ($task->type === 'revision' 
                            ? 'bg-orange-50/40' 
                            : 'bg-amber-50/40') 
                }}">

                <div class="space-y-1">

                    {{-- NAME --}}
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $task->type === 'approval'
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

                        @if($task->type === 'approval')

                            <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full ring-1 {{ $task->status_badge_class }}">
                                {{ $task->status_label }}
                            </span>

                            <span class="text-xs font-semibold text-red-700">
                                • Awaiting your approval
                            </span>
                        @elseif($task->type === 'revision')

                            <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full bg-orange-100 text-orange-700">
                                Returned
                            </span>

                            <span class="text-xs font-semibold text-orange-700">
                                • Needs revision
                            </span>

                        @else

                            <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full bg-amber-100 text-amber-700">
                                Required
                            </span>

                            <span class="text-xs font-semibold text-amber-700">
                                • Action needed ({{ $actorLabel }})
                            </span>

                        @endif

                    </div>

                </div>

                {{-- ACTION --}}
                <div>
                    <a href="{{ $task->link }}"
                    class="text-xs px-3 py-2 
                    {{ $task->type === 'approval'
                            ? 'bg-red-600 hover:bg-red-700'
                            : 'bg-amber-600 hover:bg-amber-700' }}
                    text-white rounded-md">
                        {{ $task->type === 'approval' ? 'Review' : 'Complete' }}
                    </a>
                </div>

            </div>

        @empty

            <div class="px-5 py-6 text-sm text-slate-500 text-center">
                No pending tasks — you're all caught up!
            </div>

        @endforelse

    </div>

</div>