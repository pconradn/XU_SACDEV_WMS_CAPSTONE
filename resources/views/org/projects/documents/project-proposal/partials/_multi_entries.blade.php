<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-6">

    {{-- ================= SECTION HEADER ================= --}}
    <div>
        <h3 class="text-sm font-semibold text-slate-900">
            Objectives & Responsibilities
        </h3>
        <p class="text-xs text-slate-500">
            Define what the project aims to achieve and who is involved
        </p>
    </div>

    {{-- ================= ROW 1 ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- OBJECTIVES --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-semibold text-slate-700">
                    Objectives
                </label>
                <button type="button"
                        id="addObjectiveBtn"
                        class="text-xs text-slate-600 hover:text-slate-900">
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
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none"
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
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-semibold text-slate-700">
                    Target Indicators
                </label>
                <button type="button"
                        id="addIndicatorBtn"
                        class="text-xs text-slate-600 hover:text-slate-900">
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
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none"
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
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-semibold text-slate-700">
                    Partners
                </label>
                <button type="button"
                        id="addPartnerBtn"
                        class="text-xs text-slate-600 hover:text-slate-900">
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
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none"
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
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-semibold text-slate-700">
                    Specific Roles
                </label>
                <button type="button"
                        id="addRoleBtn"
                        class="text-xs text-slate-600 hover:text-slate-900">
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
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none"
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