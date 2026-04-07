<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Selling Activity Information
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                Provide key details about the selling activity including schedule and overall sales performance.
            </p>
        </div>

        @php
            $sellingApplication = $project->documents->where('form_type_id',5)->first()->sellingApplication;
            $activityName = old('activity_name', $data->activity_name ?? $project->title);
            $sellingFrom = old('selling_from', $data->selling_from ?? $sellingApplication->duration_from ?? '');
            $sellingTo = old('selling_to', $data->selling_to ?? $sellingApplication->duration_to ?? '');
        @endphp

        <div class="border border-slate-200 rounded-xl p-4 space-y-5">

            <div class="grid grid-cols-1 gap-5">

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Name of Selling Activity
                    </label>

                    <input
                        type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter official selling activity name"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition"
                        @if($isReadOnly) disabled @endif>

                    @error('activity_name')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Selling From
                        </label>

                        <input
                            type="date"
                            name="selling_from"
                            value="{{ $sellingFrom }}"
                            class="w-full rounded-lg px-3 py-2 text-sm
                                {{ $errors->has('selling_from')
                                    ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                            @if($isReadOnly) disabled @endif>

                        @error('selling_from')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Selling To
                        </label>

                        <input
                            type="date"
                            name="selling_to"
                            value="{{ $sellingTo }}"
                            class="w-full rounded-lg px-3 py-2 text-sm
                                {{ $errors->has('selling_to')
                                    ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                            @if($isReadOnly) disabled @endif>

                        @error('selling_to')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Total Sales (₱)
                        </label>

                        <input
                            type="text"
                            id="totalSalesDisplay"
                            readonly
                            value="0.00"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-right">
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>