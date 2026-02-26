<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="text-sm font-semibold text-slate-900">Project Summary</div>

    <div class="mt-4 grid grid-cols-1 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700">
                Brief Description (1–2 sentences)
            </label>
            <textarea name="description"
                      class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                      rows="3"
                      required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">
                Link of the Project with the Organization (mission/purpose)
            </label>
            <textarea name="org_link"
                      class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                      rows="3"
                      required>{{ old('org_link') }}</textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-slate-700">
                    Org Cluster (optional)
                </label>
                <input type="text" name="org_cluster" value="{{ old('org_cluster') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                       placeholder="e.g., Academic, Socio-Civic, Culture & Arts, etc.">
            </div>
        </div>
    </div>
</div>