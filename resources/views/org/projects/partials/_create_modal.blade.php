<div
    x-show="openCreateModal"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/50 backdrop-blur-sm px-4"
    style="display: none;"
>
    <div
        @click.away="openCreateModal = false"
        x-transition
        class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden"
    >

        <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-b from-amber-50 to-white">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-600">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">
                            Add New Project
                        </h3>

                        <p class="mt-1 text-xs leading-5 text-slate-500">
                            Create a project under the current organization and school year context.
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="openCreateModal = false"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('org.projects.store') }}">
            @csrf

            <div class="px-5 py-5 space-y-5">

                <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3">
                    <div class="flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600 mt-0.5"></i>
                        <div>
                            <div class="text-xs font-semibold text-blue-800">
                                Project setup
                            </div>
                            <p class="mt-1 text-xs leading-5 text-blue-700">
                                The project title and category will be used for organizing the project workflow.
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-700 mb-2">
                        Project Title
                    </label>

                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i data-lucide="folder-open" class="w-4 h-4 text-slate-400"></i>
                        </div>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title') }}"
                            required
                            maxlength="255"
                            placeholder="Enter project title..."
                            class="w-full rounded-2xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20"
                        >
                    </div>

                    @error('title')
                        <div class="mt-2 text-xs font-medium text-rose-600">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

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

            </div>

            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-xs text-slate-500">
                    This project will be added to the active organization context.
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button
                        type="button"
                        @click="openCreateModal = false"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-amber-700"
                    >
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Project
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>