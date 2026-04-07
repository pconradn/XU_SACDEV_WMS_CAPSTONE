{{-- RETURN MODAL --}}
<div x-show="openReturn" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50" @click="openReturn=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">
                Return to Organization
            </h3>

            <p class="text-sm text-slate-500 mt-1">
                This will send the submission back for revision. Remarks are required.
            </p>
        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-5">

            {{-- ICON + MESSAGE --}}
            <div class="flex items-start gap-3">

                <div class="mt-1 text-rose-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18.364 5.636l-12.728 12.728M6.343 6.343l11.314 11.314"/>
                    </svg>
                </div>

                <div>
                    <p class="text-sm text-slate-700">
                        Are you sure you want to return this submission?
                    </p>

                    <p class="text-sm text-slate-500 mt-2">
                        The organization will be able to edit and resubmit after addressing your remarks.
                    </p>
                </div>

            </div>


            {{-- WARNING --}}
            <div class="rounded-lg border border-rose-200 bg-rose-50/70 p-4 text-sm text-rose-900">

                <div class="font-semibold mb-1">
                    Important
                </div>

                <ul class="list-disc pl-5 space-y-1">
                    <li>This action cannot be undone</li>
                    <li>The submission will be marked as returned</li>
                    <li>All progress will depend on resubmission</li>
                </ul>

            </div>


            {{-- FORM --}}
            <form class="space-y-4"
                  method="POST"
                  action="{{ route('admin.strategic_plans.return', $submission) }}">

                @csrf

                {{-- TEXTAREA (REPLACED QUILL) --}}
                <div>
                    <label class="text-sm font-medium text-slate-700">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="5"
                              required
                              class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                              placeholder="Enter required changes..."></textarea>

                    <p class="text-xs text-slate-500 mt-1">
                        Be specific so the organization can revise quickly.
                    </p>
                </div>


                {{-- ACTIONS --}}
                <div class="flex justify-end gap-2 pt-2">

                    <button type="button"
                            class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
                            @click="openReturn=false">
                        Cancel
                    </button>

                    <button type="submit"
                            class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Return to Organization
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>