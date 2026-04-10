
        {{-- ================= FORMS GRID ================= --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- ================= B1 ================= --}}
            @php $f = $forms['b1']; @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">

                <div class="flex items-start justify-between gap-4">

                    <div class="space-y-2">
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $f['label'] }}
                        </div>

                        {{-- STATUS --}}
                        <div class="inline-flex items-center gap-2 text-xs font-medium">

                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                                <span class="h-2 w-2 rounded-full {{ $f['badge']['dot'] ?? 'bg-slate-400' }}"></span>
                            </span>

                            <span class="text-slate-700">
                                {{ $f['badge']['text'] }}
                            </span>

                        </div>

                        <div class="text-[10px] text-slate-500 space-y-0.5">
                            @if($f['submitted_at'])
                                <div>Submitted: {{ \Carbon\Carbon::parse($f['submitted_at'])->format('M d, Y') }}</div>
                            @endif

                            @if($f['reviewed_at'])
                                <div>Reviewed: {{ \Carbon\Carbon::parse($f['reviewed_at'])->format('M d, Y') }}</div>
                            @endif

                            @if($f['approved_at'])
                                <div class="text-emerald-600 font-medium">Approved</div>
                            @endif
                        </div>
                    </div>


                    <div class="text-right space-y-2">

                        @if($isAdminReregHub && $f['submission'])
                            <a href="{{ route('admin.strategic_plans.show', $f['submission']->id) }}"
                            class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                                View
                            </a>

                        @elseif(!empty($f['editRoute']) && Route::has($f['editRoute']))
                            <a href="{{ route($f['editRoute']) }}"
                            class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                                Open
                            </a>
                        @endif

                    </div>

                </div>
            </div>


                        {{-- ================= B3 ================= --}}
            @php $f = $forms['b3']; @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">

                <div class="flex items-start justify-between gap-4">

                    <div class="space-y-2">
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $f['label'] }}
                        </div>

                        
                        {{-- STATUS --}}
                        <div class="inline-flex items-center gap-2 text-xs font-medium">

                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                                <span class="h-2 w-2 rounded-full {{ $f['badge']['dot'] ?? 'bg-slate-400' }}"></span>
                            </span>

                            <span class="text-slate-700">
                                {{ $f['badge']['text'] }}
                            </span>

                        </div>

                        <div class="text-[10px] text-slate-500 space-y-0.5">
                            @if($f['submitted_at'])
                                <div>Submitted: {{ \Carbon\Carbon::parse($f['submitted_at'])->format('M d, Y') }}</div>
                            @endif

                            @if($f['reviewed_at'])
                                <div>Reviewed: {{ \Carbon\Carbon::parse($f['reviewed_at'])->format('M d, Y') }}</div>
                            @endif

                            @if($f['approved_at'])
                                <div class="text-emerald-600 font-medium">Approved</div>
                            @endif
                        </div>
                    </div>

                    <div>
                        @if($isAdminReregHub && $f['submission'])
                            <a href="{{ route('admin.officer_submissions.show', $f['submission']->id) }}"
                            class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                                View
                            </a>

                        @elseif(!empty($f['editRoute']) && Route::has($f['editRoute']))
                            <a href="{{ route($f['editRoute']) }}"
                            class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
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

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="text-sm font-semibold text-slate-900">
                    {{ $f['label'] }}
                </div>



                <div class="mt-4 space-y-3">

                    <div class="text-xs text-slate-600">
                        This section reflects the assigned president's profile.
                    </div>

                    <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-4 space-y-3">

                        <div>
                            <div class="text-xs font-semibold text-slate-900">
                                Assigned President
                            </div>

                            @if($pres)
                                <div class="mt-1 text-xs text-slate-700">
                                    {{ $pres->name }}
                                </div>
                                <div class="text-[10px] text-slate-500">
                                    {{ $pres->email }}
                                </div>
                            @else
                                <div class="mt-1 text-xs text-slate-600">
                                    No president assigned yet.
                                </div>
                            @endif
                        </div>

                        @if($presProfile)
                            @if(!$isPresidentProfileComplete)
                                <div class="text-[11px] text-rose-600 font-medium">
                                    Profile incomplete
                                </div>
                            @else
                                <div class="text-[11px] text-emerald-600 font-medium">
                                    Profile complete
                                </div>
                            @endif
                        @endif

                        <div class="flex gap-2 flex-wrap">
                            @if($pres)
                                <a href="{{ $isAdminReregHub 
                                    ? route('admin.profile.view', ['user' => $pres->id]) 
                                    : route('org.profile.edit', ['user' => $pres->id]) }}"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">

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

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="text-sm font-semibold text-slate-900">
                    {{ $f['label'] }}
                </div>

                <div class="mt-4 space-y-3">

                    <div class="text-xs text-slate-600">
                        This form is completed by the assigned moderator.
                    </div>

                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 space-y-3">

                        <div>
                            <div class="text-xs font-semibold text-slate-900">
                                Assigned Moderator
                            </div>

                            @if($mod && $mod->user)
                                <div class="mt-1 text-xs text-slate-700">
                                    {{ $mod->user->name }}
                                </div>
                                <div class="text-[10px] text-slate-500">
                                    {{ $mod->user->email }}
                                </div>
                            @else
                                <div class="mt-1 text-xs text-slate-600">
                                    No moderator assigned yet.
                                </div>
                            @endif
                        </div>

                        @if($mod && $mod->user && $mod->user->profile)
                            @if(!$modProfileComplete)
                                <div class="text-[11px] text-rose-600 font-medium">
                                    Profile incomplete
                                </div>
                            @else
                                <div class="text-[11px] text-emerald-600 font-medium">
                                    Profile complete
                                </div>
                            @endif
                        @endif

                        <div class="flex gap-2 flex-wrap">

                            @if(!empty($canAssignModerator) && $canAssignModerator)
                                <a href="{{ route('org.rereg.assign.moderator.edit') }}"
                                class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                                    {{ ($mod && $mod->user) ? 'Change Moderator' : 'Assign Moderator' }}
                                </a>
                            @endif

                            @if($mod && $mod->user)
                                <a href="{{ $isAdminReregHub 
                                    ? route('admin.rereg.moderator.view', ['org' => $organization->id, 'sy' => $encodeSyId]) 
                                    : route('org.rereg.moderator.edit') }}"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">

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
            </div>


            {{-- ================= B6 ================= --}}
            @php $f = $forms['b6']; @endphp

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between gap-4">

                    <div class="space-y-2">
                        <div class="text-sm font-semibold text-slate-900">
                            {{ $f['label'] }}
                        </div>
                    </div>

                    <div class="text-right space-y-2">

                        @if($constitutionSubmission)

                            <a href="{{ route('org.rereg.constitution.download', $constitutionSubmission) }}"
                            class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                                Download
                            </a>

                            <div class="text-[10px] text-slate-500 truncate max-w-[180px]">
                                {{ $constitutionSubmission->original_filename }}
                            </div>

                        @endif

                        <form method="POST"
                            action="{{ route('org.rereg.constitution.upload') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <label class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800 cursor-pointer">
                                {{ $constitutionSubmission ? 'Replace' : 'Upload' }}

                                <input type="file"
                                    name="file"
                                    accept="application/pdf"
                                    required
                                    class="hidden"
                                    onchange="this.form.submit()">
                            </label>

                        </form>

                    </div>

                </div>
            </div>


            {{-- ================= MEMBERS ================= --}}
            <a href="{{ route('org.organization-members.index') }}"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">

                <div class="flex justify-between items-center">

                    <div>
                        <div class="text-sm font-semibold text-slate-900">
                            Organization Members
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Manage members for this school year.
                        </div>
                    </div>

                    <span class="text-xs font-semibold text-slate-700">
                        Open →
                    </span>

                </div>

            </a>

        </div>
