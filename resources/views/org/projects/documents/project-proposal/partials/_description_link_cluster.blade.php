<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= SECTION HEADER ================= --}}
        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Project Overview
            </h3>
            <p class="text-xs text-blue-700">
                Provide a short summary and explain how the project aligns with your organization
            </p>
        </div>

        {{-- ================= DESCRIPTION ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            {{-- LABEL --}}
            <label class="block text-xs font-medium text-slate-600 mb-1">
                Brief Description
            </label>

            {{-- SUBTEXT --}}
            <p class="text-xs text-blue-700 mb-2">
                Summarize the project in 1–2 sentences
            </p>

            <textarea name="description"
                rows="3"
                required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none 
                       resize-none transition"
            >{{ old('description', $proposal->description ?? '') }}</textarea>

        </div>

        {{-- ================= ORG LINK ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            {{-- LABEL --}}
            <label class="block text-xs font-medium text-slate-600 mb-1">
                Alignment with Organization
            </label>

            {{-- SUBTEXT --}}
            <p class="text-xs text-blue-700 mb-2">
                Explain how this project supports your organization’s mission or purpose
            </p>

            <textarea name="org_link"
                rows="3"
                required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none 
                       resize-none transition"
            >{{ old('org_link', $proposal->org_link ?? '') }}</textarea>

        </div>

        @php
            $clusterName = $project->organization?->cluster?->name ?? '—';
        @endphp

        {{-- ⚠️ DO NOT TOUCH (LOGIC PRESERVED) --}}
        <input type="hidden" name="org_cluster" value="{{ $clusterName }}">

    </div>

</div>