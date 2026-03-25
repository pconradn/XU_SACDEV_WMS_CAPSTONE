<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-6">

    {{-- ================= SECTION HEADER ================= --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900">
            Project Overview
        </h3>
        <p class="text-xs text-slate-500">
            Provide a short summary and explain how the project aligns with your organization
        </p>
    </div>

    {{-- ================= DESCRIPTION ================= --}}
    <div>
        <label class="block text-xs font-medium text-slate-700 mb-1">
            Brief Description
        </label>

        <p class="text-xs text-slate-400 mb-2">
            Summarize the project in 1–2 sentences
        </p>

        <textarea name="description"
                  rows="3"
                  required
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none resize-none"
        >{{ old('description', $proposal->description ?? '') }}</textarea>
    </div>

    {{-- ================= ORG LINK ================= --}}
    <div>
        <label class="block text-xs font-medium text-slate-700 mb-1">
            Alignment with Organization
        </label>

        <p class="text-xs text-slate-400 mb-2">
            Explain how this project supports your organization’s mission or purpose
        </p>

        <textarea name="org_link"
                  rows="3"
                  required
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none resize-none"
        >{{ old('org_link', $proposal->org_link ?? '') }}</textarea>
    </div>

    {{-- ================= ORG CLUSTER ================= --}}
    <div class="pt-2 border-t border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">

            <div class="md:col-span-6">
                <label class="block text-xs font-medium text-slate-700 mb-1">
                    Organization Cluster
                </label>

                <input type="text"
                       name="org_cluster"
                       value="{{ old('org_cluster', $proposal->org_cluster ?? '') }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none"
                       placeholder="e.g., Academic, Socio-Civic, Culture & Arts">
            </div>

        </div>
    </div>

</div>