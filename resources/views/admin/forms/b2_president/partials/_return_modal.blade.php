<div x-show="openReturn"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-black/40" @click="openReturn=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200 p-6">

        {{-- TITLE --}}
        <h3 class="text-lg font-semibold text-slate-900">
            Return to Organization
        </h3>

        <p class="text-sm text-slate-500 mt-1">
            Provide remarks explaining what needs correction.
        </p>

        {{-- CONTEXT --}}
        <p class="text-sm text-slate-600 mt-4">
            The organization will be able to edit and resubmit after addressing your remarks.
        </p>

        {{-- WARNING --}}
        <div class="mt-3 rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800">
            This action cannot be undone.
        </div>

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('admin.b2.president.return', $registration) }}"
              class="mt-4 space-y-4">

            @csrf

            {{-- TEXTAREA (REPLACED QUILL) --}}
            <div>
                <label class="text-sm font-medium text-slate-700">
                    Remarks
                </label>

                <textarea name="sacdev_remarks"
                          rows="5"
                          required
                          class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                          placeholder="Enter required changes..."></textarea>

                <p class="text-xs text-slate-500 mt-1">
                    Be specific so the organization can revise quickly.
                </p>
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2">

                <button type="button"
                        @click="openReturn=false"
                        class="px-4 py-2 text-sm border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-rose-600 text-white rounded-lg font-semibold hover:bg-rose-700">
                    Return Submission
                </button>

            </div>

        </form>

    </div>

</div>