<div x-show="openCreateModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- OVERLAY --}}
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
         @click="openCreateModal=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-xl">

        {{-- ================= HEADER ================= --}}
        <div class="px-6 py-4 border-b border-slate-200 flex items-start justify-between">

            <div>
                <h3 class="text-base font-semibold text-slate-900"
                    x-text="selectedProject ? 'Edit Project' : 'Create Project'">
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    <span x-show="!selectedProject">
                        Add a new project to begin the workflow.
                    </span>
                    <span x-show="selectedProject">
                        Update project details and continue managing its workflow.
                    </span>
                </p>
            </div>

            <button @click="openCreateModal=false"
                class="text-slate-400 hover:text-slate-600 transition">
                ✕
            </button>

        </div>


        {{-- ================= BODY ================= --}}
        <form method="POST"
              :action="selectedProject 
                ? `/org/projects/${selectedProject.id}` 
                : '{{ route('org.projects.store') }}'">

            @csrf

            <template x-if="selectedProject">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="px-6 py-5 space-y-5">

                {{-- TITLE --}}
                <div>
                    <label class="text-[11px] font-medium text-slate-600 uppercase tracking-wide">
                        Project Title
                    </label>

                    <input name="title"
                        x-model="selectedProject ? selectedProject.title : ''"
                        placeholder="Enter project name"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-slate-300 focus:border-slate-400 transition"
                        required>

                    <p class="text-[10px] text-slate-400 mt-1">
                        This will be used across all project documents.
                    </p>
                </div>


                {{-- INFO BOX --}}
                <div class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 flex items-start gap-2">

                    <span class="text-blue-600 text-xs mt-0.5">i</span>

                    <div class="text-[11px] text-slate-700">
                        After creating a project, you can assign a project head and start submitting required documents.
                    </div>

                </div>

            </div>


            {{-- ================= FOOTER ================= --}}
            <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-2">

                <button type="button"
                        @click="openCreateModal=false"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition">
                    Cancel
                </button>

                <button
                    :class="selectedProject 
                        ? 'bg-amber-500 hover:bg-amber-600' 
                        : 'bg-slate-900 hover:bg-slate-800'"
                    class="px-4 py-2 text-sm font-semibold text-white rounded-lg transition">

                    <span x-text="selectedProject ? 'Update Project' : 'Create Project'"></span>

                </button>

            </div>

        </form>

    </div>
</div>