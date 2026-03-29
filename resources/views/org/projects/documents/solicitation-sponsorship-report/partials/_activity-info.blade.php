<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Solicitation Activity Information
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide details about the activity where solicitation was conducted, including purpose, duration, and financial summary.
        </p>
    </div>

    @php
        $activityName = old('activity_name', $data->activity_name ?? $project->title);
        $purpose = old('purpose', $data->purpose ?? '');
        $from = old('solicitation_from', $data->solicitation_from ?? '');
        $to = old('solicitation_to', $data->solicitation_to ?? '');
        $letters = old('approved_letters_distributed', $data->approved_letters_distributed ?? '');
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
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <p class="text-[11px] text-slate-400 mt-1">
                This refers to the activity where solicitation efforts were conducted.
            </p>
        </div>


        {{-- PURPOSE --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Purpose of Solicitation
            </label>

            <textarea
                name="purpose"
                rows="4"
                placeholder="Explain the purpose of the solicitation (e.g., fundraising, support for project expenses)..."
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $purpose }}</textarea>

            <p class="text-[11px] text-slate-400 mt-1">
                Describe why the organization conducted solicitation activities.
            </p>
        </div>


        {{-- SOLICITATION FROM --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Solicitation From
            </label>

            <input
                type="date"
                name="solicitation_from"
                value="{{ $from }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <p class="text-[11px] text-slate-400 mt-1">
                Start date of the solicitation period.
            </p>
        </div>


        {{-- SOLICITATION TO --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Solicitation To
            </label>

            <input
                type="date"
                name="solicitation_to"
                value="{{ $to }}"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <p class="text-[11px] text-slate-400 mt-1">
                End date of the solicitation period.
            </p>
        </div>


        {{-- APPROVED LETTERS --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Approved Letters Distributed
            </label>

            <input
                type="number"
                name="approved_letters_distributed"
                value="{{ $letters }}"
                placeholder="Enter number of approved letters"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <p class="text-[11px] text-slate-400 mt-1">
                Total number of solicitation letters approved and distributed.
            </p>
        </div>


        {{-- TOTAL AMOUNT RAISED --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Total Amount Raised (₱)
            </label>

            <input
                type="text"
                id="totalAmountRaised"
                readonly
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-slate-50">

            <p class="text-[11px] text-slate-400 mt-1">
                Automatically calculated based on recorded contributions.
            </p>
        </div>

    </div>

</div>