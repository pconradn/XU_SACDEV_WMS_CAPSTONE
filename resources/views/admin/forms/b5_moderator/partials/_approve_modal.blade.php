<div x-show="openApprove"
     x-cloak
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50" @click="openApprove=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">
                Approve Submission
            </h3>

            <p class="text-sm text-slate-500 mt-1">
                Are you sure you want to approve this moderator submission?
            </p>
        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-5">

            {{-- CONTEXT --}}
            <div class="flex items-start gap-3">

                <div class="mt-1 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <p class="text-sm text-slate-600">
                    This will finalize the moderator submission and allow the organization to proceed.
                </p>

            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 pt-2">

                <button type="button"
                        @click="openApprove=false"
                        class="px-4 py-2 text-sm border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="button"
                        onclick="document.getElementById('approveForm').submit()"
                        class="px-4 py-2 text-sm bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700">
                    Confirm Approval
                </button>

            </div>

        </div>

    </div>
</div>