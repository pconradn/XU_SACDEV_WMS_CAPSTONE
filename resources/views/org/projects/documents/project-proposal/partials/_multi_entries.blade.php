<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>

    <div class="p-4 space-y-5">

        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600">
                <i data-lucide="target" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Objectives & Responsibilities
                </h3>
                <p class="text-[11px] text-slate-500">
                    Define what the project aims to achieve and who is involved
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                            Objectives
                        </span>
                    </div>

                    <button type="button"
                        id="addObjectiveBtn"
                        class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $objectives = old('objectives');

                    if (is_null($objectives)) {
                        $objectives = $proposal?->objectives?->pluck('objective')->toArray();

                        if (empty($objectives)) {
                            $objectives = $project->sourceStrategicPlanProject?->objectives?->pluck('text')->toArray();
                        }
                    }

                    $objectives = array_values(array_filter($objectives ?? []));

                    if (empty($objectives)) $objectives = [''];
                @endphp

                <div id="objectivesWrap" class="space-y-2">
                    @foreach($objectives as $obj)
                        <div class="flex gap-2 items-center objective-row dynamic-row">

                            <input type="text"
                                name="objectives[]"
                                value="{{ $obj }}"
                                class="w-full rounded-lg border px-3 py-2 text-xs
                                {{ $errors->has('objectives') || $errors->has('objectives.*') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                                placeholder="Enter objective">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-rose-600 text-sm px-2">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="bar-chart-3" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                            Target Indicators
                        </span>
                    </div>

                    <button type="button"
                        id="addIndicatorBtn"
                        class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $indicators = old('success_indicators');

                    if (is_null($indicators)) {
                        $indicators = $proposal?->indicators?->pluck('indicator')->toArray();

                        if (empty($indicators)) {
                            $indicators = $project->sourceStrategicPlanProject?->deliverables?->pluck('text')->toArray();
                        }
                    }

                    $indicators = array_values(array_filter($indicators ?? []));

                    if (empty($indicators)) $indicators = [''];
                @endphp

                <div id="indicatorsWrap" class="space-y-2">
                    @foreach($indicators as $ind)
                        <div class="flex gap-2 items-center indicator-row dynamic-row">

                            <input type="text"
                                name="success_indicators[]"
                                value="{{ $ind }}"
                                class="w-full rounded-lg border px-3 py-2 text-xs
                                {{ $errors->has('success_indicators') || $errors->has('success_indicators.*') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                                placeholder="Enter success indicator">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-rose-600 text-sm px-2">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-slate-200">

            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="handshake" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                            Partners
                        </span>
                    </div>

                    <button type="button"
                        id="addPartnerBtn"
                        class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $partners = old('partners');

                    if (is_null($partners)) {
                        $partners = $proposal?->partners?->pluck('name')->toArray();

                        if (empty($partners)) {
                            $partners = $project->sourceStrategicPlanProject?->partners?->pluck('text')->toArray();
                        }
                    }

                    $partners = array_values(array_filter($partners ?? []));

                    if (empty($partners)) $partners = [''];
                @endphp

                <div id="partnersWrap" class="space-y-2">
                    @foreach($partners as $partner)
                        <div class="flex gap-2 items-center partner-row dynamic-row">

                            <input type="text"
                                name="partners[]"
                                value="{{ $partner }}"
                                class="w-full rounded-lg border px-3 py-2 text-xs
                                {{ $errors->has('partners') || $errors->has('partners.*') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                                placeholder="Partner name">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-rose-600 text-sm px-2">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i data-lucide="user-cog" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                            Specific Roles
                        </span>
                    </div>

                    <button type="button"
                        id="addRoleBtn"
                        class="text-[11px] font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $roles = old('roles');

                    if (is_null($roles)) {
                        $roles = $proposal?->roles?->pluck('role_name')->toArray();

                        if (empty($roles)) {
                            $roles = $project->sourceStrategicPlanProject?->roles?->pluck('role_name')->toArray();
                        }
                    }

                    $roles = array_values(array_filter($roles ?? []));

                    if (empty($roles)) $roles = [''];
                @endphp

                <div id="rolesWrap" class="space-y-2">
                    @foreach($roles as $role)
                        <div class="flex gap-2 items-center role-row dynamic-row">

                            <input type="text"
                                name="roles[]"
                                value="{{ $role }}"
                                class="w-full rounded-lg border px-3 py-2 text-xs
                                {{ $errors->has('roles') || $errors->has('roles.*') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                                placeholder="Role title">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-rose-600 text-sm px-2">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>

    </div>

</div>