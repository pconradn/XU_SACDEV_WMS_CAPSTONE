<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Selling Activity Information
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide details about the selling activity, including purpose, duration, and expected revenue.
        </p>
    </div>

    @php
        $activityName = old('activity_name', $data->activity_name ?? $project->title);
        $projectedSales = old('projected_sales', $data->projected_sales ?? '');
        $purpose = old('purpose', $data->purpose ?? '');
        $durationFrom = old('duration_from', $data->duration_from ?? '');
        $durationTo = old('duration_to', $data->duration_to ?? '');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- ACTIVITY NAME --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Name of Selling Activity
            </label>

            <input
                type="text"
                name="activity_name"
                value="{{ $activityName }}"
                placeholder="Enter activity name"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <p class="text-[11px] text-slate-400 mt-1">
                This will be used as the official name of the selling activity.
            </p>
        </div>


        {{-- PROJECTED SALES --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Projected Sales (₱)
            </label>

            <input
                type="number"
                step="0.01"
                name="projected_sales"
                value="{{ $projectedSales }}"
                placeholder="0.00"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <p class="text-[11px] text-slate-400 mt-1">
                Estimated total revenue from the selling activity.
            </p>
        </div>


        {{-- PURPOSE --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Purpose of Selling Activity
            </label>

            <textarea
                name="purpose"
                rows="4"
                placeholder="Explain the purpose and goals of this selling activity..."
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $purpose }}</textarea>

            <p class="text-[11px] text-slate-400 mt-1">
                Describe why the organization is conducting this activity (e.g., fundraising, outreach, etc.).
            </p>
        </div>


        {{-- DURATION FROM --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Duration From
            </label>

            <input
                type="date"
                name="duration_from"
                value="{{ $durationFrom }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>


        {{-- DURATION TO --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Duration To
            </label>

            <input
                type="date"
                name="duration_to"
                value="{{ $durationTo }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

    </div>

</div>