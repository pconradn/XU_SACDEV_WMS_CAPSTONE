<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= SECTION HEADER ================= --}}
        <div class="flex flex-col">
            <h3 class="text-sm font-semibold text-slate-900">
                Selling Activity Information
            </h3>
            <p class="text-xs text-blue-700">
                Provide details about the selling activity, including purpose, duration, and projected revenue.
            </p>
        </div>

        @php
            $activityName = old('activity_name', $data->activity_name ?? $project->title);
            $projectedSales = old('projected_sales', $data->projected_sales ?? '');
            $purpose = old('purpose', $data->purpose ?? '');
            $durationFrom = old('duration_from', $data->duration_from ?? '');
            $durationTo = old('duration_to', $data->duration_to ?? '');
        @endphp

        {{-- ================= BASIC INFO ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Activity Details
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ACTIVITY NAME --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Activity Name
                    </label>

                    <input
                        type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter activity name"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                {{-- PROJECTED SALES --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Projected Sales (₱)
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="projected_sales"
                        value="{{ $projectedSales }}"
                        placeholder="0.00"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('projected_sales')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                {{-- PURPOSE --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Purpose
                    </label>

                    <textarea
                        name="purpose"
                        rows="4"
                        placeholder="Explain purpose of selling activity (e.g., fundraising, outreach)"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('purpose')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">{{ $purpose }}</textarea>
                </div>

            </div>

        </div>

        {{-- ================= DURATION ================= --}}
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

                    <input
                        type="date"
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

                    <input
                        type="date"
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

    </div>

</div>