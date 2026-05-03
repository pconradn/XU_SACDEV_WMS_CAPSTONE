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

            <button type="button"
                    @click="openEditModal=false"
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

                    <input
                        name="title"
                        x-model="selectedProject.title"
                        placeholder="Enter project name"
                        class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition"
                        required
                    >

                    <p class="text-[10px] text-slate-400 mt-1">
                        Changes will reflect across all related documents.
                    </p>
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-700 mb-2">
                        Project Category
                    </label>

                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i data-lucide="tags" class="w-4 h-4 text-slate-400"></i>
                        </div>

                        <select
                            name="category"
                            id="category"
                            required
                            class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-800 shadow-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20"
                        >
                            <option value="">Select project category...</option>
                            <option value="org_dev" {{ old('category') === 'org_dev' ? 'selected' : '' }}>
                                Organization Development
                            </option>
                            <option value="student_services" {{ old('category') === 'student_services' ? 'selected' : '' }}>
                                Student Services
                            </option>
                            <option value="community_involvement" {{ old('category') === 'community_involvement' ? 'selected' : '' }}>
                                Community Involvement
                            </option>
                        </select>
                    </div>

                    @error('category')
                        <div class="mt-2 text-xs font-medium text-rose-600">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- INFO BOX --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 flex items-start gap-2">

                    <span class="text-amber-600 text-xs mt-0.5">i</span>

                    <div class="text-[11px] text-slate-700">
                        Editing the title or category does not reset approvals or workflow status.
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

                <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-white rounded-lg bg-amber-500 hover:bg-amber-600 transition">
                    Update Project
                </button>

            </div>

        </form>

    </div>
</div>