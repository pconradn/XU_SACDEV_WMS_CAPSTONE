<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

@php
    $reregTasks = $pendingTasks->where('category', 'rereg');
    $projectTasks = $pendingTasks->where('category', '!=', 'rereg')->groupBy(fn($t) => $t->project->id ?? 'none');

    $actorLabel = 'Project Head';

    if ($roles->contains('treasurer')) $actorLabel = 'Treasurer';
    elseif ($roles->contains('moderator')) $actorLabel = 'Moderator';
    elseif ($roles->contains('finance_officer')) $actorLabel = 'Finance Officer';
    elseif ($roles->contains('president')) $actorLabel = 'President';
@endphp

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b flex items-center justify-between bg-slate-50 rounded-t-2xl">
        <div class="flex items-center gap-2">
            <i data-lucide="list-checks" class="w-4 h-4 text-slate-500"></i>

            <h3 class="text-sm font-semibold text-slate-900">
                Pending Tasks
            </h3>

            @if(($pendingCount ?? 0) > 0)
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 font-semibold">
                    {{ $pendingCount }}
                </span>
            @endif
        </div>

        <span class="text-xs text-slate-400">
            {{ ($pendingCount ?? 0) > 0 ? 'Needs attention' : 'All clear' }}
        </span>
    </div>

    <div class="max-h-[420px] overflow-y-auto divide-y">

        {{-- ================= REREG ================= --}}
        @if($reregTasks->count())

        <div class="px-5 py-3 bg-gradient-to-r from-amber-50 to-white border-b border-amber-200">
            <div class="flex items-center gap-2 text-xs font-semibold text-amber-700">
                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-500"></i>
                Re-registration Required
            </div>
        </div>

        @foreach($reregTasks as $task)
        <div class="px-5 py-3 flex items-center justify-between hover:bg-amber-50 transition">

            <div class="flex items-center gap-3">

                <i data-lucide="alert-circle" class="w-4 h-4 text-amber-500"></i>

                <div>
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $task->form_name }}
                    </p>

                    <div class="text-[11px] text-amber-700 font-medium">
                        Action required
                    </div>
                </div>

            </div>

            <a href="{{ $task->link }}"
            class="text-[11px] px-3 py-1.5 rounded-md bg-amber-600 text-white hover:bg-amber-700 font-semibold">
                Open
            </a>

        </div>
        @endforeach

        @endif


        {{-- ================= PROJECT GROUPS ================= --}}
        @foreach($projectTasks as $projectId => $tasks)

            @php $project = $tasks->first()->project ?? null; @endphp

            <div class="px-5 py-3 bg-slate-50 border-t border-b">
                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-2">
                        <i data-lucide="folder" class="w-4 h-4 text-slate-500"></i>

                        <span class="text-xs font-semibold text-slate-700">
                            {{ $project->title ?? 'General Tasks' }}
                        </span>
                    </div>

                    <span class="text-[10px] text-slate-400">
                        {{ $tasks->count() }} task{{ $tasks->count() > 1 ? 's' : '' }}
                    </span>

                </div>
            </div>

            @foreach($tasks as $task)

            @php
                $isApproval = $task->category === 'approval';
                $isRevision = ($task->state ?? null) === 'revision';

                $color = $isApproval
                    ? 'rose'
                    : ($isRevision ? 'amber' : 'emerald');
            @endphp

            <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-50 transition">

                {{-- LEFT --}}
                <div class="flex items-center gap-3">

                    <div class="
                        w-2 h-2 rounded-full
                        {{ $color === 'rose' ? 'bg-rose-500' :
                           ($color === 'amber' ? 'bg-amber-500' : 'bg-emerald-500') }}
                    "></div>

                    <div>
                        <p class="text-sm font-medium text-slate-900">
                            {{ $task->formType->name ?? $task->form_name }}
                        </p>

                        <div class="flex items-center gap-2 text-[11px] text-slate-500">

                            @if($isApproval)
                                <span class="text-rose-600 font-semibold">Awaiting approval</span>
                            @elseif($isRevision)
                                <span class="text-amber-600 font-semibold">Needs revision</span>
                            @else
                                <span class="text-emerald-600 font-semibold">
                                    Action required ({{ $actorLabel }})
                                </span>
                            @endif

                        </div>
                    </div>

                </div>

                {{-- ACTION --}}
                <a href="{{ $task->link }}"
                   class="text-[11px] px-3 py-1.5 rounded-md font-semibold text-white
                   {{ $color === 'rose' ? 'bg-rose-600 hover:bg-rose-700' :
                      ($color === 'amber' ? 'bg-amber-600 hover:bg-amber-700' :
                      'bg-emerald-600 hover:bg-emerald-700') }}">
                    
                    @if($isApproval)
                        Review
                    @elseif($isRevision)
                        Fix
                    @else
                        Open
                    @endif

                </a>

            </div>

            @endforeach

        @endforeach


        {{-- EMPTY --}}
        @if($pendingTasks->count() === 0)
        <div class="px-5 py-10 text-center">

            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-400 mx-auto mb-2"></i>

            <div class="text-sm font-semibold text-slate-700">
                You're all caught up
            </div>

            <div class="text-xs text-slate-500">
                No pending tasks.
            </div>

        </div>
        @endif

    </div>

</div>