<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= SECTION HEADER ================= --}}
        <div class="flex flex-col">
            <h3 class="text-sm font-semibold text-slate-900">
                Activity Information
            </h3>
            <p class="text-xs text-blue-700">
                Provide details about the solicitation activity, including purpose, timeline, and funding target.
            </p>
        </div>

        @php
        $proposalData = optional($data->document?->project?->proposalDocument?->proposalData);

        $solicitationAmount = optional(
            $proposalData?->fundSources
                ?->where('source_name', 'Solicitation')
                ->first()
        )->amount;

        // raw numeric (used for backend)
        $rawTargetAmount = old('target_amount', $data->target_amount ?? $solicitationAmount ?? null);

        // formatted (for display only)
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

        {{-- ================= BASIC INFO ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Activity Details
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ORGANIZATION --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Organization
                    </label>

                    <input type="text"
                        value="{{ $project->organization->name }}"
                        disabled
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                </div>

                {{-- ACTIVITY NAME --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Activity Name
                    </label>

                    <input type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter activity name"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                {{-- PURPOSE --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Purpose of Solicitation
                    </label>

                    <textarea
                        name="purpose"
                        rows="4"
                        placeholder="Explain purpose of solicitation"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('purpose')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">{{ $purpose }}</textarea>
                </div>

            </div>

        </div>

        {{-- ================= SCHEDULE ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Duration
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- FROM --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        From
                    </label>

                    <input type="date"
                        name="duration_from"
                        value="{{ $durationFrom }}"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('duration_from')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                {{-- TO --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        To
                    </label>

                    <input type="date"
                        name="duration_to"
                        value="{{ $durationTo }}"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('duration_to')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

            </div>

        </div>

        {{-- ================= FUNDING ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Funding Details
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- TARGET --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">
                        Target Amount
                    </label>

                    {{-- DISPLAY ONLY --}}
                    <input type="text"
                        value="Php {{ $formattedTargetAmount }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-slate-50 text-slate-700"
                        readonly>

                    {{-- ACTUAL VALUE (USED IN SUBMISSION) --}}
                    <input type="hidden"
                        name="target_amount"
                        value="{{ $rawTargetAmount }}">
                </div>

                {{-- LETTER COUNT --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Number of Letters
                    </label>

                    <input type="number"
                        name="desired_letter_count"
                        value="{{ $letterCount }}"
                        placeholder="Enter number"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('desired_letter_count')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

            </div>

        </div>

        {{-- ================= LETTER ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Solicitation Letter
            </div>

            @if(!$isReadOnly)
                <input type="url"
                    name="letter_draft_link"
                    value="{{ $link }}"
                    placeholder="Paste Google Docs or Word link (set as commenter access)"
                    class="w-full rounded-lg px-3 py-2 text-sm
                        {{ $errors->has('letter_draft_link')
                            ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                            : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                        focus:ring-2 focus:outline-none transition">
            @endif

            @if(!empty($data?->letter_draft_link))
                <div class="mt-3 text-xs text-slate-600 break-all">
                    Current: {{ $data->letter_draft_link }}
                </div>
            @endif

        </div>

        {{-- ================= APPROVAL ================= --}}
        @php
            $batch = $document?->solicitationBatches?->first();
        @endphp

        @if($batch)
        <div class="border border-emerald-200 rounded-xl bg-emerald-50 p-4">

            <div class="text-xs font-semibold uppercase tracking-wide text-emerald-800">
                SACDEV Approval Details
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                <div>
                    <div class="text-slate-500 text-xs">Approved Letters</div>
                    <div class="font-semibold text-slate-900">
                        {{ $batch->approved_letter_count }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500 text-xs">Series Start</div>
                    <div class="font-semibold text-slate-900">
                        {{ $batch->control_series_start }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500 text-xs">Series End</div>
                    <div class="font-semibold text-slate-900">
                        {{ $batch->control_series_end }}
                    </div>
                </div>

            </div>

        </div>
        @endif

    </div>

</div>