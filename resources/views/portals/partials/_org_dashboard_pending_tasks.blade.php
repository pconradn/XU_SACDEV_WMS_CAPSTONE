                @php
                    $currentUser = auth()->user();

                    if ($currentUser) {
                        $currentUser->loadMissing('profile');
                    }

                    $profile = $currentUser?->profile;

                    $isCurrentUserProfileComplete =
                        $profile
                        && filled($profile->first_name)
                        && filled($profile->last_name)
                        && filled($profile->birthday)
                        && filled($profile->sex)
                        && filled($profile->mobile_number)
                        && filled($profile->email)
                        && filled($profile->home_address)
                        && filled($profile->city_address);
                @endphp                
                @php
                    $pendingTasks = collect($pendingTasks ?? []);
                    $roles = collect($roles ?? []);

                    $pendingTasks = $pendingTasks->filter(function ($task) {
                        $project = $task->project ?? null;

                        if (!$project) {
                            return true;
                        }

                        $project->loadMissing('documents.formType');

                        $documents = collect($project->documents ?? []);

                        $projectProposal = $documents->first(function ($doc) {
                            return (int) ($doc->is_active ?? 0) === 1
                                && $doc->formType?->code === 'PROJECT_PROPOSAL';
                        });

                        $proposalApproved = $projectProposal?->status === 'approved_by_sacdev';

                        $taskFormTypeId = $task->form_type_id
                            ?? $task->formType?->id
                            ?? null;

                        $taskFormName = $task->form_name
                            ?? $task->formType?->name
                            ?? null;

                        $formType = null;

                        if ($taskFormTypeId) {
                            $formType = \App\Models\FormType::query()
                                ->find($taskFormTypeId);
                        }

                        if (!$formType && $taskFormName) {
                            $formType = \App\Models\FormType::query()
                                ->where('name', $taskFormName)
                                ->first();
                        }

                        $phase = $task->formType->phase
                            ?? $formType?->phase
                            ?? null;

                        if ($phase === 'post_implementation' && !$proposalApproved) {
                            return false;
                        }

                        return true;
                    })->values();

                    $pendingCount = $pendingTasks->count();

                    $reregTasks = $pendingTasks->where('category', 'rereg')->values();

                    $clearanceTasks = $pendingTasks
                        ->filter(fn($task) =>
                            ($task->form_code ?? null) === 'OFF_CAMPUS_CLEARANCE'
                            || ($task->form_name ?? null) === 'Off-Campus Clearance'
                        )
                        ->values();

                    $approvalTasks = $pendingTasks
                        ->where('category', 'approval')
                        ->values();

                    $revisionTasks = $pendingTasks
                        ->filter(fn($task) =>
                            ($task->state ?? null) === 'revision'
                            && ($task->form_code ?? null) !== 'OFF_CAMPUS_CLEARANCE'
                            && ($task->form_name ?? null) !== 'Off-Campus Clearance'
                        )
                        ->values();

                    $projectTasks = $pendingTasks
                        ->where('category', '!=', 'rereg')
                        ->reject(fn($task) =>
                            ($task->form_code ?? null) === 'OFF_CAMPUS_CLEARANCE'
                            || ($task->form_name ?? null) === 'Off-Campus Clearance'
                        )
                        ->groupBy(fn($task) => $task->project->id ?? 'none');

                    $actorLabel = 'Project Head';

                    if ($roles->contains('treasurer')) {
                        $actorLabel = 'Treasurer';
                    } elseif ($roles->contains('moderator')) {
                        $actorLabel = 'Moderator';
                    } elseif ($roles->contains('finance_officer')) {
                        $actorLabel = 'Finance Officer';
                    } elseif ($roles->contains('president')) {
                        $actorLabel = 'President';
                    }

                    $isPresident = $roles->contains('president');

                    $hasTasks = ($pendingCount ?? 0) > 0
                        || !$isCurrentUserProfileComplete
                        || ($isPresident && ($projectsWithoutHeadCount ?? 0) > 0);
                @endphp


                <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                    <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                    <i data-lucide="circle-alert" class="w-4 h-4 text-indigo-600"></i>
                                    What needs your attention?
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    Tasks are grouped by workflow area so you can quickly see what needs action.
                                </div>
                            </div>

                            @if(($pendingCount ?? 0) > 0)
                                <span class="shrink-0 inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-semibold text-rose-700">
                                    <i data-lucide="bell" class="w-3 h-3"></i>
                                    {{ $pendingCount }} pending
                                </span>
                            @else
                                <span class="shrink-0 inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                    All clear
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 space-y-4">

                        @if($reregTasks->count())
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                            <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-slate-900">
                                                    {{ $reregTasks->count() }} re-registration {{ $reregTasks->count() > 1 ? 'requirements' : 'requirement' }} need action
                                                </div>

                                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-700">
                                                    Organization Setup
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs leading-5 text-slate-600">
                                                Complete these setup requirements before the organization can fully proceed with project workflows.
                                            </p>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach($reregTasks as $task)
                                                    <a href="{{ $task->link }}"
                                                    class="inline-flex max-w-full items-center gap-1.5 rounded-full border border-amber-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-amber-700 transition hover:bg-amber-50">
                                                        <i data-lucide="file-check-2" class="w-3 h-3 shrink-0"></i>
                                                        <span class="truncate">
                                                            {{ $task->form_name }}
                                                        </span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('org.rereg.index') }}"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-amber-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-700 sm:w-auto">
                                        Open Setup
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @endif


                        @if($clearanceTasks->count())
                            <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                                            <i data-lucide="shield-alert" class="w-5 h-5"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-slate-900">
                                                    {{ $clearanceTasks->count() }} clearance {{ $clearanceTasks->count() > 1 ? 'requirements' : 'requirement' }} need action
                                                </div>

                                                <span class="rounded-full bg-cyan-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-cyan-700">
                                                    Off-Campus Clearance
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs leading-5 text-slate-600">
                                                These projects require off-campus clearance before the workflow can fully proceed.
                                            </p>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach($clearanceTasks->take(10) as $task)
                                                    <a href="{{ $task->project ? route('org.projects.documents.hub', $task->project) : route('org.projects.index') }}"
                                                    class="inline-flex max-w-full items-center gap-1.5 rounded-full border border-cyan-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-cyan-700 transition hover:bg-cyan-50">
                                                        <i data-lucide="shield-alert" class="w-3 h-3 shrink-0"></i>

                                                        <span class="truncate">
                                                            {{ $task->project->title ?? 'Off-Campus Clearance' }}
                                                        </span>

                                                        <span class="hidden sm:inline text-[10px] opacity-75">
                                                            {{ ucfirst(str_replace('_', ' ', $task->status ?? 'not_started')) }}
                                                        </span>
                                                    </a>
                                                @endforeach

                                                @if($clearanceTasks->count() > 10)
                                                    <span class="inline-flex items-center rounded-full border border-cyan-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-cyan-700">
                                                        + {{ $clearanceTasks->count() - 10 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ $clearanceTasks->first()->project ? route('org.projects.documents.hub', $clearanceTasks->first()->project) : route('org.projects.index') }}"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-cyan-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-cyan-700 sm:w-auto">
                                        Open Hub
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @endif


                        @if($approvalTasks->count())
                            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                            <i data-lucide="file-check-2" class="w-5 h-5"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-slate-900">
                                                    {{ $approvalTasks->count() }} {{ $approvalTasks->count() > 1 ? 'documents' : 'document' }} pending your review
                                                </div>

                                                <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-indigo-700">
                                                    {{ $actorLabel }}
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs leading-5 text-slate-600">
                                                These documents are waiting for your approval, review, or signature.
                                            </p>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach($approvalTasks->take(10) as $task)
                                                    <a href="{{ $task->link }}"
                                                    class="inline-flex max-w-full items-center gap-1.5 rounded-full border border-indigo-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-indigo-700 transition hover:bg-indigo-50">
                                                        <i data-lucide="file-signature" class="w-3 h-3 shrink-0"></i>
                                                        <span class="truncate">
                                                            {{ $task->formType->name ?? $task->form_name }}
                                                        </span>
                                                    </a>
                                                @endforeach

                                                @if($approvalTasks->count() > 10)
                                                    <span class="inline-flex items-center rounded-full border border-indigo-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                                        + {{ $approvalTasks->count() - 10 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('org.projects.documents.hub', $approvalTasks->first()->project) }}"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700 sm:w-auto">
                                        Review
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($revisionTasks->count())
                            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                                            <i data-lucide="file-warning" class="w-5 h-5"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-slate-900">
                                                    {{ $revisionTasks->count() }} {{ $revisionTasks->count() > 1 ? 'documents' : 'document' }} need revision
                                                </div>

                                                <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-rose-700">
                                                    Returned
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs leading-5 text-slate-600">
                                                These items were returned and need corrections before they can continue in the workflow.
                                            </p>

                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach($revisionTasks->take(10) as $task)
                                                    <a href="{{ $task->link }}"
                                                    class="inline-flex max-w-full items-center gap-1.5 rounded-full border border-rose-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-rose-700 transition hover:bg-rose-50">
                                                        <i data-lucide="file-pen-line" class="w-3 h-3 shrink-0"></i>
                                                        <span class="truncate">
                                                            {{ $task->formType->name ?? $task->form_name }}
                                                        </span>
                                                    </a>
                                                @endforeach

                                                @if($revisionTasks->count() > 10)
                                                    <span class="inline-flex items-center rounded-full border border-rose-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-rose-700">
                                                        + {{ $revisionTasks->count() - 10 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('org.projects.documents.hub', $revisionTasks->first()->project) }}"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700 sm:w-auto">
                                        Fix
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @foreach($projectTasks as $projectId => $tasks)

                            @php
                                $project = $tasks->first()->project ?? null;

                                $normalTasks = $tasks
                                    ->reject(fn($task) => $task->category === 'approval')
                                    ->reject(fn($task) => ($task->state ?? null) === 'revision')
                                    ->values();
                            @endphp

                            @if($normalTasks->count())
                                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                                <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <div class="text-sm font-semibold text-slate-900">
                                                        {{ $normalTasks->count() }} {{ $normalTasks->count() > 1 ? 'documents' : 'document' }} need action
                                                    </div>

                                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-700">
                                                        {{ $project->title ?? 'General Project Tasks' }}
                                                    </span>
                                                </div>

                                                <p class="mt-1 text-xs leading-5 text-slate-600">
                                                    These project requirements need to be opened, prepared, submitted, or continued.
                                                </p>

                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach($normalTasks->take(10) as $task)
                                                        <a href="{{ $task->link }}"
                                                        class="inline-flex max-w-full items-center gap-1.5 rounded-full border border-blue-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-blue-700 transition hover:bg-blue-50">
                                                            <i data-lucide="file-text" class="w-3 h-3 shrink-0"></i>
                                                            <span class="truncate">
                                                                {{ $task->formType->name ?? $task->form_name }}
                                                            </span>
                                                        </a>
                                                    @endforeach

                                                    @if($normalTasks->count() > 10)
                                                        <span class="inline-flex items-center rounded-full border border-blue-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                                            + {{ $normalTasks->count() - 10 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ $project ? route('org.projects.documents.hub', $project) : route('org.projects.index') }}"
                                        class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700 sm:w-auto">
                                            Open
                                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif

                        @endforeach

                        @if($isPresident && (($projectsWithoutHeadCount ?? 0) > 0))
                            <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                                        </div>

                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-slate-900">
                                                    {{ $projectsWithoutHeadCount }} {{ $projectsWithoutHeadCount > 1 ? 'projects' : 'project' }} need project heads
                                                </div>

                                                <span class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-violet-700">
                                                    President Task
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs leading-5 text-slate-600">
                                                Assign project heads so members can prepare project documents and continue the workflow.
                                            </p>
                                        </div>
                                    </div>

                                    <a href="{{ route('org.assign-project-heads.index') }}"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-violet-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-violet-700 sm:w-auto">
                                        Assign Heads
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(!$isCurrentUserProfileComplete)
                            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-700">
                                            <i data-lucide="user-round-cog" class="w-5 h-5"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-semibold text-slate-900">
                                                    Complete your user profile
                                                </div>

                                                <span class="rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-orange-700">
                                                    Profile Requirement
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs leading-5 text-slate-600">
                                                Some organization workflows depend on your personal profile details. Complete your profile to avoid delays in approvals and submissions.
                                            </p>
                                        </div>
                                    </div>

                                    <a href="{{ route('org.profile.edit') }}"
                                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-orange-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-orange-700 sm:w-auto">
                                        Open Profile
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @unless($hasTasks)
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                    </div>

                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            No urgent workflow actions right now
                                        </div>

                                        <p class="mt-1 text-xs leading-5 text-slate-600">
                                            You are all caught up. You can still review your organization profile or check the projects module.
                                        </p>

                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <a href="{{ route('org.organization-info.show') }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                                Open Organization
                                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                            </a>

                                            <a href="{{ route('org.projects.index') }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                                                Open Projects
                                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endunless

                    </div>
                </div>