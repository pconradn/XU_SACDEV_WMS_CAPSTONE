<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- ACCENT --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="megaphone" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Activity Information
                </h3>
                <p class="text-xs text-blue-700 mt-1">
                    Provide details about the solicitation activity, including purpose, timeline, and funding target.
                </p>
            </div>
        </div>

        @php
        $proposalData = optional($project?->proposalDocument?->proposalData);

        $solicitationAmount = optional(
            $proposalData?->fundSources
                ?->where('source_name', 'Solicitation')
                ->first()
        )->amount;

        $rawTargetAmount = old('target_amount', $data->target_amount ?? $solicitationAmount ?? null);

        $formattedTargetAmount = $rawTargetAmount !== null
            ? number_format((float) $rawTargetAmount, 2, '.', ',')
            : '';
        @endphp

        @php
            $activityName = old('activity_name', $data->activity_name ?? $project->title);
            $purpose = old('purpose', $data->purpose ?? '');
            $durationFrom = old('duration_from', $data->duration_from ?? '');
            $durationTo = old('duration_to', $data->duration_to ?? '');
            $targetAmount = $solicitationAmount;
            $letterCount = old('desired_letter_count', $data->desired_letter_count ?? '');
            $link = old('letter_draft_link', $data->letter_draft_link ?? '');
        @endphp

        {{-- BASIC --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4 hover:bg-slate-50 transition">

            <div class="flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Activity Details
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Organization
                    </label>

                    <input type="text"
                        value="{{ $project->organization->name }}"
                        disabled
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Activity Name
                    </label>

                    <input type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter activity name"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Purpose of Solicitation
                    </label>

                    <textarea
                        name="purpose"
                        rows="3"
                        placeholder="Explain purpose of solicitation"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('purpose')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">{{ $purpose }}</textarea>
                </div>

            </div>

        </div>

        {{-- SCHEDULE --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4 hover:bg-slate-50 transition">

            <div class="flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Duration
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-[11px] text-slate-600 mb-1">From</label>
                    <input type="date"
                        name="duration_from"
                        value="{{ $durationFrom }}"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('duration_from')
                                ? 'border-rose-500 focus:ring-rose-500'
                                : 'border-slate-300 focus:ring-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-[11px] text-slate-600 mb-1">To</label>
                    <input type="date"
                        name="duration_to"
                        value="{{ $durationTo }}"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('duration_to')
                                ? 'border-rose-500 focus:ring-rose-500'
                                : 'border-slate-300 focus:ring-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

            </div>

        </div>

        {{-- FUNDING --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4 hover:bg-slate-50 transition">

            <div class="flex items-center gap-2">
                <i data-lucide="wallet" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Funding Details
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Target Amount
                    </label>

                    <input type="text"
                        value="Php {{ $formattedTargetAmount }}"
                        class="w-full border rounded-lg px-3 py-2 text-xs bg-slate-50 text-slate-700"
                        readonly>

                    <input type="hidden"
                        name="target_amount"
                        value="{{ $rawTargetAmount }}">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Number of Letters
                    </label>

                    <input type="number"
                        name="desired_letter_count"
                        value="{{ $letterCount }}"
                        placeholder="Enter number"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('desired_letter_count')
                                ? 'border-rose-500 focus:ring-rose-500'
                                : 'border-slate-300 focus:ring-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

            </div>

        </div>

        {{-- LETTER --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3 hover:bg-slate-50 transition">

            <div class="flex items-center gap-2">
                <i data-lucide="file-text" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Solicitation Letter
                </span>
            </div>

            @if(!$isReadOnly)
                <input type="url"
                    name="letter_draft_link"
                    value="{{ $link }}"
                    placeholder="Paste Google Docs or Word link"
                    class="w-full rounded-lg px-3 py-2 text-xs
                        {{ $errors->has('letter_draft_link')
                            ? 'border-rose-500 focus:ring-rose-500'
                            : 'border-slate-300 focus:ring-blue-500' }}
                        focus:ring-2 focus:outline-none transition">

                <p class="text-[11px] text-blue-600">
                    This will be reviewed by SACDEV and OSA. Please ensure the document is complete and check for any comments or required revisions.
                </p>
            @endif

            @if(!empty($data?->letter_draft_link))
                <div class="text-[11px] text-slate-600 break-all">
                    Current: {{ $data->letter_draft_link }}
                </div>
            @endif

        </div>

        {{-- APPROVAL --}}
        @php
            $batch = $document?->solicitationBatches?->first();
        @endphp
        @if($batch)
        <div class="rounded-xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white p-4 space-y-4">

            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 border border-emerald-200 flex items-center justify-center">
                    <i data-lucide="badge-check" class="w-4 h-4 text-emerald-600"></i>
                </div>

                <div>
                    <div class="text-[11px] font-semibold uppercase tracking-wide text-emerald-800">
                        SACDEV Approval Details
                    </div>
                    <p class="text-[11px] text-emerald-700 mt-1">
                        These values are provided after SACDEV review. Use them for official tracking and documentation.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">

                <div class="rounded-lg border border-slate-200 bg-white p-3">
                    <div class="flex items-center gap-2 text-slate-500 mb-1">
                        <i data-lucide="files" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Approved Letters</span>
                    </div>
                    <div class="font-semibold text-slate-900 text-sm">
                        {{ $batch->approved_letter_count }}
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-3">
                    <div class="flex items-center gap-2 text-slate-500 mb-1">
                        <i data-lucide="hash" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Series Start</span>
                    </div>
                    <div class="font-semibold text-slate-900 text-sm">
                        {{ $batch->control_series_start }}
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-3">
                    <div class="flex items-center gap-2 text-slate-500 mb-1">
                        <i data-lucide="hash" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Series End</span>
                    </div>
                    <div class="font-semibold text-slate-900 text-sm">
                        {{ $batch->control_series_end }}
                    </div>
                </div>

            </div>

        </div>
        @endif

    </div>

</div>