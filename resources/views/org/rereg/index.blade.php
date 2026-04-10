<x-app-layout>

@php
    $isAdminReregHub = $isAdminReregHub ?? false;
@endphp

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

        @include('org.rereg.partials._forms-grid', [
            'forms' => $forms,
            'presidentUser' => $presidentUser,
            'isPresidentProfileComplete' => $isPresidentProfileComplete,
            'b5Moderator' => $b5Moderator,
            'isModerator' => $isModerator,
            'canAssignModerator' => $canAssignModerator,
            'constitutionSubmission' => $constitutionSubmission,
            'encodeSyId' => $encodeSyId,
            'isAdminReregHub' => $isAdminReregHub ?? false,
        ])

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