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

                <div class="text-[10px] text-blue-900 italic mb-1">
                    (List the specific objectives of the project.)
                </div>

                <div id="objectivesWrap" class="space-y-2">
                    <input type="text"
                           name="objectives[]"
                           value="{{ old('objectives.0') }}"
                           class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                           placeholder="Enter objective">
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

                <div class="text-[10px] text-blue-900 italic mb-1">
                    (State measurable indicators of success.)
                </div>

                <div id="indicatorsWrap" class="space-y-2">
                    <input type="text"
                           name="success_indicators[]"
                           value="{{ old('success_indicators.0') }}"
                           class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                           placeholder="Enter success indicator">
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

                <div class="text-[10px] text-blue-900 italic mb-1">
                    (Internal or external collaborators.)
                </div>

                <div id="partnersWrap" class="space-y-2">
                    <input type="text"
                           name="partners[]"
                           value="{{ old('partners.0') }}"
                           class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                           placeholder="Partner name">
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

                <div class="text-[10px] text-blue-900 italic mb-1">
                    (List roles involved in the project.)
                </div>

                <div id="rolesWrap" class="space-y-2">
                    <input type="text"
                           name="roles[]"
                           value="{{ old('roles.0') }}"
                           class="w-full border border-slate-300 bg-white px-3 py-1 text-[12px]"
                           placeholder="Role title">
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