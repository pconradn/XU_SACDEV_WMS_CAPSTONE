<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Collection Activity Information
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                Provide details about the collection activity, including its purpose and duration.
            </p>
        </div>

        @php
            $activityName = old('activity_name', $data->activity_name ?? $project->title);
            $purpose = old('purpose', $data->purpose ?? '');
            $collectionFrom = old('collection_from', $data->collection_from ?? '');
            $collectionTo = old('collection_to', $data->collection_to ?? '');
        @endphp

        <div class="border border-slate-200 rounded-xl p-4 space-y-5">

            <div class="grid grid-cols-1 gap-5">

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Name of Activity
                    </label>

                    <input
                        type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter collection activity name"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition"
                        @if($isReadOnly) disabled @endif>

                    <p class="text-[11px] text-slate-400 mt-1">
                        This will be used as the official name of the collection activity.
                    </p>

                    @error('activity_name')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Purpose of Collection
                    </label>

                    <textarea
                        name="purpose"
                        rows="3"
                        placeholder="Describe the purpose of the collection"
                        class="w-full rounded-lg px-3 py-2 text-sm
                            {{ $errors->has('purpose')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition"
                        @if($isReadOnly) disabled @endif>{{ $purpose }}</textarea>

                    <p class="text-[11px] text-slate-400 mt-1">
                        Explain why the collection was conducted (e.g., fundraising, contributions, participation fees).
                    </p>

                    @error('purpose')
                        <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Collection From
                        </label>

                        <input
                            type="date"
                            name="collection_from"
                            value="{{ $collectionFrom }}"
                            class="w-full rounded-lg px-3 py-2 text-sm
                                {{ $errors->has('collection_from')
                                    ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                            @if($isReadOnly) disabled @endif>

                        <p class="text-[11px] text-slate-400 mt-1">
                            Start date when collections began.
                        </p>

                        @error('collection_from')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Collection To
                        </label>

                        <input
                            type="date"
                            name="collection_to"
                            value="{{ $collectionTo }}"
                            class="w-full rounded-lg px-3 py-2 text-sm
                                {{ $errors->has('collection_to')
                                    ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                            @if($isReadOnly) disabled @endif>

                        <p class="text-[11px] text-slate-400 mt-1">
                            End date of the collection period.
                        </p>

                        @error('collection_to')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>