<div x-show="openEditModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- OVERLAY --}}
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
         @click="openEditModal=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-xl">

        {{-- ================= HEADER ================= --}}
        <div class="px-6 py-4 border-b border-slate-200 flex items-start justify-between">

            <div>
                <h3 class="text-base font-semibold text-slate-900">
                    Edit Project
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Update project details without affecting its workflow progress.
                </p>
            </div>

            <button @click="openEditModal=false"
                class="text-slate-400 hover:text-slate-600 transition">
                ✕
            </button>

        </div>


        {{-- ================= FORM ================= --}}
        <form method="POST"
              :action="selectedProject ? `/org/projects/${selectedProject.id}` : '#'">

            @csrf
            @method('PUT')

            {{-- ================= BODY ================= --}}
            <div class="px-6 py-5 space-y-5">

                {{-- TITLE --}}
                <div>
                    <label class="text-[11px] font-medium text-slate-600 uppercase tracking-wide">
                        Project Title
                    </label>

                    <input name="title"
                        x-model="selectedProject.title"
                        placeholder="Enter project name"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition"
                        required>

                    <p class="text-[10px] text-slate-400 mt-1">
                        Changes will reflect across all related documents.
                    </p>
                </div>


                {{-- INFO BOX --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 flex items-start gap-2">

                    <span class="text-amber-600 text-xs mt-0.5">i</span>

                    <div class="text-[11px] text-slate-700">
                        Editing the title does not reset approvals or workflow status.
                    </div>

                </div>

            </div>


            {{-- ================= FOOTER ================= --}}
            <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-2">

                <button type="button"
                        @click="openEditModal=false"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                    Cancel
                </button>

                <button
                    class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-amber-500 hover:bg-amber-600 transition">
                    Update Project
                </button>

            </div>

        </form>

    </div>
</div>