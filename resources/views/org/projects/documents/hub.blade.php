<x-app-layout>

<div 
    x-data="{ 
        openAgreement: @json($needsAgreement),
        helpOpen: false
    }"
>
    @php
        $clearanceHasFile = $clearance['has_file'] ?? !empty($project->clearance_file_path ?? null);
        $clearanceRemarks = $clearance['remarks'] ?? ($project->clearance_remarks ?? null);
        $clearanceCanUpload = $clearance['can_upload'] ?? (($clearance['is_project_head'] ?? false) && !($clearance['is_locked'] ?? false) && !($clearance['is_completed'] ?? false));
        $clearanceStatus = $clearance['status'] ?? null;
    @endphp

    {{-- ================= CONTAINER ================= --}}
    <div class="max-w-7xl mx-auto px-4 py-6 space-y-4">

        {{-- ================= CLEARANCE WORKFLOW BANNER ================= --}}
        @if($clearance['required'])
            <div class="rounded-2xl border shadow-sm px-4 py-3 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between
                @if($clearanceStatus === 'rejected')
                    border-rose-300 bg-rose-50
                @elseif($clearanceHasFile)
                    border-blue-300 bg-blue-50
                @else
                    border-amber-300 bg-amber-50
                @endif
            ">

                {{-- LEFT --}}
                <div class="flex items-start gap-3 min-w-0">

                    {{-- ICON --}}
                    <div class="mt-0.5">
                        @if($clearanceStatus === 'rejected')
                            <i data-lucide="x-circle" class="w-4 h-4 text-rose-600"></i>
                        @elseif($clearanceHasFile)
                            <i data-lucide="file-check" class="w-4 h-4 text-blue-600"></i>
                        @else
                            <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600"></i>
                        @endif
                    </div>

                    {{-- TEXT --}}
                    <div class="min-w-0">
                        <div class="text-xs font-semibold
                            @if($clearanceStatus === 'rejected') text-rose-800
                            @elseif($clearanceHasFile) text-blue-800
                            @else text-amber-800
                            @endif
                        ">
                            @if(!$clearanceHasFile)
                                Off-Campus Clearance Required
                            @elseif($clearanceStatus === 'pending')
                                Clearance Uploaded — Awaiting Approval
                            @elseif($clearanceStatus === 'rejected')
                                Clearance Returned
                            @elseif($clearanceStatus === 'verified')
                                Clearance Approved
                            @else
                                Clearance In Progress
                            @endif
                        </div>

                        <div class="mt-0.5 text-[11px]
                            @if($clearanceStatus === 'rejected') text-rose-700
                            @elseif($clearanceHasFile) text-blue-700
                            @else text-amber-700
                            @endif
                        ">
                            @if(!$clearanceHasFile)
                                Generate the clearance form, secure signatures, then upload.
                            @elseif($clearanceStatus === 'pending')
                                Your clearance is under review.
                            @elseif($clearanceStatus === 'rejected')
                                Review remarks and upload again.
                            @elseif($clearanceStatus === 'verified')
                                Clearance approved.
                            @endif
                        </div>

                        {{-- REMARKS --}}
                        @if($clearanceStatus === 'rejected' && filled($clearanceRemarks))
                            <div class="mt-2 rounded-lg border border-rose-200 bg-white/70 px-2.5 py-2">
                                <div class="text-[10px] font-semibold uppercase text-rose-700">Remarks</div>
                                <div class="mt-1 text-[11px] text-rose-800">
                                    {{ $clearanceRemarks }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="flex items-center gap-2 shrink-0">

                    <button @click="helpOpen = true"
                        class="h-7 w-7 rounded-md border bg-white text-[11px] font-semibold shadow-sm hover:bg-slate-50">
                        ?
                    </button>

                    @if(!$clearanceHasFile)
                        <a href="{{ $clearance['print_url'] }}"
                        class="px-3 py-1.5 rounded-md bg-amber-600 text-white text-[11px] hover:bg-amber-700 transition">
                            Generate
                        </a>
                    @elseif($clearanceStatus === 'rejected' && $clearanceCanUpload)
                        <a href="{{ $clearance['print_url'] }}"
                        class="px-3 py-1.5 rounded-md bg-rose-600 text-white text-[11px] hover:bg-rose-700 transition">
                            Review
                        </a>
                    @endif

                </div>
            </div>
        @endif


        {{-- ================= HEADER ================= --}}
        @include('org.projects.documents.v2.partials.header', ['header' => $header])


        {{-- ================= STATUS BANNERS ================= --}}
        @if($header['is_completed'])
            <div class="rounded-2xl border border-emerald-300 bg-emerald-50 px-4 py-3 shadow-sm">
                <div class="flex items-start gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                    <div>
                        <div class="text-xs font-semibold text-emerald-800">
                            Project Completed
                        </div>
                        <div class="text-[11px] mt-0.5 text-emerald-700">
                            This project has been finalized. Submissions are now closed.
                        </div>
                    </div>
                </div>
            </div>
        @endif


        @if($header['is_cancelled'])
            <div class="rounded-2xl border border-rose-300 bg-rose-50 px-4 py-3 shadow-sm">
                <div class="flex items-start gap-2">
                    <i data-lucide="slash" class="w-4 h-4 text-rose-600 mt-0.5"></i>
                    <div>
                        <div class="text-xs font-semibold text-rose-800">
                            Project Cancelled
                        </div>
                        <div class="text-[11px] mt-0.5 text-rose-700">
                            This project is cancelled. All submissions are locked.
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- ================= MAIN GRID ================= --}}
        <div class="relative">

            <div class="
                grid grid-cols-1 lg:grid-cols-7 gap-4
                @if($header['is_cancelled'])
                    opacity-50 pointer-events-none select-none
                @endif
            ">

            {{-- LEFT --}}
            <div class="lg:col-span-5 space-y-4">

                @include('org.projects.documents.v2.partials.snapshot')

                @include('org.projects.documents.v2.partials.milestone')

                {{-- DOCUMENT TABLE --}}
                @include('org.projects.documents.v2.partials.document-table', [
                    'documentsActionRequired' => $documentsActionRequired,
                    'documentsInProgress' => $documentsInProgress,
                    'documentsCompleted' => $documentsCompleted,
                    'optional' => $sections['optional'] ?? collect(),
                    'workflow' => $sections['workflow'] ?? collect(),
                    'project' => $project,
                    'preSubmitted' => (
                        $proposalDoc && $proposalDoc->status !== 'draft'
                        &&
                        $budgetDoc && $budgetDoc->status !== 'draft'
                    ),
                ])

            </div>


            {{-- RIGHT SIDEBAR --}}
            <div class="lg:col-span-2">
                <div class="space-y-4 lg:sticky lg:top-6">

                    @include('org.projects.documents.v2.partials.actions')

                    @include('org.projects.documents.v2.partials.clearance-card')

                    @include('org.projects.documents.v2.partials.packet-placeholder')

                </div>
            </div>

        </div>
        </div>


        {{-- ================= MODALS ================= --}}
        @include('org.projects.documents.v2.partials.agreement')
        @include('org.projects.documents.v2.partials.help')

    </div>
</div>

</x-app-layout>