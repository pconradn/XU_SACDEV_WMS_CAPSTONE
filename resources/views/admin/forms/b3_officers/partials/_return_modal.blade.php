<div x-show="openReturn"
     x-cloak
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50"
         @click="openReturn=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">
                Return Submission
            </h3>

            <button @click="openReturn=false"
                    class="text-slate-400 hover:text-slate-600 text-lg">
                ✕
            </button>
        </div>

        {{-- BODY --}}
        <form method="POST"
              action="{{ route('admin.officer_submissions.return', $submission->id) }}"
              class="p-5 space-y-4">

            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Return Remarks <span class="text-rose-500">*</span>
                </label>

                {{-- QUILL EDITOR --}}
                <div id="returnEditor"
                     class="bg-white border border-slate-300 rounded-lg"></div>

                {{-- HIDDEN INPUT --}}
                <input type="hidden" name="sacdev_remarks" id="returnRemarksInput" required>
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-2 pt-2">

                <button type="button"
                        @click="openReturn=false"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-300 bg-white hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                        onclick="syncReturnRemarks()"
                        class="px-4 py-2 text-sm rounded-lg bg-amber-600 text-white font-semibold hover:bg-amber-700">
                    Confirm Return
                </button>

            </div>

        </form>

    </div>

</div>