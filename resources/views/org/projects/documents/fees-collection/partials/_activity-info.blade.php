<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Collection Activity Information
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide details about the collection activity, including its purpose and duration.
        </p>
    </div>

    @php
        $activityName = old('activity_name', $data->activity_name ?? $project->title);
        $purpose = old('purpose', $data->purpose ?? '');
        $collectionFrom = old('collection_from', $data->collection_from ?? '');
        $collectionTo = old('collection_to', $data->collection_to ?? '');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- ACTIVITY NAME --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Name of Activity
            </label>

            <input
                type="text"
                name="activity_name"
                value="{{ $activityName }}"
                placeholder="Enter collection activity name"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                This will be used as the official name of the collection activity.
            </p>
        </div>


        {{-- PURPOSE --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Purpose of Collection
            </label>

            <textarea
                name="purpose"
                rows="3"
                placeholder="Describe the purpose of the collection"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif>{{ $purpose }}</textarea>

            <p class="text-[11px] text-slate-400 mt-1">
                Explain why the collection was conducted (e.g., fundraising, contributions, participation fees).
            </p>
        </div>


        {{-- COLLECTION FROM --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Collection From
            </label>

            <input
                type="date"
                name="collection_from"
                value="{{ $collectionFrom }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                Start date when collections began.
            </p>
        </div>


        {{-- COLLECTION TO --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Collection To
            </label>

            <input
                type="date"
                name="collection_to"
                value="{{ $collectionTo }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                End date of the collection period.
            </p>
        </div>

    </div>

</div>