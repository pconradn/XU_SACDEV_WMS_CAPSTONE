<div class="grid grid-cols-1 gap-4 xl:grid-cols-2">

    {{-- ================= B1 ================= --}}
    @php $f = $forms['b1']; @endphp
    <div class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm transition hover:border-slate-300 hover:bg-slate-50/80">

        <div class="flex h-full flex-col justify-between gap-4 sm:flex-row sm:items-start">

            <div class="min-w-0 flex-1 space-y-4">

                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                        <i data-lucide="file-text" class="h-4 w-4"></i>
                    </div>

                    <div class="min-w-0 space-y-1">
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $f['label'] }}
                        </div>
                        <div class="text-xs text-slate-500">
                            Prepare and review the organization strategic plan for the selected school year.
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-slate-700">
                        <span class="h-2 w-2 rounded-full {{ $f['badge']['dot'] ?? 'bg-slate-400' }}"></span>
                        {{ $f['badge']['text'] }}
                    </span>

                    @if($f['approved_at'])
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-emerald-700">
                            <i data-lucide="badge-check" class="h-3.5 w-3.5"></i>
                            Approved
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-2 text-[11px] text-slate-500 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <div class="font-medium text-slate-700">Submitted</div>
                        <div class="mt-1">{{ $f['submitted_at'] ? \Carbon\Carbon::parse($f['submitted_at'])->format('M d, Y') : '—' }}</div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <div class="font-medium text-slate-700">Reviewed</div>
                        <div class="mt-1">{{ $f['reviewed_at'] ? \Carbon\Carbon::parse($f['reviewed_at'])->format('M d, Y') : '—' }}</div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <div class="font-medium text-slate-700">Approval</div>
                        <div class="mt-1">{{ $f['approved_at'] ? \Carbon\Carbon::parse($f['approved_at'])->format('M d, Y') : 'Pending' }}</div>
                    </div>
                </div>

            </div>

            <div class="flex shrink-0 sm:justify-end">
                @if($isAdminReregHub && $f['submission'])
                    <a href="{{ route('admin.strategic_plans.show', $f['submission']->id) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                        View
                    </a>
                @elseif(!empty($f['editRoute']) && Route::has($f['editRoute']))
                    <a href="{{ route($f['editRoute']) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="folder-open" class="h-3.5 w-3.5"></i>
                        Open
                    </a>
                @endif
            </div>

        </div>
    </div>

    {{-- ================= B3 ================= --}}
    @php $f = $forms['b3']; @endphp
    <div class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm transition hover:border-slate-300 hover:bg-slate-50/80">

        <div class="flex h-full flex-col justify-between gap-4 sm:flex-row sm:items-start">

            <div class="min-w-0 flex-1 space-y-4">

                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                        <i data-lucide="users" class="h-4 w-4"></i>
                    </div>

                    <div class="min-w-0 space-y-1">
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $f['label'] }}
                        </div>
                        <div class="text-xs text-slate-500">
                            Review and maintain the list of officers endorsed for the current registration cycle.
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                    <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-2.5 py-1 text-slate-700">
                        <span class="h-2 w-2 rounded-full {{ $f['badge']['dot'] ?? 'bg-slate-400' }}"></span>
                        {{ $f['badge']['text'] }}
                    </span>

                    @if($f['approved_at'])
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-emerald-700">
                            <i data-lucide="badge-check" class="h-3.5 w-3.5"></i>
                            Approved
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-2 text-[11px] text-slate-500 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <div class="font-medium text-slate-700">Submitted</div>
                        <div class="mt-1">{{ $f['submitted_at'] ? \Carbon\Carbon::parse($f['submitted_at'])->format('M d, Y') : '—' }}</div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <div class="font-medium text-slate-700">Reviewed</div>
                        <div class="mt-1">{{ $f['reviewed_at'] ? \Carbon\Carbon::parse($f['reviewed_at'])->format('M d, Y') : '—' }}</div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-2">
                        <div class="font-medium text-slate-700">Approval</div>
                        <div class="mt-1">{{ $f['approved_at'] ? \Carbon\Carbon::parse($f['approved_at'])->format('M d, Y') : 'Pending' }}</div>
                    </div>
                </div>

            </div>

            <div class="flex shrink-0 sm:justify-end">
                @if($isAdminReregHub && $f['submission'])
                    <a href="{{ route('admin.officer_submissions.show', $f['submission']->id) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                        View
                    </a>
                @elseif(!empty($f['editRoute']) && Route::has($f['editRoute']))
                    <a href="{{ route($f['editRoute']) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="folder-open" class="h-3.5 w-3.5"></i>
                        Open
                    </a>
                @endif
            </div>

        </div>
    </div>

    {{-- ================= B2 ================= --}}
    @php
        $f = $forms['b2'];
        $pres = $presidentUser ?? null;
        $presProfile = $pres?->profile;
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">

        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700">
                <i data-lucide="user-round" class="h-4 w-4"></i>
            </div>

            <div class="space-y-1">
                <div class="text-sm font-semibold text-slate-900">
                    {{ $f['label'] }}
                </div>
                <div class="text-xs text-slate-500">
                    This section reflects the assigned president's profile and readiness for registration.
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-2xl border border-indigo-200 bg-gradient-to-b from-indigo-50 to-white p-4">

            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900">
                        Assigned President
                    </div>

                    @if($pres)
                        <div class="mt-1 truncate text-xs font-medium text-slate-800">
                            {{ $pres->name }}
                        </div>
                        <div class="mt-0.5 truncate text-[11px] text-slate-500">
                            {{ $pres->email }}
                        </div>
                    @else
                        <div class="mt-1 text-xs text-slate-600">
                            No president assigned yet.
                        </div>
                    @endif
                </div>

                <div class="shrink-0">
                    @if($presProfile)
                        @if(!$isPresidentProfileComplete)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-700">
                                <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>
                                Incomplete
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                                <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
                                Complete
                            </span>
                        @endif
                    @endif
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @if($pres)
                    <a href="{{ $isAdminReregHub
                        ? route('admin.profile.view', ['user' => $pres->id])
                        : route('org.profile.edit', ['user' => $pres->id]) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="user-search" class="h-3.5 w-3.5"></i>
                        @if(auth()->id() !== $pres->id)
                            View Profile
                        @elseif(!$isPresidentProfileComplete)
                            Complete Profile
                        @else
                            Open Profile
                        @endif
                    </a>
                @endif
            </div>

        </div>
    </div>

    {{-- ================= B5 ================= --}}
    @php
        $f = $forms['b5'];
        $submission = $f['submission'];
        $mod = $b5Moderator ?? null;
        $modProfile = $mod?->user?->profile;

        $modProfileComplete = $modProfile
            && $modProfile->first_name
            && $modProfile->last_name
            && $modProfile->mobile_number
            && $modProfile->email
            && $modProfile->city_address
            && $modProfile->university_designation
            && $modProfile->unit_department
            && $modProfile->employment_status
            && $modProfile->years_of_service;
    @endphp

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">

        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-700">
                <i data-lucide="shield-check" class="h-4 w-4"></i>
            </div>

            <div class="space-y-1">
                <div class="text-sm font-semibold text-slate-900">
                    {{ $f['label'] }}
                </div>
                <div class="text-xs text-slate-500">
                    This section is completed by the assigned moderator and tracks moderator readiness.
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-2xl border border-blue-200 bg-gradient-to-b from-blue-50 to-white p-4">

            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-xs font-semibold text-slate-900">
                        Assigned Moderator
                    </div>

                    @if($mod && $mod->user)
                        <div class="mt-1 truncate text-xs font-medium text-slate-800">
                            {{ $mod->user->name }}
                        </div>
                        <div class="mt-0.5 truncate text-[11px] text-slate-500">
                            {{ $mod->user->email }}
                        </div>
                    @else
                        <div class="mt-1 text-xs text-slate-600">
                            No moderator assigned yet.
                        </div>
                    @endif
                </div>

                <div class="shrink-0">
                    @if(!$submission)

                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-700">
                            <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>
                            Submission Required
                        </span>

                    @else

                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                            <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
                            Complete
                        </span>

                    @endif
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @if(!empty($canAssignModerator) && $canAssignModerator)
                    <a href="{{ route('org.rereg.assign.moderator.edit') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        <i data-lucide="user-cog" class="h-3.5 w-3.5"></i>
                        {{ ($mod && $mod->user) ? 'Change Moderator' : 'Assign Moderator' }}
                    </a>
                @endif

                @if($mod && $mod->user)
                    <a href="{{ $isAdminReregHub
                        ? route('admin.rereg.moderator.view', ['org' => $organization->id, 'sy' => $encodeSyId])
                        : route('org.rereg.moderator.edit') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="folder-open" class="h-3.5 w-3.5"></i>
                        @if($isAdminReregHub)
                            View
                        @elseif(!$isModerator)
                            View
                        @elseif(!$modProfileComplete)
                            Complete Profile
                        @elseif(!$submission)
                            Start
                        @else
                            Open
                        @endif
                    </a>
                @endif
            </div>

        </div>
    </div>

   
    @php $f = $forms['b6']; @endphp
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">

        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                <i data-lucide="file-archive" class="h-4 w-4"></i>
            </div>

            <div class="space-y-1">
                <div class="text-sm font-semibold text-slate-900">
                    {{ $f['label'] }}
                </div>
                <div class="text-xs text-slate-500">
                    Keep one active copy of the organization constitution for this registration cycle.
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-4">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div class="min-w-0 flex-1 space-y-3">

                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                        @if($constitutionSubmission)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-emerald-700">
                                <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
                                Uploaded
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-slate-600">
                                <i data-lucide="circle-dashed" class="h-3.5 w-3.5"></i>
                                Not uploaded
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-2 text-[11px] text-slate-500 sm:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                            <div class="font-medium text-slate-700">Latest upload</div>
                            <div class="mt-1">
                                {{ $constitutionSubmission?->submitted_at ? \Carbon\Carbon::parse($constitutionSubmission->submitted_at)->format('M d, Y') : '—' }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 min-w-0">
                            <div class="font-medium text-slate-700">File name</div>
                            <div class="mt-1 truncate">
                                {{ $constitutionSubmission?->original_filename ?? 'No file uploaded yet' }}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex shrink-0 flex-wrap items-center gap-2 sm:justify-end">
                    @php
                        $downloadRoute = null;

                        if ($constitutionSubmission) {
                            $downloadRoute = $isAdminReregHub
                                ? route('admin.rereg.constitution.download', $constitutionSubmission->id)
                                : route('org.rereg.constitution.download', $constitutionSubmission->id);
                        }
                    @endphp

                    @if($constitutionSubmission)
                        @if($downloadRoute)
                            <a href="{{ $downloadRoute }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                                <i data-lucide="download" class="h-3.5 w-3.5"></i>
                                Download
                            </a>
                        @endif
                    @endif

                    @if($isPresident && !$isAdminReregHub)
                        <form method="POST"
                              action="{{ route('org.rereg.constitution.upload') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                <i data-lucide="{{ $constitutionSubmission ? 'refresh-cw' : 'upload' }}" class="h-3.5 w-3.5"></i>
                                {{ $constitutionSubmission ? 'Replace' : 'Upload' }}

                                <input type="file"
                                       name="constitution_file"
                                       accept="application/pdf"
                                       required
                                       class="hidden"
                                       onchange="this.form.submit()">
                            </label>
                        </form>
                    @endif
                </div>

            </div>

        </div>
    </div>

  
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">

        {{-- HEADER --}}
        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-amber-200 bg-amber-50 text-amber-700">
                <i data-lucide="image-up" class="h-4 w-4"></i>
            </div>

            <div class="space-y-1">
                <div class="text-sm font-semibold text-slate-900">
                    Organization Document Header
                </div>
                <div class="text-xs text-slate-500">
                    Upload a reusable document header for official printables and generated forms.
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="mt-4 rounded-2xl border border-dashed border-amber-200 bg-gradient-to-b from-amber-50 to-white p-4">

            <div class="grid gap-4 lg:grid-cols-2 lg:items-center">

                {{-- LEFT --}}
                <div class="space-y-3">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-100 px-2.5 py-1 text-[11px] font-medium text-amber-700">
                        <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                        Coming soon
                    </span>

                    <div class="text-xs text-slate-600 leading-relaxed">
                        Later, presidents will be able to upload a header image and preview how it appears in printable outputs.
                    </div>

                    <div class="text-[11px] text-slate-500">
                        Planned support: upload, preview, replacement, and printable integration.
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="w-full">

                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">

                        {{-- PREVIEW --}}
                        <div class="flex h-[120px] items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50">
                            <div class="space-y-2 text-center">
                                <div class="mx-auto flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i data-lucide="image" class="h-4 w-4"></i>
                                </div>
                                <div class="text-[10px] font-medium text-slate-500">
                                    Preview unavailable
                                </div>
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <button type="button"
                            disabled
                            class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-400 cursor-not-allowed">
                            <i data-lucide="upload" class="h-3.5 w-3.5"></i>
                            Upload
                        </button>

                    </div>

                </div>

            </div>

        </div>
    </div>
@php
    $isAdmin = auth()->user()?->isSacdev();
@endphp
    
<a href="{{ $isAdmin
        ? route('sacdev.members.index', [
            'organization_id' => $organization->id,
            'school_year_id' => $encodeSyId
        ])
        : route('org.organization-members.index') }}"
   class="group rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm transition hover:border-slate-300 hover:bg-slate-50/80">

    <div class="flex items-start justify-between gap-4">

        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600">
                <i data-lucide="users-2" class="h-4 w-4"></i>
            </div>

            <div class="space-y-1">
                <div class="text-sm font-semibold text-slate-900">
                    Organization Members
                </div>
                <div class="text-xs text-slate-500">
                    Manage members and school-year participation records for this organization.
                </div>
            </div>
        </div>

        <span class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition group-hover:bg-slate-50">
            <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
            Open
        </span>

    </div>
</a>

</div>