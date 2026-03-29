@php
    $classes = [
        'pre_implementation' => 'bg-emerald-100 text-emerald-700',
        'off-campus' => 'bg-purple-100 text-purple-700',
        'post_implementation' => 'bg-blue-100 text-blue-700',
        'notice' => 'bg-red-100 text-red-700',
        'default' => 'bg-yellow-100 text-yellow-700',
    ];
@endphp

<div class="hidden">
    bg-blue-100 text-blue-700
    bg-purple-100 text-purple-700
    bg-emerald-100 text-emerald-700
    bg-red-100 text-red-700
    bg-yellow-100 text-yellow-700
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

    <div class="px-5 py-4 border-b flex justify-between items-center bg-slate-50">
        <h3 class="text-sm font-semibold text-slate-900">
            Project Approvals
        </h3>

        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-slate-200 text-slate-700">
            {{ $projectApprovals->count() }} projects
        </span>
    </div>

    <div class="divide-y max-h-[420px] overflow-y-auto">

        @forelse($projectApprovals as $task)
            <a href="{{ $task->route }}"
               class="block px-5 py-4 hover:bg-slate-50 hover:shadow-sm transition-all">

                <div class="flex items-center justify-between">

                    <div class="text-sm font-semibold text-slate-900">
                        {{ $task->project->title }}
                    </div>

                    @if($task->count > 1)
                        <span class="text-[10px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold">
                            {{ $task->count }} pending
                        </span>
                    @endif

                </div>

                <div class="text-xs text-slate-500 mt-1">
                    {{ $task->organization->name ?? '' }}
                </div>

                <div class="text-[11px] text-slate-400 mt-1">
                    {{ optional($task->project->implementation_start_date)->format('M d, Y') }}
                </div>

                <div class="flex flex-wrap gap-1 mt-3 max-h-[60px] overflow-y-auto pr-1">

                    @foreach($task->forms as $form)

                        <span class="text-[10px] px-2 py-1 rounded-full font-semibold {{ $classes[$form['phase']] ?? $classes['default'] }}">
                            {{ $form['name'] }}
                        </span>

                    @endforeach

                </div>

            </a>
        @empty
            <div class="px-5 py-4 text-sm text-slate-500">
                No pending approvals
            </div>
        @endforelse

    </div>

</div>