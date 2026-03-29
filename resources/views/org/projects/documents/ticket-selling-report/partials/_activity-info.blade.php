<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Ticket Selling Activity Information
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide details about the ticket selling activity, including duration and total sales generated.
        </p>
    </div>

    @php
        $activityName = old('activity_name', $data->activity_name ?? $project->title);
        $sellingFrom = old('selling_from', $data->selling_from ?? '');
        $sellingTo = old('selling_to', $data->selling_to ?? '');
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
                placeholder="Enter activity name"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                This will be used as the official name of the ticket selling activity.
            </p>
        </div>


        {{-- SELLING FROM --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Selling From
            </label>

            <input
                type="date"
                name="selling_from"
                value="{{ $sellingFrom }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                Start date of ticket selling.
            </p>
        </div>


        {{-- SELLING TO --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Selling To
            </label>

            <input
                type="date"
                name="selling_to"
                value="{{ $sellingTo }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif>

            <p class="text-[11px] text-slate-400 mt-1">
                End date of ticket selling.
            </p>
        </div>


        {{-- TOTAL SALES --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Total Ticket Sales (₱)
            </label>

            <div
                id="totalTicketSalesDisplay"
                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-900">
                ₱ 0.00
            </div>

            <p class="text-[11px] text-slate-400 mt-1">
                Automatically calculated based on ticket entries recorded below.
            </p>
        </div>

    </div>

</div>