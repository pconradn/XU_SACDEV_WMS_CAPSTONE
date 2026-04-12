<div
    id="helpModal"
    class="fixed inset-0 z-[9999] hidden bg-black/40 backdrop-blur-sm"
    onclick="closeHelpModal()"
>
    <div class="absolute inset-0 overflow-y-auto">
        <div class="min-h-full flex items-start justify-center p-4 sm:p-6 md:p-8">
            <div
                onclick="event.stopPropagation()"
                class="w-full max-w-3xl rounded-2xl border border-slate-200 bg-white shadow-2xl mt-10"
            >
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">
                            {{ $helpTitle ?? 'Help Guide' }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Follow the instructions below while filling out this form.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeHelpModal()"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition"
                    >
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="px-5 py-5 space-y-4 text-sm text-slate-700 max-h-[75vh] overflow-y-auto">
                    @if(($document->formType->code ?? null) === 'PROJECT_PROPOSAL')
                        @include('components.project-document.help.content._project-proposal')

                    @elseif(($document->formType->code ?? null) === 'BUDGET_PROPOSAL')
                        @include('components.project-document.help.content._budget-proposal')

                    @elseif(($document->formType->code ?? null) === 'OFF_CAMPUS_APPLICATION')
                        @include('components.project-document.help.content._off-campus')

                    @elseif(($document->formType->code ?? null) === 'SOLICITATION_APPLICATION')
                        @include('components.project-document.help.content._solicitation')

                    @elseif(($document->formType->code ?? null) === 'SELLING_APPLICATION')
                        @include('components.project-document.help.content._selling')

                    @elseif(($document->formType->code ?? null) === 'REQUEST_TO_PURCHASE')
                        @include('components.project-document.help.content._request-to-purchase')

                    @elseif(($document->formType->code ?? null) === 'SOLICITATION_SPONSORSHIP_REPORT')
                        @include('components.project-document.help.content._solicitation-sponsorship-report')

                    @elseif(($document->formType->code ?? null) === 'TICKET_SELLING_REPORT')
                        @include('components.project-document.help.content._ticket-selling-report')

                    @elseif(($document->formType->code ?? null) === 'SELLING_ACTIVITY_REPORT')
                        @include('components.project-document.help.content._selling-activity')

                    @elseif(($document->formType->code ?? null) === 'FEES_COLLECTION_REPORT')
                        @include('components.project-document.help.content._fees-collection')

                    @elseif(($document->formType->code ?? null) === 'DOCUMENTATION_REPORT')
                        @include('components.project-document.help.content._documentation-report')

                    @elseif(($document->formType->code ?? null) === 'LIQUIDATION_REPORT')
                        @include('components.project-document.help.content._liquidation')

                    @elseif(($document->formType->code ?? null) === 'POSTPONEMENT_NOTICE')
                        @include('components.project-document.help.content._postponement-notice')

                    @elseif(($document->formType->code ?? null) === 'CANCELLATION_NOTICE')
                        @include('components.project-document.help.content._cancellation-notice')

                    @else
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                            No help guide is available for this form yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>