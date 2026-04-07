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
        <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between">

            <div>
                <h3 class="text-lg font-semibold text-slate-900">
                    Return Submission
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Provide remarks explaining what needs correction.
                </p>
            </div>

            <button @click="openReturn=false"
                    class="text-slate-400 hover:text-slate-600 text-lg leading-none">
                ✕
            </button>

        </div>

        {{-- BODY --}}
        <form method="POST"
              action="{{ route('admin.officer_submissions.return', $submission->id) }}"
              class="p-5 space-y-5">

            @csrf

            {{-- CONTEXT --}}
            <p class="text-sm text-slate-600">
                The organization will be able to edit and resubmit after addressing your remarks.
            </p>

            {{-- WARNING --}}
            <div class="rounded-lg border border-rose-200 bg-rose-50/70 p-3 text-sm text-rose-900">
                This action cannot be undone.
            </div>


            {{-- TEXTAREA (REPLACED QUILL) --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Return Remarks <span class="text-rose-500">*</span>
                </label>

                <textarea name="sacdev_remarks"
                          rows="5"
                          required
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                          placeholder="Enter required changes..."></textarea>

                <p class="text-xs text-slate-500 mt-1">
                    Be specific so the organization can revise quickly.
                </p>
            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-2 pt-2">

                <button type="button"
                        @click="openReturn=false"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 text-sm rounded-lg bg-rose-600 text-white font-semibold hover:bg-rose-700">
                    Confirm Return
                </button>

            </div>

        </form>

    </div>

</div>