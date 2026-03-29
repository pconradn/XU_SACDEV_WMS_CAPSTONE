<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Activity Information
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide details about the solicitation activity, including purpose, timeline, and target funding.
        </p>
    </div>


    @php
        $activityName = old('activity_name', $data->activity_name ?? $project->title);
        $purpose = old('purpose', $data->purpose ?? '');
        $durationFrom = old('duration_from', $data->duration_from ?? '');
        $durationTo = old('duration_to', $data->duration_to ?? '');
        $targetAmount = old('target_amount', $data->target_amount ?? '');
        $letterCount = old('desired_letter_count', $data->desired_letter_count ?? '');
        $link = old('letter_draft_link', $data->letter_draft_link ?? '');
    @endphp


    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- ORGANIZATION --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Organization
            </label>

            <input
                type="text"
                value="{{ $project->organization->name }}"
                disabled
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
        </div>


        {{-- ACTIVITY NAME --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Activity Name
            </label>

            <input
                type="text"
                name="activity_name"
                value="{{ $activityName }}"
                placeholder="Enter activity name"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>


        {{-- PURPOSE --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Purpose of Solicitation
            </label>

            <textarea
                name="purpose"
                rows="4"
                placeholder="Explain why this solicitation is being conducted..."
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $purpose }}</textarea>
        </div>


        {{-- DURATION --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Duration From
            </label>

            <input
                type="date"
                name="duration_from"
                value="{{ $durationFrom }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Duration To
            </label>

            <input
                type="date"
                name="duration_to"
                value="{{ $durationTo }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>


        {{-- TARGET AMOUNT --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Target Amount (₱)
            </label>

            <input
                type="number"
                name="target_amount"
                value="{{ $targetAmount }}"
                placeholder="0.00"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>


        {{-- LETTER COUNT --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Desired Number of Letters
            </label>

            <input
                type="number"
                name="desired_letter_count"
                value="{{ $letterCount }}"
                placeholder="Enter number of letters"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>


        {{-- LETTER DRAFT --}}
        <div class="md:col-span-2">

            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Solicitation Letter Draft
            </label>

            <div class="mt-3 border border-slate-200 rounded-xl bg-slate-50 p-4 space-y-3">

                {{-- EXISTING LINK --}}
                @if(!empty($data?->letter_draft_link))
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                    <div class="text-sm text-slate-700 break-all">
                        <span class="text-slate-500 text-xs">Current Link:</span><br>
                        <span class="font-medium">{{ $data->letter_draft_link }}</span>
                    </div>

                    <a
                        href="{{ $data->letter_draft_link }}"
                        target="_blank"
                        class="px-3 py-2 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Open Document
                    </a>

                </div>
                @endif


                {{-- INPUT --}}
                @if(!$isReadOnly)
                <input
                    type="url"
                    name="letter_draft_link"
                    value="{{ $link }}"
                    placeholder="Paste Google Docs or Word link"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @endif


                {{-- HELPER --}}
                <div class="text-xs text-slate-500 leading-relaxed">
                    Upload your letter to <span class="font-medium">Google Docs</span> or 
                    <span class="font-medium">Microsoft Word Online</span>, then share as 
                    <span class="font-medium">Commenter</span> so SACDEV can review it.
                </div>

            </div>

        </div>


        {{-- SACDEV APPROVAL --}}
        @php
            $batch = $document?->solicitationBatches?->first();
        @endphp

        @if($batch)
        <div class="md:col-span-2">

            <div class="border border-emerald-200 rounded-xl bg-emerald-50 p-4">

                <div class="text-xs font-semibold uppercase tracking-wide text-emerald-800">
                    SACDEV Approval Details
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                    <div>
                        <div class="text-slate-500 text-xs">
                            Approved Letters
                        </div>
                        <div class="font-semibold text-slate-900">
                            {{ $batch->approved_letter_count }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">
                            Control Series Start
                        </div>
                        <div class="font-semibold text-slate-900">
                            {{ $batch->control_series_start }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-500 text-xs">
                            Control Series End
                        </div>
                        <div class="font-semibold text-slate-900">
                            {{ $batch->control_series_end }}
                        </div>
                    </div>

                </div>

            </div>

        </div>
        @endif

    </div>

</div>