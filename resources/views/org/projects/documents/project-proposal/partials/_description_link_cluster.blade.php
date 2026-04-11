<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>

    <div class="p-4 space-y-5">

        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Project Overview
                </h3>
                <p class="text-[11px] text-slate-500">
                    Provide a short summary and explain alignment with your organization
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

            <div class="flex items-center gap-2">
                <i data-lucide="align-left" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Brief Description
                </span>
            </div>

            <div class="text-[11px] text-slate-500">
                Summarize the project in 1–2 sentences
            </div>

            <textarea name="description"
                rows="3"
                required
                class="w-full rounded-lg border px-3 py-2 text-xs
                {{ $errors->has('description') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                focus:ring-2 focus:outline-none resize-none transition"
            >{{ old('description', $proposal->description ?? '') }}</textarea>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

            <div class="flex items-center gap-2">
                <i data-lucide="link" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Alignment with Organization
                </span>
            </div>

            <div class="text-[11px] text-slate-500">
                Explain how this project supports your organization’s mission or purpose
            </div>

            <textarea name="org_link"
                rows="3"
                required
                class="w-full rounded-lg border px-3 py-2 text-xs
                {{ $errors->has('org_link') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                focus:ring-2 focus:outline-none resize-none transition"
            >{{ old('org_link', $proposal->org_link ?? '') }}</textarea>

        </div>

        @php
            $clusterName = $project->organization?->cluster?->name ?? '—';
        @endphp

        <input type="hidden" name="org_cluster" value="{{ $clusterName }}">

    </div>

</div>