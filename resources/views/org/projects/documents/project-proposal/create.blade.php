<x-app-layout>

    <div class="max-w-6xl mx-auto space-y-6">

        @php
            $status = $document->status ?? 'draft';

            $isProjectHead = $isProjectHead ?? false;

            $isEditable = $isProjectHead && in_array($status, ['draft','submitted','returned']);

            if ($status === 'approved') {
                $isEditable = false;
            }

            $isReadOnly = !$isEditable;

            $statusStyles = [
                'draft'     => 'bg-slate-50 text-slate-700 border-slate-200',
                'submitted' => 'bg-blue-50 text-blue-800 border-blue-200',
                'returned'  => 'bg-rose-50 text-rose-800 border-rose-200',
                'approved'  => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            ];

            $style = $statusStyles[$status] ?? $statusStyles['draft'];

            $currentApprover = $document?->signatures
                ?->where('status', 'pending')
                ->sortBy('id')
                ->first();
        @endphp

        {{-- ================= STATUS CARD ================= --}}
        <div class="border border-slate-300 {{ $style }} px-4 py-3 text-sm mb-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

                <div class="font-semibold tracking-wide">
                    PROJECT PROPOSAL STATUS:
                    <span class="ml-1 uppercase">
                        {{ $status }}
                    </span>
                </div>

                @if($status === 'submitted' && $currentApprover)
                    <div class="text-[12px] font-medium">
                        Awaiting:
                        <span class="capitalize font-semibold">
                            {{ str_replace('_', ' ', $currentApprover->role) }}
                        </span>
                    </div>
                @endif

                @if($status === 'approved')
                    <div class="text-[12px] font-medium">
                        Fully approved and finalized.
                    </div>
                @endif

                @if($status === 'draft')
                    <div class="text-[12px]">
                        This proposal is still editable.
                    </div>
                @endif

                @if($status === 'returned')
                    <div class="text-[12px] font-medium">
                        Returned for revision. Please update and resubmit.
                    </div>
                @endif

            </div>
        </div>

        {{-- ================= REMARKS ================= --}}
        @if(isset($document) && $document->remarks && $isProjectHead)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-sm font-semibold text-amber-800 mb-1">
                            Returned for Revision
                        </div>

                        <div class="text-sm text-amber-700">
                            {{ $document->remarks }}
                        </div>

                        @if($document->returnedBy)
                            <div class="text-xs text-amber-600 mt-2 italic">
                                {{ $document->returnedBy->name }}
                                • {{ \Carbon\Carbon::parse($document->returned_at)->format('M d, Y h:i A') }}
                            </div>
                        @endif
                    </div>

                    <button onclick="this.closest('div').remove()"
                            class="text-amber-500 hover:text-amber-700 text-sm">
                        ✕
                    </button>
                </div>
            </div>
        @endif

        {{-- ================= HEADER INFO ================= --}}
        <div class="">
            @include('org.projects.documents.project-proposal.partials._header', [
                'project' => $project,
            ])
        </div>

        {{-- @include('org.projects.documents.project-proposal.partials._flash') --}}

        {{-- ================= FORM ================= --}}
        <form method="POST"
              action="{{ route('org.projects.project-proposal.store', $project) }}"
              id="proposalForm"
              class="space-y-6">

            @csrf

            @if($isReadOnly)
                <fieldset disabled class="space-y-6">
            @endif

            {{-- GROUP INTO CARDS --}}
            <div class="grid gap-6">

                <div class="">
                    @include('org.projects.documents.project-proposal.partials._schedule_venue')
                </div>

                <div class="">
                    @include('org.projects.documents.project-proposal.partials._nature_sdg_area')
                </div>

                <div class="">
                    @include('org.projects.documents.project-proposal.partials._description_link_cluster')
                </div>

                <div class="">
                    @include('org.projects.documents.project-proposal.partials._multi_entries')
                </div>

                <div class="">
                    @include('org.projects.documents.project-proposal.partials._budget_funds_audience')
                </div>

                <div class="">
                    @include('org.projects.documents.project-proposal.partials._guests_plan_of_action')
                </div>

            </div>

            @if($isReadOnly)
                </fieldset>
            @endif

        </form>

        {{-- ================= SIGNATURES ================= --}}
        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            @include('org.projects.documents.project-proposal.partials._signatures')
        </div>

        {{-- ================= ACTIONS ================= --}}
        @include('org.projects.documents.project-proposal.partials._actions')

    </div>

    @include('org.projects.documents.project-proposal.partials._script')

</x-app-layout>