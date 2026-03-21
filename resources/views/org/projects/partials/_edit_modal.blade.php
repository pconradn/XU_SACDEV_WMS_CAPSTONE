<div x-show="openEditModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50" @click="openEditModal=false"></div>

    <div class="relative w-full max-w-lg bg-white rounded-2xl border border-slate-200 shadow-xl p-6">

        <h3 class="text-lg font-semibold text-slate-900 mb-4">
            Edit Project
        </h3>

        <form method="POST"
              :action="selectedProject ? `/org/projects/${selectedProject.id}` : '#'">
            @csrf
            @method('PUT')

            <div class="space-y-4">

                <div>
                    <label class="text-xs uppercase text-slate-500">Title</label>
                    <input name="title"
                           x-model="selectedProject.title"
                           class="mt-1 w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
                           required>
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button"
                        @click="openEditModal=false"
                        class="px-4 py-2 border rounded-lg text-sm">
                    Cancel
                </button>

                <button class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-semibold">
                    Update
                </button>
            </div>

        </form>

    </div>
</div>