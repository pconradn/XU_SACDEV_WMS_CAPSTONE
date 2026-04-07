<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <h2 class="text-lg font-semibold text-slate-900">
                        Organization Re-Registration
                    </h2>

                    <p class="text-xs text-slate-500 mt-1">
                        Complete all required forms to activate your organization for the selected school year.
                    </p>
                </div>

                <div>
                    @if($isActivated)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                            Registered
                        </span>
                    @elseif($allApproved)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                            Ready
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            In Progress
                        </span>
                    @endif
                </div>

            </div>

        </div>


        {{-- ================= GUIDANCE ================= --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 flex items-start gap-3">

            <span class="text-blue-600 text-xs mt-0.5">i</span>

            <div class="text-xs text-slate-700 space-y-1">
                <p class="font-medium text-blue-700">How this works</p>
                <ul class="list-disc ml-4 space-y-0.5">
                    <li>Complete all required forms</li>
                    <li>Wait for review and approval</li>
                    <li>Once approved, your organization becomes active</li>
                </ul>
            </div>

        </div>


        {{-- ================= STATUS MESSAGES ================= --}}
        @if(session('error'))
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('status'))
            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('status') }}
            </div>
        @endif


        {{-- ================= ALREADY ACTIVATED ================= --}}
        @if($isActivated)
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-900 flex justify-between">
                <div>
                    <div class="text-sm font-semibold">
                        Already Registered for this School Year
                    </div>
                    <div class="text-xs mt-1">
                        This organization already has an activation record.
                    </div>
                </div>

                <span class="text-xs font-semibold bg-emerald-100 px-3 py-1 rounded-full">
                    Registered
                </span>
            </div>
        @endif


        {{-- ================= NO SY ================= --}}
        @if(!$encodeSyId)

            <div class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-600">
                Please select a target school year to continue.
            </div>

        @else


        {{-- ================= FORMS GRID ================= --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            @foreach($forms as $key => $f)

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">

                <div class="flex items-start justify-between gap-4">

                    {{-- LEFT --}}
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

                        {{-- TIMELINE --}}
                        <div class="text-[10px] text-slate-500 space-y-0.5">

                            @if(!empty($f['submitted_at']))
                                <div>Submitted: {{ \Carbon\Carbon::parse($f['submitted_at'])->format('M d, Y') }}</div>
                            @endif

                            @if(!empty($f['reviewed_at']))
                                <div>Reviewed: {{ \Carbon\Carbon::parse($f['reviewed_at'])->format('M d, Y') }}</div>
                            @endif

                            @if(!empty($f['approved_at']))
                                <div class="text-emerald-600 font-medium">
                                    Approved
                                </div>
                            @endif

                        </div>

                    </div>


                    {{-- RIGHT ACTION --}}
                    <div class="text-right space-y-2">

                        {{-- CONSTITUTION --}}
                        @if($key === 'b6')

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

                        {{-- NORMAL --}}
                        @elseif($key !== 'b5')

                            @if(!empty($f['editRoute']) && Route::has($f['editRoute']))
                                <a href="{{ route($f['editRoute']) }}"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                                    Open
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400">
                                    No action
                                </span>
                            @endif

                        @endif

                    </div>

                </div>


                {{-- ================= MODERATOR ================= --}}
                @if($key === 'b5')

                <div class="mt-4 space-y-3">

                    <div class="text-xs text-slate-600">
                        This form is completed by the assigned moderator.
                    </div>

                    @php $mod = $b5Moderator ?? null; @endphp

                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">

                        <div class="text-xs font-semibold text-slate-900">
                            Assigned Moderator
                        </div>

                        @if($mod && $mod->user)

                            <div class="mt-2 text-xs text-slate-700 space-y-1">
                                <div>{{ $mod->user->name }}</div>
                                <div class="text-slate-500">{{ $mod->user->email }}</div>
                            </div>

                        @else

                            <div class="mt-2 text-xs text-slate-600">
                                No moderator assigned yet.
                            </div>

                        @endif

                        @if(!empty($canAssignModerator) && $canAssignModerator)
                        <div class="mt-3">
                            <a href="{{ route('org.rereg.assign.moderator.edit') }}"
                            class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                                {{ ($mod && $mod->user) ? 'Change' : 'Assign' }}
                            </a>
                        </div>
                        @endif

                    </div>

                </div>

                @endif

            </div>

            @endforeach


            {{-- MEMBERS --}}
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


        {{-- ================= FOOTER ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-5 shadow-sm">

            <div class="flex justify-between items-center">

                <div class="text-xs text-slate-600">
                    @if($isActivated)
                        Registration complete.
                    @else
                        Complete all required forms and wait for approval.
                    @endif
                </div>

                @if($isActivated)
                    <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full">
                        Registered
                    </span>
                @elseif($allApproved)
                    <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full">
                        Ready
                    </span>
                @else
                    <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-3 py-1 rounded-full">
                        Incomplete
                    </span>
                @endif

            </div>

        </div>


        @endif

    </div>
</x-app-layout>