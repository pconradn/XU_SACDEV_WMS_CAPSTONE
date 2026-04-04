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

    <div class="w-full px-2 py-1 space-y-3">

        {{-- ================= CLEARANCE TOP PROMPT ================= --}}
        @if($clearance['required'])
            <div class="border rounded-xl px-3 py-2 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between
                @if($clearanceStatus === 'rejected')
                    border-rose-300 bg-rose-50
                @elseif($clearanceHasFile)
                    border-blue-300 bg-blue-50
                @else
                    border-amber-300 bg-amber-50
                @endif
            ">
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

                    @if($clearanceStatus === 'rejected' && filled($clearanceRemarks))
                        <div class="mt-2 rounded-lg border border-rose-200 bg-white/70 px-2.5 py-2">
                            <div class="text-[10px] font-semibold uppercase text-rose-700">Remarks</div>
                            <div class="mt-1 text-[11px] text-rose-800">
                                {{ $clearanceRemarks }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button @click="helpOpen = true"
                        class="h-7 w-7 rounded-md border bg-white text-[11px] font-semibold shadow-sm">
                        ?
                    </button>

                    @if(!$clearanceHasFile)
                        <a href="{{ $clearance['print_url'] }}"
                        class="px-3 py-1.5 rounded-md bg-amber-600 text-white text-[11px]">
                            Generate Clearance
                        </a>
                    @elseif($clearanceStatus === 'rejected' && $clearanceCanUpload)
                        <a href="{{ $clearance['print_url'] }}"
                        class="px-3 py-1.5 rounded-md bg-rose-600 text-white text-[11px]">
                            Review Clearance
                        </a>
                    @endif
                </div>
            </div>
        @endif


        {{-- HEADER --}}
        @include('org.projects.documents.v2.partials.header', ['header' => $header])

        @if($header['is_completed'])
            <div class="border border-emerald-300 bg-emerald-50 rounded-xl px-4 py-3 text-emerald-800 shadow-sm">
                <div class="text-sm font-semibold">
                    Project Completed
                </div>
                <div class="text-xs mt-1">
                    This project has been marked as completed by SACDEV Admin. All requirements have been finalized and submissions are now closed.
                </div>
            </div>
        @endif


        @if($header['is_cancelled'])
            <div class="border border-rose-300 bg-rose-50 rounded-xl px-4 py-3 text-rose-800 shadow-sm">
                <div class="text-sm font-semibold">
                    Project Cancelled
                </div>
                <div class="text-xs mt-1">
                    This project has been cancelled. All submissions are now locked and no further actions are allowed.
                </div>
            </div>
        @endif


        {{-- ================= MAIN GRID ================= --}}
        <div class="relative">

            <div class="
                grid grid-cols-1 lg:grid-cols-7 gap-3
                @if($header['is_cancelled'])
                    opacity-50 pointer-events-none select-none
                @endif
            ">

            {{-- LEFT --}}
            <div class="lg:col-span-5 space-y-3">

                @include('org.projects.documents.v2.partials.snapshot')

                @include('org.projects.documents.v2.partials.milestone')

                {{-- NOTICE --}}

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


            {{-- RIGHT --}}
            <div class="lg:col-span-2">
                <div class="space-y-3 lg:sticky lg:top-6">

                    @include('org.projects.documents.v2.partials.actions')

                    @include('org.projects.documents.v2.partials.clearance-card')

                    @include('org.projects.documents.v2.partials.packet-placeholder')

                    
                </div>
            </div>
        </div>

        </div>


        {{-- MODALS --}}
        @include('org.projects.documents.v2.partials.agreement')
        @include('org.projects.documents.v2.partials.help')

    </div>
</div>

</x-app-layout>