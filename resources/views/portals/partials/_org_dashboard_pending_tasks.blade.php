<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

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

    {{-- BODY --}}
    <div class="divide-y">

        @forelse($pendingTasks as $task)

            @php
                $project = $task->project;
                $pending = $task->currentPendingSignature();
            @endphp

            <div class="px-5 py-4 flex items-center justify-between bg-red-50/40">

                {{-- LEFT --}}
                <div class="space-y-1">

                    {{-- FORM NAME --}}
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $task->formType->name ?? 'Document' }}
                    </p>

                    {{-- PROJECT --}}
                    <p class="text-xs text-slate-500">
                        Project: {{ $project->title ?? '—' }}
                    </p>

                    {{-- STATUS --}}
                    <div class="flex items-center gap-2">

                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full ring-1 {{ $task->status_badge_class }}">
                            {{ $task->status_label }}
                        </span>

                        <span class="text-xs font-semibold text-red-700">
                            • Awaiting your approval
                        </span>

                    </div>

                </div>

                {{-- ACTION --}}
                <div>
                    <a href="{{ route('org.projects.documents.hub', $project) }}"
                       class="text-xs px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Review
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