        {{-- ORG IDENTITY + LOGO --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Organization Identity</h2>
                    <div class="mt-3 text-sm text-slate-700 space-y-1">
                        <div><span class="font-medium">Acronym:</span> {{ $submission->org_acronym ?: '—' }}</div>
                        <div><span class="font-medium">Name:</span> {{ $submission->org_name ?: '—' }}</div>
                    </div>
                </div>

                @if(!empty($submission->logo_path))
                    <div class="shrink-0">
                        <div class="text-xs text-slate-500 mb-1">Logo</div>
                        <img class="h-20 w-20 rounded-lg border border-slate-200 object-cover"
                             src="{{ Storage::url($submission->logo_path) }}"
                             alt="Organization Logo">
                    </div>
                @endif
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-lg border border-slate-200 p-4">
                    <div class="text-sm font-semibold text-slate-800">Mission</div>
                    <div class="mt-2 text-sm text-slate-700 whitespace-pre-line">{{ $submission->mission ?: '—' }}</div>
                </div>
                <div class="rounded-lg border border-slate-200 p-4">
                    <div class="text-sm font-semibold text-slate-800">Vision</div>
                    <div class="mt-2 text-sm text-slate-700 whitespace-pre-line">{{ $submission->vision ?: '—' }}</div>
                </div>
            </div>
        </div>