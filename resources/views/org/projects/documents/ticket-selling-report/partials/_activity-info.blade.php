<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="ticket" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Ticket Selling Activity Information
                </h3>
                <p class="text-xs text-blue-700 mt-1">
                    Provide details about the ticket selling activity, including duration and total sales generated.
                </p>
            </div>
        </div>

        @php
            $activityName = old('activity_name', $data->activity_name ?? $project->title);
            $sellingFrom = old('selling_from', $data->selling_from ??  '');
            $sellingTo = old('selling_to', $data->selling_to ?? '');
        @endphp

        {{-- CONTENT --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-5 hover:bg-slate-50 transition">

            <div class="grid grid-cols-1 gap-5">

                {{-- ACTIVITY NAME --}}
                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Name of Activity
                    </label>

                    <input
                        type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter official ticket selling activity name"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition"
                        @if($isReadOnly) disabled @endif>

                    @error('activity_name')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DATES + TOTAL --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- FROM --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-600 mb-1">
                            Selling From
                        </label>

                        <input
                            type="date"
                            name="selling_from"
                            value="{{ $sellingFrom }}"
                            class="w-full rounded-lg px-3 py-2 text-xs
                                {{ $errors->has('selling_from')
                                    ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                            @if($isReadOnly) disabled @endif>

                        @error('selling_from')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TO --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-600 mb-1">
                            Selling To
                        </label>

                        <input
                            type="date"
                            name="selling_to"
                            value="{{ $sellingTo }}"
                            class="w-full rounded-lg px-3 py-2 text-xs
                                {{ $errors->has('selling_to')
                                    ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                            @if($isReadOnly) disabled @endif>

                        @error('selling_to')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- TOTAL --}}
                    <div>
                        <label class="block text-[11px] font-medium text-slate-600 mb-1 text-right">
                            Total Ticket Sales (₱)
                        </label>

                        <input
                            type="text"
                            id="totalTicketSalesDisplay"
                            readonly
                            value="0.00"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-right text-slate-800">
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>