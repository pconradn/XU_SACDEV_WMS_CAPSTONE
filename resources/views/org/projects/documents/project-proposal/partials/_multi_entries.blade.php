<div class="border border-slate-300">

    {{-- Top Label --}}
    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Project Objectives and Responsibilities
        </div>
    </div>

    <div class="px-4 pb-3 pt-2 space-y-6">

        {{-- ROW 1 --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Objectives --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Objectives:
                </label>

                @php
                    $objectives = old('objectives')
                        ?? ($proposal?->objectives?->pluck('objective')->toArray() ?? []);
                    if (empty($objectives)) $objectives = [''];
                @endphp

                <div id="objectivesWrap" class="space-y-2">
                    @foreach($objectives as $obj)
                        <div class="flex gap-2 objective-row dynamic-row">
                            <input type="text"
                                   name="objectives[]"
                                   value="{{ $obj }}"
                                   class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                                   placeholder="Enter objective">
                            <button type="button"
                                    class="remove-btn text-red-600 text-[12px] px-2">
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button"
                        id="addObjectiveBtn"
                        class="mt-2 text-[10px] text-blue-700 underline">
                    + Add Objective
                </button>
            </div>

            {{-- Target Indicators --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Target Indicators:
                </label>

                @php
                    $indicators = old('success_indicators')
                        ?? ($proposal?->indicators?->pluck('indicator')->toArray() ?? []);
                    if (empty($indicators)) $indicators = [''];
                @endphp

                <div id="indicatorsWrap" class="space-y-2">
                    @foreach($indicators as $ind)
                        <div class="flex gap-2 indicator-row dynamic-row">
                            <input type="text"
                                   name="success_indicators[]"
                                   value="{{ $ind }}"
                                   class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                                   placeholder="Enter success indicator">
                            <button type="button"
                                    class="remove-btn text-red-600 text-[12px] px-2">
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button"
                        id="addIndicatorBtn"
                        class="mt-2 text-[10px] text-blue-700 underline">
                    + Add Indicator
                </button>
            </div>

        </div>

        <div class="border-t border-slate-300"></div>

        {{-- ROW 2 --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 pt-4">

            {{-- Partners --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Partners:
                </label>

                @php
                    $partners = old('partners')
                        ?? ($proposal?->partners?->pluck('name')->toArray() ?? []);
                    if (empty($partners)) $partners = [''];
                @endphp

                <div id="partnersWrap" class="space-y-2">
                    @foreach($partners as $partner)
                        <div class="flex gap-2 partner-row dynamic-row">
                            <input type="text"
                                   name="partners[]"
                                   value="{{ $partner }}"
                                   class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                                   placeholder="Partner name">
                            <button type="button"
                                    class="remove-btn text-red-600 text-[12px] px-2">
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button"
                        id="addPartnerBtn"
                        class="mt-2 text-[10px] text-blue-700 underline">
                    + Add Partner
                </button>
            </div>

            {{-- Specific Roles --}}
            <div>
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    Specific Roles:
                </label>

                @php
                    $roles = old('roles')
                        ?? ($proposal?->roles?->pluck('role_name')->toArray() ?? []);
                    if (empty($roles)) $roles = [''];
                @endphp

                <div id="rolesWrap" class="space-y-2">
                    @foreach($roles as $role)
                        <div class="flex gap-2 role-row dynamic-row">
                            <input type="text"
                                   name="roles[]"
                                   value="{{ $role }}"
                                   class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                                   placeholder="Role title">
                            <button type="button"
                                    class="remove-btn text-red-600 text-[12px] px-2">
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button"
                        id="addRoleBtn"
                        class="mt-2 text-[10px] text-blue-700 underline">
                    + Add Role
                </button>
            </div>

        </div>

    </div>

</div>