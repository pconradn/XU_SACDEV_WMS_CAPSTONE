<div x-data="{ openReturn: false, openApprove: false, openRevert: false, loading: false }">

    @if($submission->status === 'submitted_to_sacdev')

        <div class="fixed bottom-0 left-0 right-0 z-40 
                    border-t border-slate-200 
                    bg-white/95 backdrop-blur
                    shadow-[0_-4px_16px_rgba(0,0,0,0.06)]
                    pb-[env(safe-area-inset-bottom)]">

            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between gap-4">

                {{-- LEFT --}}
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-slate-800">
                        Done reviewing this submission?
                    </span>
                    <span class="text-xs text-slate-500">
                        Choose an action below to proceed.
                    </span>
                </div>

                <div class="flex items-center gap-2">

          
                    <button type="button"
                            @click="openReturn = true"
                            :disabled="loading"
                            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 
                                   hover:bg-slate-50 transition
                                   disabled:opacity-50 disabled:cursor-not-allowed">
                        Return for Revision
                    </button>


                    <button type="button"
                            @click="openApprove = true"
                            :disabled="loading"
                            class="inline-flex items-center justify-center gap-2 rounded-lg 
                                   bg-slate-900 px-5 py-2 text-sm font-semibold text-white 
                                   hover:bg-slate-800 transition
                                   disabled:opacity-50 disabled:cursor-not-allowed">
                        Approve Submission
                    </button>

                </div>

            </div>

        </div>

    @else

        <div class="fixed bottom-0 left-0 right-0 z-40 
                    border-t border-slate-200 
                    bg-slate-50
                    pb-[env(safe-area-inset-bottom)]">

            <div class="max-w-7xl mx-auto px-6 py-3 text-center">

                <div class="text-sm font-medium text-slate-700">
                    No actions available
                </div>

                <div class="text-xs text-slate-500 mt-1">
                    This submission is not in a state that allows review actions.
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