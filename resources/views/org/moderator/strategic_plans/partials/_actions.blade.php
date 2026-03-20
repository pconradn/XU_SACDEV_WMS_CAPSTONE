{{-- ========================================= --}}
{{-- MODERATOR ACTIONS (SELF-CONTAINED) --}}
{{-- ========================================= --}}
<div x-data="moderatorActions()" x-cloak>

    {{-- QUILL --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    {{-- ========================================= --}}
    {{-- STICKY ACTION BAR --}}
    {{-- ========================================= --}}
    @if(in_array(trim($submission->status), ['submitted_to_moderator','returned_by_moderator'], true))

        <div 
            class="fixed bottom-0 left-0 right-0 z-40 
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

                {{-- RIGHT --}}
                <div class="flex items-center gap-2">

                    {{-- RETURN --}}
                    <button type="button"
                            @click="openReturn = true"
                            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                        Return for Revision
                    </button>

                    {{-- FORWARD --}}
                    <button type="button"
                            @click="openForward = true"
                            class="inline-flex items-center justify-center gap-2 rounded-lg 
                                   bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">
                        Forward to SACDEV
                    </button>

                </div>
            </div>
        </div>

    @else

        {{-- NO ACTION --}}
        <div 
            class="fixed bottom-0 left-0 right-0 z-40 
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


    {{-- ========================================= --}}
    {{-- RETURN MODAL --}}
    {{-- ========================================= --}}
    <div x-show="openReturn" x-cloak
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-slate-900/50" @click="openReturn=false"></div>

        {{-- MODAL --}}
        <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl">

            <div class="px-5 py-4 border-b">
                <h3 class="text-lg font-semibold">Return Submission</h3>
                <p class="text-sm text-slate-500 mt-1">Provide remarks for revision.</p>
            </div>

            <form method="POST"
                  action="{{ route('org.moderator.strategic_plans.return', $submission) }}"
                  class="p-5 space-y-4"
                  @submit.prevent="submitReturn">

                @csrf

                {{-- QUILL --}}
                <div id="returnEditor" class="h-32 bg-white"></div>
                <input type="hidden" name="moderator_remarks" x-ref="returnRemarks">

                <div class="flex justify-end gap-2">
                    <button type="button"
                            @click="openReturn=false"
                            class="px-4 py-2 border rounded-lg text-sm">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 bg-rose-600 text-white rounded-lg text-sm hover:bg-rose-700">
                        Submit Return
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- ========================================= --}}
    {{-- FORWARD MODAL --}}
    {{-- ========================================= --}}
    <div x-show="openForward" x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center p-4">

        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-slate-900/50" @click="openForward=false"></div>

        {{-- MODAL --}}
        <div class="relative w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-xl">

            {{-- HEADER --}}
            <div class="px-5 py-4 border-b">
                <h3 class="text-lg font-semibold text-slate-900">
                    Forward to SACDEV
                </h3>
            </div>

            {{-- BODY --}}
            <div class="p-5 space-y-4">

                {{-- ICON + MESSAGE --}}
                <div class="flex items-start gap-3">

                    {{-- ICON --}}
                    <div class="mt-1 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    {{-- TEXT --}}
                    <div>
                        <p class="text-sm text-slate-700">
                            Are you sure all information in this Strategic Plan is complete and correct?
                        </p>

                        <p class="text-sm text-slate-500 mt-2">
                            This submission will be forwarded to <span class="font-semibold text-slate-700">SACDEV</span> for official review.
                            You will no longer be able to edit it unless it is returned.
                        </p>
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-5 py-4 border-t flex justify-end gap-2">

                <button type="button"
                        @click="openForward=false"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-sm text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>

                <form method="POST"
                    action="{{ route('org.moderator.strategic_plans.forward', $submission) }}">
                    @csrf

                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                        Yes, Forward Submission
                    </button>
                </form>

            </div>

        </div>
    </div>


    {{-- ========================================= --}}
    {{-- ALPINE + QUILL --}}
    {{-- ========================================= --}}
    <script>
        function moderatorActions() {
            return {
                openReturn: false,
                openForward: false,
                returnQuill: null,
                forwardQuill: null,

                init() {
                    this.$nextTick(() => {

                        this.returnQuill = new Quill('#returnEditor', {
                            theme: 'snow',
                            placeholder: 'Write remarks...'
                        });

                        this.forwardQuill = new Quill('#forwardEditor', {
                            theme: 'snow',
                            placeholder: 'Optional note...'
                        });

                    });
                },

                submitReturn(e) {
                    this.$refs.returnRemarks.value = this.returnQuill.root.innerHTML;
                    e.target.submit();
                },

                submitForward(e) {
                    this.$refs.forwardNote.value = this.forwardQuill.root.innerHTML;
                    e.target.submit();
                }
            }
        }
    </script>

</div>