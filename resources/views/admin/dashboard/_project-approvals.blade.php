@php
    $classes = [
        'pre_implementation' => 'bg-emerald-100 text-emerald-700',
        'off-campus' => 'bg-purple-100 text-purple-700',
        'post_implementation' => 'bg-blue-100 text-blue-700',
        'notice' => 'bg-rose-100 text-rose-700',
        'completion' => 'bg-emerald-100 text-emerald-800',
        'default' => 'bg-amber-100 text-amber-700',
    ];
@endphp

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">

        <div class="flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-slate-500"></i>

            <div>
                <div class="text-xs font-semibold text-slate-900">
                    Project Approvals
                </div>
                <div class="text-[10px] text-slate-500">
                    Pending approvals and completion-ready projects
                </div>
            </div>
        </div>

        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
            {{ $projectApprovals->count() }}
        </span>

    </div>


    {{-- TABLE HEAD --}}
    <div class="px-4 py-2 border-b border-slate-200 bg-slate-50/70 text-[10px] font-semibold text-slate-500 uppercase tracking-wide grid grid-cols-12 gap-2">

        <div class="col-span-5">Project</div>
        <div class="col-span-2">Date</div>
        <div class="col-span-3">Forms</div>
        <div class="col-span-2 text-right">Status</div>

    </div>


    {{-- ROWS --}}
    <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto">

        @forelse($projectApprovals as $task)

            @php
                $isCompletion = $task->is_completion ?? false;
            @endphp

            <a href="{{ $task->form_route }}"
               class="grid grid-cols-12 gap-2 items-center px-4 py-3 text-xs transition
                      {{ $isCompletion ? 'bg-emerald-50 hover:bg-emerald-100/50' : 'hover:bg-slate-50' }}">

                {{-- PROJECT --}}
                <div class="col-span-5 min-w-0">

                    <div class="font-medium text-slate-900 truncate">
                        {{ $task->project->title }}
                    </div>

                    <div class="text-[10px] text-slate-500 truncate">
                        {{ $task->organization->name ?? '' }}
                    </div>

                </div>


                {{-- DATE --}}
                <div class="col-span-2 text-[11px] text-slate-500">
                    {{ optional($task->project->implementation_start_date)->format('M d, Y') }}
                </div>


                {{-- FORMS --}}
                <div class="col-span-3 flex flex-wrap gap-1">

                    @foreach($task->forms as $form)
                        <span class="text-[9px] px-2 py-0.5 rounded-full font-semibold
                            {{ $classes[$form['phase']] ?? $classes['default'] }}">
                            {{ $form['name'] }}
                        </span>
                    @endforeach

                </div>


                {{-- STATUS --}}
                <div class="col-span-2 flex justify-end">

                    @if($isCompletion)
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                            Ready
                        </span>
                    @elseif($task->count > 1)
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-semibold">
                            {{ $task->count }} pending
                        </span>
                    @endif

                </div>

            </a>

        @empty

            <div class="px-4 py-6 text-center">

                <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                    <i data-lucide="inbox" class="w-4 h-4"></i>
                </div>

                <div class="text-xs font-medium text-slate-600">
                    No pending approvals
                </div>

            </div>

        @endforelse

    </div>

</div>