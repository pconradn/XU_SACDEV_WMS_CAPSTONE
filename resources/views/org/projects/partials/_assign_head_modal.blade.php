<div x-show="openAssignHeadModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- OVERLAY --}}
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
         @click="openAssignHeadModal = false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-xl">

        {{-- ================= HEADER ================= --}}
        <div class="px-6 py-4 border-b border-slate-200 flex items-start justify-between">

            <div>
                <h3 class="text-base font-semibold text-slate-900">
                    Assign Project Head
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Select an officer who will be responsible for managing this project’s workflow and submissions.
                </p>
            </div>

            <button @click="openAssignHeadModal = false"
                class="text-slate-400 hover:text-slate-600 transition">
                ✕
            </button>

        </div>


        {{-- ================= FORM ================= --}}
        <form method="POST"
              :action="selectedProject ? `/org/assign-project-heads/${selectedProject.id}` : '#'">

            @csrf

            {{-- ================= BODY ================= --}}
            <div class="px-6 py-5 space-y-5">

                {{-- SELECT OFFICER --}}
                <div>
                    <label class="text-[11px] font-medium text-slate-600 uppercase tracking-wide">
                        Select Officer
                    </label>

                    <select name="officer_id"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-slate-300 focus:border-slate-400 transition"
                        required>

                        <option value="">-- Choose an officer --</option>

                        @foreach($officers as $o)
                            <option value="{{ $o->id }}">
                                {{ $o->full_name }} — {{ $o->email }}
                            </option>
                        @endforeach

                    </select>

                    @error('officer_id')
                        <div class="mt-1 text-xs text-rose-600">
                            {{ $message }}
                        </div>
                    @enderror

                    <p class="text-[10px] text-slate-400 mt-1">
                        Only active officers can be assigned as project heads.
                    </p>
                </div>


                {{-- INFO BOX --}}
                <div class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 flex items-start gap-2">

                    <span class="text-blue-600 text-xs mt-0.5">i</span>

                    <div class="text-[11px] text-slate-700 space-y-1">
                        <p>
                            The assigned project head will:
                        </p>
                        <ul class="list-disc ml-4 space-y-0.5">
                            <li>Submit and manage project documents</li>
                            <li>Be responsible for workflow progress</li>
                            <li>Receive approval-related actions</li>
                        </ul>
                    </div>

                </div>


                {{-- WARNING BOX (ONLY IF REASSIGNING) --}}
                <template x-if="selectedProject">
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 flex items-start gap-2">

                        <span class="text-amber-600 text-xs mt-0.5">!</span>

                        <div class="text-[11px] text-slate-700">
                            Reassigning will change responsibility but will not remove existing documents or approvals.
                        </div>

                    </div>
                </template>

            </div>


            {{-- ================= FOOTER ================= --}}
            <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-2">

                <button type="button"
                        @click="openAssignHeadModal = false"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-slate-900 hover:bg-slate-800 transition">
                    Assign Project Head
                </button>

            </div>

        </form>

    </div>
</div>