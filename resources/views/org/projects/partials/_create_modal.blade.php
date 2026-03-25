<div x-show="openCreateModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50" @click="openCreateModal=false"></div>

    <div class="relative w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-xl p-6">

        {{-- TITLE --}}
        <h3 class="text-lg font-semibold text-slate-900 mb-4"
            x-text="selectedProject ? 'Edit Project' : 'Add Project'">
        </h3>

        {{-- FORM --}}
        <form method="POST"
              :action="selectedProject 
                ? `/org/projects/${selectedProject.id}` 
                : '{{ route('org.projects.store') }}'">

            @csrf

            {{-- METHOD FOR UPDATE --}}
            <template x-if="selectedProject">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="space-y-4">

                {{-- TITLE INPUT --}}
                <div>
                    <label class="text-xs uppercase text-slate-500">Title</label>
                    <input name="title"
                           x-model="selectedProject ? selectedProject.title : ''"
                           class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
                           required>
                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="mt-6 flex justify-end gap-2">

                <button type="button"
                        @click="openCreateModal=false"
                        class="px-4 py-2 border border-slate-300 rounded-lg text-sm hover:bg-slate-50">
                    Cancel
                </button>

                <button
                    :class="selectedProject 
                        ? 'bg-amber-500 hover:bg-amber-600' 
                        : 'bg-slate-900 hover:bg-slate-800'"
                    class="px-4 py-2 text-white rounded-lg text-sm font-semibold transition"
                    x-text="selectedProject ? 'Update Project' : 'Save Project'">
                </button>

            </div>

        </form>

    </div>
</div>