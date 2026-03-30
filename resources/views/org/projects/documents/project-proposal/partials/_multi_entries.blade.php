<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= SECTION HEADER ================= --}}
        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Objectives & Responsibilities
            </h3>
            <p class="text-xs text-blue-700">
                Define what the project aims to achieve and who is involved
            </p>
        </div>

        {{-- ================= ROW 1 ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- OBJECTIVES --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs font-semibold text-slate-900 uppercase tracking-wide">
                        Objectives
                    </label>

                    <button type="button"
                        id="addObjectiveBtn"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $objectives = old('objectives')
                        ?? ($proposal?->objectives?->pluck('objective')->toArray() ?? []);
                    if (empty($objectives)) $objectives = [''];
                @endphp

                <div id="objectivesWrap" class="space-y-2">
                    @foreach($objectives as $obj)
                        <div class="flex gap-2 items-center objective-row dynamic-row">

                            <input type="text"
                                name="objectives[]"
                                value="{{ $obj }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                placeholder="Enter objective">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">
                                ✕
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

            {{-- TARGET INDICATORS --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs font-semibold text-slate-900 uppercase tracking-wide">
                        Target Indicators
                    </label>

                    <button type="button"
                        id="addIndicatorBtn"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $indicators = old('success_indicators')
                        ?? ($proposal?->indicators?->pluck('indicator')->toArray() ?? []);
                    if (empty($indicators)) $indicators = [''];
                @endphp

                <div id="indicatorsWrap" class="space-y-2">
                    @foreach($indicators as $ind)
                        <div class="flex gap-2 items-center indicator-row dynamic-row">

                            <input type="text"
                                name="success_indicators[]"
                                value="{{ $ind }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                placeholder="Enter success indicator">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">
                                ✕
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        {{-- ================= ROW 2 ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-slate-200">

            {{-- PARTNERS --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs font-semibold text-slate-900 uppercase tracking-wide">
                        Partners
                    </label>

                    <button type="button"
                        id="addPartnerBtn"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $partners = old('partners')
                        ?? ($proposal?->partners?->pluck('name')->toArray() ?? []);
                    if (empty($partners)) $partners = [''];
                @endphp

                <div id="partnersWrap" class="space-y-2">
                    @foreach($partners as $partner)
                        <div class="flex gap-2 items-center partner-row dynamic-row">

                            <input type="text"
                                name="partners[]"
                                value="{{ $partner }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                placeholder="Partner name">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">
                                ✕
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

            {{-- ROLES --}}
            <div class="border border-slate-200 rounded-xl p-4">

                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs font-semibold text-slate-900 uppercase tracking-wide">
                        Specific Roles
                    </label>

                    <button type="button"
                        id="addRoleBtn"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                        + Add
                    </button>
                </div>

                @php
                    $roles = old('roles')
                        ?? ($proposal?->roles?->pluck('role_name')->toArray() ?? []);
                    if (empty($roles)) $roles = [''];
                @endphp

                <div id="rolesWrap" class="space-y-2">
                    @foreach($roles as $role)
                        <div class="flex gap-2 items-center role-row dynamic-row">

                            <input type="text"
                                name="roles[]"
                                value="{{ $role }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm 
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                placeholder="Role title">

                            <button type="button"
                                class="remove-btn text-slate-400 hover:text-red-600 text-sm px-2">
                                ✕
                            </button>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>

    </div>

</div>