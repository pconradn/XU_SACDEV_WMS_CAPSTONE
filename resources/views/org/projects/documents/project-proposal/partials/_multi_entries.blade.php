<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="text-sm font-semibold text-slate-900">Details (Multiple Entries)</div>
    <div class="mt-1 text-xs text-slate-500">
        Use “Add” to insert more rows.
    </div>

    {{-- Objectives --}}
    <div class="mt-5">
        <div class="flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-slate-800">Objectives</div>
            <button type="button"
                    class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                    onclick="addRow('objectives')">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="objectives">
            @php $oldObjectives = old('objectives', ['']); @endphp
            @foreach($oldObjectives as $i => $val)
                <div class="flex gap-2">
                    <input type="text"
                           name="objectives[]"
                           value="{{ $val }}"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                           placeholder="Objective {{ $i + 1 }}">
                    <button type="button"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                            onclick="removeRow(this)">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Success Indicators --}}
    <div class="mt-6">
        <div class="flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-slate-800">Target / Success Indicators</div>
            <button type="button"
                    class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                    onclick="addRow('indicators')">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="indicators">
            @php $oldIndicators = old('indicators', ['']); @endphp
            @foreach($oldIndicators as $i => $val)
                <div class="flex gap-2">
                    <input type="text"
                           name="indicators[]"
                           value="{{ $val }}"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                           placeholder="Indicator {{ $i + 1 }}">
                    <button type="button"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                            onclick="removeRow(this)">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Partners / Sponsors --}}
    <div class="mt-6">
        <div class="flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-slate-800">Target Partners / Sponsors</div>
            <button type="button"
                    class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                    onclick="addPartnerRow()">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="partners">
            @php $oldPartners = old('partners', [['name' => '', 'type' => '']]); @endphp
            @foreach($oldPartners as $i => $p)
                <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
                    <input type="text"
                           name="partners[{{ $i }}][name]"
                           value="{{ $p['name'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                           placeholder="Name">
                    <input type="text"
                           name="partners[{{ $i }}][type]"
                           value="{{ $p['type'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                           placeholder="Type (optional)">
                    <button type="button"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                            onclick="removeRow(this)">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Roles specific to project --}}
    <div class="mt-6">
        <div class="flex items-center justify-between gap-3">
            <div class="text-sm font-semibold text-slate-800">Roles Specific to the Project</div>
            <button type="button"
                    class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                    onclick="addRoleRow()">
                + Add
            </button>
        </div>

        <div class="mt-2 space-y-2" id="roles">
            @php $oldRoles = old('roles', [['role_name' => '', 'description' => '']]); @endphp
            @foreach($oldRoles as $i => $r)
                <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
                    <input type="text"
                           name="roles[{{ $i }}][role_name]"
                           value="{{ $r['role_name'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                           placeholder="Role name">
                    <input type="text"
                           name="roles[{{ $i }}][description]"
                           value="{{ $r['description'] ?? '' }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                           placeholder="Description (optional)">
                    <button type="button"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                            onclick="removeRow(this)">
                        Remove
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div> 