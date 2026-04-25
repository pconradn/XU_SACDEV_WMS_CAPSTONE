<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

    @php
        $idx = 'president';

        $presidentMembership = \App\Models\OrgMembership::where('organization_id', $registration->organization_id)
            ->where('school_year_id', $registration->target_school_year_id)
            ->where('role', 'president')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        $presidentUser = $presidentMembership?->user;

        $presidentStudentId = $presidentUser?->email
            ? explode('@', $presidentUser->email)[0]
            : null;

        $presidentItem = collect($registration->items ?? [])
            ->first(fn ($item) => (string) $item->student_id_number === (string) $presidentStudentId);

        $prefix = old("items.$idx.prefix", $presidentItem->prefix ?? $presidentUser->prefix ?? '');
        $first  = old("items.$idx.first_name", $presidentItem->first_name ?? $presidentUser->first_name ?? '');
        $mi     = old("items.$idx.middle_initial", $presidentItem->middle_initial ?? $presidentUser->middle_initial ?? '');
        $last   = old("items.$idx.last_name", $presidentItem->last_name ?? $presidentUser->last_name ?? '');

        $studentId = old("items.$idx.student_id_number", $presidentItem->student_id_number ?? $presidentStudentId ?? '');

        $position = old("items.$idx.position", $presidentItem->position ?? 'President');
        $course   = old("items.$idx.course_and_year", $presidentItem->course_and_year ?? '');
        $mobile   = old("items.$idx.mobile_number", $presidentItem->mobile_number ?? '');

        $qpi1 = old("items.$idx.first_sem_qpi", $presidentItem->first_sem_qpi ?? '');
        $qpi2 = old("items.$idx.second_sem_qpi", $presidentItem->second_sem_qpi ?? '');
        $qpi3 = old("items.$idx.intersession_qpi", $presidentItem->intersession_qpi ?? '');
    @endphp

    <div class="mb-4 flex items-start justify-between gap-4">
        <div>
            <div class="text-xs font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                President Information
            </div>

            <div class="text-[11px] text-slate-500 mt-1">
                System-assigned role. Display position and additional details may be edited.
            </div>

            <div class="text-[11px] text-slate-400 mt-2">
                Name and Student ID are fixed and cannot be changed.
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">

        <div class="flex items-center justify-between mb-3">
            <div class="text-xs font-semibold text-slate-800">
                President
            </div>

            <span class="text-[10px] px-2 py-0.5 rounded-md bg-blue-50 text-blue-700">
                System Role: President Approver
            </span>
        </div>

        <input type="hidden" name="items[{{ $idx }}][major_officer_role]" value="president">

        <div class="mb-3">
            <label class="block text-[10px] text-slate-500 mb-1">Display Position</label>
            <input type="text"
                name="items[{{ $idx }}][position]"
                value="{{ $position }}"
                class="w-full rounded-xl border px-3 py-2 text-xs border-slate-200"
                {{ !$canEdit ? 'readonly' : '' }}>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="text-[10px] text-slate-500">Prefix</label>
                <input type="text"
                    name="items[{{ $idx }}][prefix]"
                    value="{{ $prefix }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200 bg-slate-50 text-slate-500"
                    readonly>
            </div>

            <div>
                <label class="text-[10px] text-slate-500">First Name</label>
                <input type="text"
                    name="items[{{ $idx }}][first_name]"
                    value="{{ $first }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200 bg-slate-50 text-slate-500"
                    readonly>
            </div>

            <div>
                <label class="text-[10px] text-slate-500">M.I.</label>
                <input type="text"
                    name="items[{{ $idx }}][middle_initial]"
                    value="{{ $mi }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200 bg-slate-50 text-slate-500"
                    readonly>
            </div>

            <div>
                <label class="text-[10px] text-slate-500">Last Name</label>
                <input type="text"
                    name="items[{{ $idx }}][last_name]"
                    value="{{ $last }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200 bg-slate-50 text-slate-500"
                    readonly>
            </div>
        </div>

        <div class="mt-3">
            <label class="text-[10px] text-slate-500">Student ID</label>
            <input type="text"
                name="items[{{ $idx }}][student_id_number]"
                value="{{ $studentId }}"
                class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200 bg-slate-50 text-slate-500"
                readonly>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-3">
            <div>
                <label class="text-[10px] text-slate-500">Course & Year</label>
                <input type="text"
                    name="items[{{ $idx }}][course_and_year]"
                    value="{{ $course }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                    {{ !$canEdit ? 'readonly' : '' }}>
            </div>

            <div>
                <label class="text-[10px] text-slate-500">Mobile</label>
                <input type="text"
                    name="items[{{ $idx }}][mobile_number]"
                    value="{{ $mobile }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                    {{ !$canEdit ? 'readonly' : '' }}>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 mt-3">
            <div>
                <label class="text-[10px] text-slate-500">Prev 1st Sem QPI</label>
                <input type="number" step="0.01" min="0" max="4"
                    name="items[{{ $idx }}][first_sem_qpi]"
                    value="{{ $qpi1 }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                    {{ !$canEdit ? 'readonly' : '' }}>
            </div>

            <div>
                <label class="text-[10px] text-slate-500">Prev 2nd Sem QPI</label>
                <input type="number" step="0.01" min="0" max="4"
                    name="items[{{ $idx }}][second_sem_qpi]"
                    value="{{ $qpi2 }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                    {{ !$canEdit ? 'readonly' : '' }}>
            </div>

            <div>
                <label class="text-[10px] text-slate-500">Prev Inter QPI</label>
                <input type="number" step="0.01" min="0" max="4"
                    name="items[{{ $idx }}][intersession_qpi]"
                    value="{{ $qpi3 }}"
                    class="w-full rounded-xl border px-2 py-1.5 text-xs border-slate-200"
                    {{ !$canEdit ? 'readonly' : '' }}>
            </div>
        </div>

    </div>
</div>