<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    {{-- LEFT: Org details --}}
    <div class="md:col-span-2 bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Organization Details</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Identity + mission and vision submitted in the Strategic Plan.
                </p>
            </div>

            <div class="shrink-0">
                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                    {{ $submission->org_acronym ? $submission->org_acronym : 'No Acronym' }}
                </span>
            </div>
        </div>

        {{-- Name --}}
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs font-semibold text-slate-500">Organization Name</div>
            <div class="mt-1 text-sm font-semibold text-slate-900">
                {{ $submission->org_name ?: '—' }}
            </div>

            @if(!empty($submission->org_acronym))
                <div class="mt-1 text-xs text-slate-500">
                    Acronym: <span class="font-medium text-slate-700">{{ $submission->org_acronym }}</span>
                </div>
            @endif
        </div>

        {{-- Mission + Vision --}}
        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-xs font-semibold text-slate-500">Mission</div>
                <div class="mt-2 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                    {{ $submission->mission ?: '—' }}
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 p-4">
                <div class="text-xs font-semibold text-slate-500">Vision</div>
                <div class="mt-2 text-sm text-slate-800 whitespace-pre-line leading-relaxed">
                    {{ $submission->vision ?: '—' }}
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Logo --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Logo</h2>
                <p class="mt-1 text-sm text-slate-500">Uploaded branding file (if provided).</p>
            </div>
        </div>

        {{-- Always show a consistent preview box --}}
        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 flex items-center justify-center min-h-[220px]">
            @if(!empty($submission->logo_path))
                <img
                    src="{{ asset('storage/' . $submission->logo_path) }}"
                    alt="Org Logo"
                    class="max-h-48 w-auto object-contain"
                    loading="lazy"
                >
            @else
                <div class="text-center">
                    <div class="text-sm font-semibold text-slate-800">No logo uploaded</div>
                    <div class="mt-1 text-sm text-slate-600">
                        This submission did not include a logo.
                    </div>
                </div>
            @endif
        </div>

        {{-- File meta / placeholder --}}
        <div class="mt-3 rounded-lg border border-slate-200 bg-white px-3 py-2">
            <div class="text-[11px] font-semibold text-slate-500">File</div>
            <div class="text-xs text-slate-700 break-all">
                {{ $submission->logo_original_name ?? '—' }}
            </div>

            @if(!empty($submission->logo_mime) || !empty($submission->logo_size_bytes))
                <div class="mt-1 text-[11px] text-slate-500">
                    @if(!empty($submission->logo_mime))
                        <span class="mr-2">Type: {{ $submission->logo_mime }}</span>
                    @endif
                    @if(!empty($submission->logo_size_bytes))
                        <span>Size: {{ number_format($submission->logo_size_bytes / 1024, 1) }} KB</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
