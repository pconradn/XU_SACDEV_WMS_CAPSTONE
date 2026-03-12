<div class="border border-slate-300">

    {{-- Top Label --}}
    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Project Summary
        </div>
    </div>

    <div class="px-4 pb-3 pt-2 space-y-4">

        {{-- Brief Description --}}
        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Brief Description (1–2 sentences):
            </label>

            <textarea name="description"
                      class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                      rows="2"
                      required>{{ old('description', $proposal->description ?? '') }}</textarea>
        </div>

        {{-- Org Link --}}
        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Link of the Project with the Organization (mission/purpose):
            </label>

            <textarea name="org_link"
                      class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                      rows="2"
                      required>{{ old('org_link', $proposal->org_link ?? '') }}</textarea>
        </div>

        {{-- Divider --}}
        <div class="border-t border-slate-300"></div>

        {{-- Org Cluster --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-12 items-start">

            <div class="md:col-span-7">
                <label class="block text-[12px] font-medium text-slate-700">
                    Org Cluster:
                </label>

                <input type="text"
                       name="org_cluster"
                       value="{{ old('org_cluster', $proposal->org_cluster ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                       placeholder="e.g., Academic, Socio-Civic, Culture & Arts, etc.">
            </div>

        </div>

    </div>

</div>