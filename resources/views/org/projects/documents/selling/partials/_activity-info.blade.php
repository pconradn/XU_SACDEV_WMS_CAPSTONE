<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- ACCENT --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Selling Activity Information
                </h3>
                <p class="text-xs text-blue-700 mt-1">
                    Provide details about the selling activity, including purpose, duration, and projected revenue.
                </p>
            </div>
        </div>

        @php
            $activityName = old('activity_name', $data->activity_name ?? $project->title);
            $projectedSales = old('projected_sales', $data->projected_sales ?? '');
            $purpose = old('purpose', $data->purpose ?? '');
            $durationFrom = old('duration_from', $data->duration_from ?? '');
            $durationTo = old('duration_to', $data->duration_to ?? '');
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
                        Activity Name
                    </label>

                    <input
                        type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter activity name"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Projected Sales (₱)
                    </label>

                    <input
                        type="text"
                        id="projectedSales"
                        name="projected_sales"
                        value="{{ isset($projectedSales) ? number_format((float) str_replace(',', '', $projectedSales), 2) : '' }}"
                        readonly
                        class="w-full rounded-lg px-3 py-2 text-xs bg-slate-50
                            {{ $errors->has('projected_sales')
                                ? 'border-rose-500'
                                : 'border-slate-300' }}
                            focus:outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Purpose
                    </label>

                    <textarea
                        name="purpose"
                        rows="3"
                        placeholder="Explain purpose of selling activity (e.g., fundraising, outreach)"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('purpose')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">{{ $purpose }}</textarea>
                </div>

            </div>

        </div>

        {{-- DURATION --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4 hover:bg-slate-50 transition">

            <div class="flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Duration
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-[11px] text-slate-600 mb-1">
                        From
                    </label>

                    <input
                        type="date"
                        name="duration_from"
                        value="{{ $durationFrom }}"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('duration_from')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-[11px] text-slate-600 mb-1">
                        To
                    </label>

                    <input
                        type="date"
                        name="duration_to"
                        value="{{ $durationTo }}"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('duration_to')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

            </div>

        </div>

    </div>

</div>