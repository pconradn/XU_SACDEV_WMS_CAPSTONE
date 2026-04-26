<x-app-layout>

@php
    $isAdminView = $isAdminView ?? false;

    $isEditable = !$isAdminView && $isModerator && $isProfileComplete;

    if ($isAdminView) {
        $profileRoute = route('admin.profile.view', ['user' => $profile->user_id]);
    } elseif ($isModerator) {
        $profileRoute = route('org.profile.edit');
    } else {
        $profileRoute = route('org.profile.edit', ['user' => $profile->user_id]);
    }
    

    
@endphp

@php

@endphp

<style>
    .page-container {
        max-width: 1180px;
    }
</style>

<nav class="px-5 sm:px-6 pt-4 text-xs text-slate-500">
    <ol class="flex items-center gap-1">
        <li>
            <a href="{{ $isAdminView 
                    ? route('admin.rereg.hub', ['organization' => $organization->id]) 
                    : route('org.rereg.index') }}"
               class="hover:text-slate-700 transition">
                {{ $isAdminView ? 'Re-Registration Hub' : 'Re-Registration' }}
            </a>
        </li>

        <li class="text-slate-400">/</li>

        <li class="text-slate-700 font-medium">
            Moderator Submission
        </li>
    </ol>
</nav>


<div class="page-container mx-auto px-4 py-6 space-y-6">

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <i data-lucide="shield-check" class="h-4 w-4"></i>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Moderator Submission
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        @if($isAdminView)
                            Admin viewing mode. This page is read-only.
                        @else
                            Organization members can view this page. Only the assigned moderator can update the submission.
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($isAdminView)
                    <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700">
                        <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                        Read Only
                    </span>
                @endif

                @if($isProfileComplete)
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                        <i data-lucide="badge-check" class="h-3.5 w-3.5"></i>
                        Profile Complete
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-700">
                        <i data-lucide="alert-circle" class="h-3.5 w-3.5"></i>
                        Profile Incomplete
                    </span>
                @endif
            </div>

        </div>
    </div>


    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm">
            <div class="flex items-start gap-2">
                <div class="mt-0.5 text-emerald-600">
                    <i data-lucide="check-circle-2" class="h-4 w-4"></i>
                </div>
                <div class="text-xs font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif


    @if(!$isProfileComplete && $isModerator)
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 text-rose-600">
                    <i data-lucide="shield-alert" class="h-4 w-4"></i>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-sm font-semibold text-rose-900">
                        Profile Incomplete
                    </div>
                    <p class="mt-1 text-xs text-rose-800">
                        Complete the following required profile details before the moderator submission can be updated.
                    </p>

                    <ul class="mt-3 space-y-1.5 text-xs text-rose-800">
                        @foreach($missingFields as $field)
                            <li class="flex items-start gap-2">
                                <i data-lucide="dot" class="mt-[1px] h-4 w-4 shrink-0"></i>
                                <span>{{ $field }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        <a href="{{ $profileRoute }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-white/70 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                            <i data-lucide="external-link" class="h-3.5 w-3.5"></i>
                            {{ $isAdminView ? 'View Moderator Profile' : ($isModerator ? 'Open Profile' : 'Open Moderator Profile') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="space-y-6 lg:col-span-2">

            <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                <i data-lucide="user-round" class="h-4 w-4"></i>
                            </div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Profile Summary
                            </div>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-400">
                            Moderator identity and profile details used for this submission
                        </p>
                    </div>

                    <a href="{{ $profileRoute }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="external-link" class="h-3.5 w-3.5"></i>
                        {{ $isAdminView ? 'View Profile' : ($isModerator ? 'Open Profile' : 'Open Moderator Profile') }}
                    </a>
                </div>

                <div class="mt-4 rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="flex items-start gap-4">
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                            @if($profile && $profile->photo_id_path)
                                <img src="{{ asset('storage/'.$profile->photo_id_path) }}"
                                     class="h-full w-full object-cover">
                            @else
                                <i data-lucide="image-off" class="h-5 w-5 text-slate-400"></i>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-semibold text-slate-900">
                                {{ $profile->full_name ?? '—' }}
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                {{ $profile->email ?? '—' }}
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-600">
                                    {{ $profile->university_designation ?? 'No Designation' }}
                                </span>
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-600">
                                    {{ $profile->unit_department ?? 'No Department' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">Name</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">{{ $profile->full_name ?? '—' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">Email</div>
                            <div class="mt-1 text-sm font-medium text-slate-900 break-all">{{ $profile->email ?? '—' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">Mobile</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">{{ $profile->mobile_number ?? '—' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">City Address</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">{{ $profile->city_address ?? '—' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">Designation</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">{{ $profile->university_designation ?? '—' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">Department</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">{{ $profile->unit_department ?? '—' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">Employment</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">{{ $profile->employment_status ?? '—' }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-3 py-3">
                            <div class="text-[11px] text-slate-500">Years of Service</div>
                            <div class="mt-1 text-sm font-medium text-slate-900">{{ $profile->years_of_service ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>


            <form method="POST" action="{{ route('org.rereg.moderator.update') }}">
                @csrf

                @php
                    $hasSubmission = $submission && (
                        $submission->was_moderator_before !== null ||
                        $submission->moderated_org_name ||
                        $submission->served_nominating_org_before !== null ||
                        $submission->served_nominating_org_years
                    );
                @endphp

                <div class="rounded-2xl border {{ ($isEditable && !$hasSubmission) ? 'border-amber-300 bg-amber-50/40' : 'border-slate-200 bg-gradient-to-b from-slate-50 to-white' }} p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                                    <i data-lucide="file-pen-line" class="h-4 w-4"></i>
                                </div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    Moderator Details
                                </div>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-400">
                                Background details needed for moderator nomination and review
                            </p>
                        </div>

                        @if($isEditable && !$hasSubmission)
                            <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-800">
                                <i data-lucide="alert-triangle" class="h-3.5 w-3.5"></i>
                                Action Required
                            </span>
                        @elseif($isEditable)
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                                <i data-lucide="pencil-line" class="h-3.5 w-3.5"></i>
                                Editable
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-600">
                                <i data-lucide="lock" class="h-3.5 w-3.5"></i>
                                Locked
                            </span>
                        @endif
                    </div>

                    @if($isEditable && !$hasSubmission)
                        <div class="mt-3 rounded-xl border border-amber-200 bg-amber-100 px-3 py-2 text-[12px] text-amber-900 flex items-start gap-2">
                            <i data-lucide="info" class="h-4 w-4 mt-[1px]"></i>
                            <div>
                                This section still needs to be filled out. Please complete all required fields before saving your submission.
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div class="rounded-xl bg-white p-3 shadow-sm ring-1 ring-slate-200">
                            <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-slate-600">
                                <i data-lucide="history" class="h-3.5 w-3.5 text-slate-400"></i>
                                Have you been a moderator before?
                            </label>

                            <select name="was_moderator_before"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                    @disabled(!$isEditable)>
                                <option value="">Select</option>
                                <option value="1" @selected(old('was_moderator_before', $submission?->was_moderator_before) == 1)>Yes</option>
                                <option value="0" @selected(old('was_moderator_before', $submission?->was_moderator_before) == 0)>No</option>
                            </select>

                            @error('was_moderator_before')
                                <div class="mt-1 text-[11px] text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="rounded-xl bg-white p-3 shadow-sm ring-1 ring-slate-200">
                            <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-slate-600">
                                <i data-lucide="building-2" class="h-3.5 w-3.5 text-slate-400"></i>
                                If yes, which organization?
                            </label>

                            <input type="text"
                                name="moderated_org_name"
                                value="{{ old('moderated_org_name', $submission?->moderated_org_name) }}"
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                @disabled(!$isEditable)>

                            @error('moderated_org_name')
                                <div class="mt-1 text-[11px] text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="rounded-xl bg-white p-3 shadow-sm ring-1 ring-slate-200">
                            <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-slate-600">
                                <i data-lucide="users-round" class="h-3.5 w-3.5 text-slate-400"></i>
                                Served this nominating organization before?
                            </label>

                            <select name="served_nominating_org_before"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                    @disabled(!$isEditable)>
                                <option value="">Select</option>
                                <option value="1" @selected(old('served_nominating_org_before', $submission?->served_nominating_org_before) == 1)>Yes</option>
                                <option value="0" @selected(old('served_nominating_org_before', $submission?->served_nominating_org_before) == 0)>No</option>
                            </select>

                            @error('served_nominating_org_before')
                                <div class="mt-1 text-[11px] text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="rounded-xl bg-white p-3 shadow-sm ring-1 ring-slate-200">
                            <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-slate-600">
                                <i data-lucide="calendar-range" class="h-3.5 w-3.5 text-slate-400"></i>
                                If yes, how many years?
                            </label>

                            <input type="number"
                                name="served_nominating_org_years"
                                value="{{ old('served_nominating_org_years', $submission?->served_nominating_org_years) }}"
                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                @disabled(!$isEditable)>

                            @error('served_nominating_org_years')
                                <div class="mt-1 text-[11px] text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    @if($isModerator && !$isAdminView)
                        <div class="mt-5 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50"
                                    @disabled(!$isProfileComplete)>
                                <i data-lucide="save" class="h-3.5 w-3.5"></i>
                                Save Submission
                            </button>
                        </div>
                    @endif
                </div>
            </form>

        </div>


@php
    $hasSubmission = $submission && (
        $submission->was_moderator_before !== null ||
        $submission->moderated_org_name ||
        $submission->served_nominating_org_before !== null ||
        $submission->served_nominating_org_years
    );
@endphp

<div class="space-y-6">

    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                <i data-lucide="list-checks" class="h-4 w-4"></i>
            </div>
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                Progress Checklist
            </div>
        </div>

        <div class="mt-4 space-y-3">

            <div class="flex items-center justify-between rounded-xl bg-white px-3 py-3 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-center gap-2 text-sm font-medium text-slate-800">
                    @if($isProfileComplete)
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-600"></i>
                    @else
                        <i data-lucide="circle" class="h-4 w-4 text-slate-300"></i>
                    @endif
                    Profile Completed
                </div>
                <span class="text-[11px] font-medium {{ $isProfileComplete ? 'text-emerald-700' : 'text-slate-400' }}">
                    {{ $isProfileComplete ? 'Done' : 'Pending' }}
                </span>
            </div>

            <div class="flex items-center justify-between rounded-xl bg-white px-3 py-3 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-center gap-2 text-sm font-medium text-slate-800">
                    @if($hasSubmission)
                        <i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-600"></i>
                    @else
                        <i data-lucide="circle" class="h-4 w-4 text-slate-300"></i>
                    @endif
                    Submission Created
                </div>
                <span class="text-[11px] font-medium {{ $hasSubmission ? 'text-emerald-700' : 'text-slate-400' }}">
                    {{ $hasSubmission ? 'Done' : 'Pending' }}
                </span>
            </div>

        </div>
    </div>


    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                <i data-lucide="clipboard-list" class="h-4 w-4"></i>
            </div>
            <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                Guidance
            </div>
        </div>

        <div class="mt-4 space-y-3 text-xs text-slate-600">
            <div class="rounded-xl bg-white px-3 py-3 shadow-sm ring-1 ring-slate-200">
                Complete the moderator profile first before filling out the submission details.
            </div>

            <div class="rounded-xl bg-white px-3 py-3 shadow-sm ring-1 ring-slate-200">
                Only the assigned moderator can update this submission when profile requirements are complete.
            </div>

            <div class="rounded-xl bg-white px-3 py-3 shadow-sm ring-1 ring-slate-200">
                Admin view is intended for review and verification only.
            </div>
        </div>
    </div>

</div>

    </div>

</div>

</x-app-layout>