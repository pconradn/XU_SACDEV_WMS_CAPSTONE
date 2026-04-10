<div x-data="{ openReturn: false, openApprove: false, loading: false }">

    @if($submission->status === 'submitted_to_sacdev')

        <div class="fixed bottom-4 right-0 z-40 flex justify-center pointer-events-none
                    w-full lg:pl-64">

            <div class="pointer-events-auto w-full max-w-4xl mx-auto px-4">

                <div class="rounded-2xl border border-slate-200 bg-white shadow-lg
                            flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4
                            px-4 py-3">

                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-800">
                            Done reviewing this submission?
                        </span>
                        <span class="text-[11px] text-slate-500">
                            Choose an action to proceed.
                        </span>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">

                        <button type="button"
                                @click="openReturn = true"
                                :disabled="loading"
                                class="flex-1 sm:flex-none rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 
                                       hover:bg-slate-50 transition
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            Return
                        </button>

                        <button type="button"
                                @click="openApprove = true"
                                :disabled="loading"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 rounded-lg 
                                       bg-emerald-600 px-4 py-1.5 text-xs font-semibold text-white 
                                       hover:bg-emerald-700 transition shadow-sm
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            Approve
                        </button>

                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="fixed bottom-4 right-0 z-40 flex justify-center pointer-events-none
                    w-full lg:pl-64">

            <div class="pointer-events-auto w-full max-w-2xl mx-auto px-4">

                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-center shadow-sm">

                    <div class="text-xs font-medium text-slate-700">
                        No actions available
                    </div>

                    <div class="text-[10px] text-slate-500 mt-0.5">
                        This submission cannot be reviewed at this stage.
                    </div>

                </div>

            </div>

        </div>

    @endif


    <form id="approveForm"
          method="POST"
          action="{{ route('admin.officer_submissions.approve', $submission->id) }}">
        @csrf
    </form>


    @include('admin.forms.b3_officers.partials._return_modal', [
        'submission' => $submission
    ])

    @include('admin.forms.b3_officers.partials._approval-preview-modal')

</div>