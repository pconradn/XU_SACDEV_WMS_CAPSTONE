<x-app-layout>
    <div x-data="presidentAssignmentsPage()" class="space-y-6">

        {{-- HEADER --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                        President Assignments
                    </h1>
                    <p class="mt-1 text-sm text-slate-500 max-w-2xl">
                        Assign organization presidents by school year. Select a school year first, then manage which
                        student is assigned as president for each organization.
                    </p>
                </div>

            </div>
        </div>




        {{-- SCHOOL YEAR CONTEXT --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200">
                <h2 class="text-sm font-semibold text-slate-900">
                    School Year Context
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Choose the school year you want to manage. All president assignments on this page will use that selected context.
                </p>
            </div>

            <div class="px-6 py-5">
                <form method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                    <div class="w-full lg:max-w-sm">
                        <label for="school_year_id" class="block text-xs font-medium uppercase tracking-wide text-slate-500">
                            Target School Year
                        </label>

                        <select
                            id="school_year_id"
                            name="school_year_id"
                            class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                            required
                        >
                            <option value="">Select a school year</option>
                            @foreach($schoolYears as $sy)
                                <option value="{{ $sy->id }}" @selected($selectedSyId == $sy->id)>
                                    {{ $sy->name }}{{ $sy->is_active ? ' — Active' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
                        >
                            Load Organizations
                        </button>
                    </div>

                </form>
            </div>

            @if($selectedSyId > 0)
                @php
                    $selectedSchoolYear = $schoolYears->firstWhere('id', $selectedSyId);
                @endphp

                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                                Current Context
                            </div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                {{ $selectedSchoolYear?->name ?? 'Selected School Year' }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if(optional($selectedSchoolYear)->is_active)
                                <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                    Active School Year
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    Archived / Inactive Context
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            {{-- ================= CONTROL BAR ================= --}}
            @if($selectedSyId > 0)

                @php
                    $total = $organizations->count();
                    $assignedCount = $assignedMap->count();
                    $progressPercent = $total > 0 ? round(($assignedCount / $total) * 100) : 0;
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-6 py-5 border-b border-slate-200 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        {{-- PROGRESS --}}
                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500 font-medium">
                                Progress
                            </div>

                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                {{ $assignedCount }} / {{ $total }} organizations assigned
                            </div>
                        </div>

                        {{-- SEARCH --}}
                        <div class="w-full lg:w-80">
                            <div class="flex items-center gap-3 rounded-xl border border-slate-300 px-3 py-2">

                                {{-- ICON --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35m1.85-5.65a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>
                                </svg>

                                {{-- INPUT --}}
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Search organizations..."
                                    class="w-full text-sm border-0 focus:ring-0 placeholder:text-slate-400"
                                >

                                {{-- CLEAR --}}
                                <button
                                    x-show="search.length > 0"
                                    @click="search = ''"
                                    class="text-slate-400 hover:text-slate-600"
                                >
                                    ✕
                                </button>

                            </div>
                        </div>

                    </div>


                    {{-- PROGRESS BAR --}}
                    <div class="px-6 pt-4">
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div
                                class="h-2 rounded-full {{ $progressPercent == 100 ? 'bg-emerald-600' : 'bg-blue-600' }} transition-all"
                                style="width: {{ $progressPercent }}%">
                            </div>
                        </div>

                        <div class="mt-1 text-[11px] text-slate-500">
                            {{ $progressPercent }}% complete
                        </div>
                    </div>


                    {{-- FILTERS --}}
                    <div class="px-6 py-4 flex flex-wrap items-center gap-2">

                        <button
                            @click="filter = 'all'"
                            :class="filter === 'all'
                                ? 'bg-slate-900 text-white'
                                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                            class="text-xs px-3 py-1.5 rounded-full font-medium transition"
                        >
                            All
                        </button>

                        <button
                            @click="filter = 'assigned'"
                            :class="filter === 'assigned'
                                ? 'bg-emerald-600 text-white'
                                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                            class="text-xs px-3 py-1.5 rounded-full font-medium transition"
                        >
                            Assigned
                        </button>

                        <button
                            @click="filter = 'unassigned'"
                            :class="filter === 'unassigned'
                                ? 'bg-amber-500 text-white'
                                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                            class="text-xs px-3 py-1.5 rounded-full font-medium transition"
                        >
                            Not Assigned
                        </button>

                    </div>

                </div>

            @endif

            {{-- ================= ORGANIZATION GRID ================= --}}
            @if($selectedSyId > 0)

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                    @forelse($organizations as $org)
                        
                        @php
                            $assigned = $assignedMap->get($org->id);
                            $searchText = strtolower($org->name . ' ' . ($org->acronym ?? ''));
                        @endphp


                        <div
                            x-show="match('{{ $searchText }}') && filterMatch({{ $assigned ? 'true' : 'false' }})"
                            class="rounded-2xl border 
                                {{ !$assigned ? 'border-amber-200 bg-amber-50/40' : 'border-slate-200 bg-white' }}
                                p-5 shadow-sm hover:shadow-md hover:-translate-y-[1px] transition"
                        >

                            {{-- TOP --}}
                            <div class="flex items-center justify-between gap-3">

                                {{-- LEFT: LOGO + NAME --}}
                                <div class="flex items-center gap-3 min-w-0">

                                    {{-- LOGO --}}
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden flex items-center justify-center shrink-0">

                                        @if($org->logo_path)
                                            <img src="{{ asset('storage/' . $org->logo_path) }}"
                                                class="w-full h-full object-cover">

                                        @else
                                            {{-- fallback icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 text-slate-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7h18M3 12h18M3 17h18"/>
                                            </svg>
                                        @endif

                                    </div>

                                    {{-- NAME --}}
                                    <div class="min-w-0">
                                        <div class="text-base font-semibold text-slate-900 truncate">
                                            {{ $org->name }}
                                        </div>

                                        <div class="text-xs text-slate-500">
                                            {{ $org->acronym ?? '—' }}
                                        </div>
                                    </div>

                                </div>

                                {{-- STATUS BADGE --}}
                                <div class="shrink-0">
                                    @if($assigned)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full border bg-emerald-100 text-emerald-700 border-emerald-200">
                                            Assigned
                                        </span>
                                    @else
                                        <span class="text-[10px] px-2 py-0.5 rounded-full border bg-amber-100 text-amber-700 border-amber-200">
                                            Missing
                                        </span>
                                    @endif
                                </div>

                            </div>


                            {{-- PRESIDENT --}}
                            <div class="mt-5 space-y-1">

                                <div class="text-[11px] uppercase tracking-wide text-slate-400">
                                    President
                                </div>

                                @if($assigned)
                                    <div class="mt-1 text-sm font-semibold text-slate-900">
                                        {{ $assigned->officerEntry->full_name ?? '—' }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        {{ $assigned->officerEntry->student_id_number ?? '' }}
                                    </div>
                                @else
                                    <div class="mt-1 text-sm text-slate-400 italic">
                                        <span class="text-amber-600 font-medium">
                                            No president assigned
                                        </span>
                                    </div>
                                @endif

                            </div>


                            {{-- ACTION --}}
                            <div class="mt-5 flex justify-end">

                                <button
                                    class="openAssignModal text-xs px-3 py-1.5 rounded-lg 
                                        bg-slate-900 text-white hover:bg-slate-800 font-semibold"
                                    data-org-id="{{ $org->id }}"
                                    data-org-name="{{ $org->name }}"
                                    data-sy-id="{{ $selectedSyId }}"
                                    data-sy-name="{{ $selectedSchoolYear?->name }}"
                                    data-has-president="{{ $assigned ? '1' : '0' }}"
                                    data-current-name="{{ $assigned?->officerEntry?->full_name ?? '' }}"
                                    data-current-id="{{ $assigned?->officerEntry?->student_id_number ?? '' }}"
                                >
                                    {{ $assigned ? 'Replace' : 'Assign' }}
                                </button>

                            </div>

                        </div>

                    @empty

                        <div class="col-span-full text-center text-sm text-slate-500 py-10">
                            No organizations found for this school year.
                        </div>

                    @endforelse

                </div>

            @endif

            
        </div>


        {{-- EMPTY STATE BEFORE SELECTION --}}
        @if($selectedSyId <= 0)
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white border border-slate-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                    </svg>
                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-900">
                    Select a school year to begin
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    President assignments are managed per school year. Once you select a school year,
                    the organizations under that context will appear here for assignment.
                </p>
            </div>
        @endif

    </div>



{{-- ================= ADVANCED ASSIGN MODAL ================= --}}
<div id="assignModal" class="fixed inset-0 hidden items-center justify-center bg-black/40 z-50 p-4">

    <div class="w-full max-w-xl rounded-2xl bg-white shadow-xl overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b">

            <div class="flex items-start justify-between">
                <div>
                    <h2 id="modalTitle" class="text-base font-semibold text-slate-900">
                        Assign President
                    </h2>

                    <p id="modalOrgName" class="text-sm text-slate-500 mt-1"></p>

                    <p id="modalSyName" class="text-xs text-slate-400 mt-1"></p>
                </div>

                <button id="closeAssignModal"
                    class="text-slate-400 hover:text-slate-600 text-sm">
                    ✕
                </button>
            </div>

        </div>


        {{-- CURRENT PRESIDENT (NEW) --}}
        <div id="currentPresidentBox" class="px-6 py-4 bg-slate-50 border-b hidden">

            <div class="text-[11px] uppercase tracking-wide text-slate-400">
                Current President
            </div>

            <div id="currentPresidentName" class="mt-1 text-sm font-semibold text-slate-900"></div>
            <div id="currentPresidentId" class="text-xs text-slate-500"></div>

        </div>


        {{-- WARNING --}}
        <div id="replaceWarning" class="px-6 py-3 bg-amber-50 border-b border-amber-200 hidden">
            <p class="text-xs text-amber-800">
                You are about to replace the current president. This action should only be done if the previous assignment is incorrect or outdated.
            </p>
        </div>


        {{-- FORM --}}
        <form method="POST" action="{{ route('admin.president_assignments.assign') }}" class="px-6 py-5 space-y-4">
            <input type="hidden" name="force_replace" id="modalForceReplace" value="0">
            
            @csrf

            <input type="hidden" name="organization_id" id="modalOrgId">
            <input type="hidden" name="school_year_id" id="modalSyId">

            {{-- NAME --}}
            <div class="grid grid-cols-2 gap-3">

                <div>
                    <label class="text-xs text-slate-600">First Name</label>
                    <input name="first_name" id="modalFirstName"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm"
                        required pattern="[A-Za-z\s\]+">
                </div>

                <div>
                    <label class="text-xs text-slate-600">Last Name</label>
                    <input name="last_name" id="modalLastName"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm"
                        required pattern="[A-Za-z\s\-]+">
                </div>

                <div>
                    <label class="text-xs text-slate-600">Middle Initial</label>
                    <input name="middle_initial" id="modalMiddleInitial"
                        maxlength="1"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm"
                        pattern="[A-Za-z]">
                </div>

                <div>
                    <label class="text-xs text-slate-600">Prefix</label>
                    <input name="prefix" id="modalPrefix"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm"
                        placeholder="Mr. / Ms."
                        pattern="[A-Za-z\.]+">
                </div>

            </div>

            {{-- STUDENT ID --}}
            <div>
                <label class="block text-xs font-medium text-slate-600">
                    Student ID Number
                </label>

                <input
                    type="text"
                    name="student_id_number"
                    id="modalStudentId"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm"
                    placeholder="2018xxxxxxx"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    required
                >



                <div class="mt-2 text-xs text-slate-500">
                    Email preview:
                    <span id="modalEmailPreview" class="font-semibold text-blue-600">
                        studentID@my.xu.edu.ph
                    </span>
                </div>

                <div id="studentIdWarning"
                    class="mt-2 hidden rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                </div>
            </div>


            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 pt-3">

                <button type="button"
                    class="px-4 py-2 text-xs rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50"
                    onclick="closeAssignModal()">
                    Cancel
                </button>

                <button
                    id="submitBtn"
                    class="px-4 py-2 text-xs rounded-xl bg-slate-900 text-white font-semibold hover:bg-slate-800">
                    Assign
                </button>

            </div>

        </form>

    </div>
</div>


    <script>
    function presidentAssignmentsPage() {
        return {

            // SEARCH
            search: '',

            // FILTER
            filter: 'all', // all | assigned | unassigned

            // MATCH FUNCTION (used by cards later)
            match(text) {
                return text.includes(this.search.toLowerCase());
            },

            filterMatch(isAssigned) {
                if (this.filter === 'assigned') return isAssigned;
                if (this.filter === 'unassigned') return !isAssigned;
                return true;
            }
        }
    }
    </script>
    <script>
    const modal = document.getElementById('assignModal');

    function closeAssignModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.openAssignModal').forEach(btn => {
        btn.addEventListener('click', () => {

            // BASIC
            document.getElementById('modalOrgName').textContent = btn.dataset.orgName;
            document.getElementById('modalSyName').textContent = btn.dataset.syName;

            document.getElementById('modalOrgId').value = btn.dataset.orgId;
            document.getElementById('modalSyId').value = btn.dataset.syId;

            // RESET
            document.getElementById('modalFirstName').value = '';
            document.getElementById('modalLastName').value = '';
            document.getElementById('modalMiddleInitial').value = '';
            document.getElementById('modalPrefix').value = '';
            document.getElementById('modalStudentId').value = '';
            document.getElementById('modalEmailPreview').textContent = 'studentID@my.xu.edu.ph';

            // CURRENT PRESIDENT LOGIC
            const hasPresident = btn.dataset.hasPresident === '1';
            const currentName = btn.dataset.currentName || '';
            const currentId = btn.dataset.currentId || '';

            const box = document.getElementById('currentPresidentBox');
            const warning = document.getElementById('replaceWarning');
            const title = document.getElementById('modalTitle');
            const forceReplaceInput = document.getElementById('modalForceReplace');
            const submitBtn = document.getElementById('submitBtn');

            if (hasPresident) {
                title.textContent = 'Replace President';
                submitBtn.textContent = 'Replace';
                forceReplaceInput.value = '1';

                box.classList.remove('hidden');
                warning.classList.remove('hidden');

                document.getElementById('currentPresidentName').textContent = currentName;
                document.getElementById('currentPresidentId').textContent = currentId;
            } else {
                title.textContent = 'Assign President';
                submitBtn.textContent = 'Assign';
                forceReplaceInput.value = '0';

                box.classList.add('hidden');
                warning.classList.add('hidden');

                document.getElementById('currentPresidentName').textContent = '';
                document.getElementById('currentPresidentId').textContent = '';
            }


            console.log('force_replace:', document.getElementById('modalForceReplace').value);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    // CLOSE
    document.getElementById('closeAssignModal').addEventListener('click', closeAssignModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeAssignModal();
    });

    // EMAIL PREVIEW
    const studentIdInput = document.getElementById('modalStudentId');
    const warningBox = document.getElementById('studentIdWarning');

    let checkTimeout = null;

    studentIdInput.addEventListener('input', (e) => {

        const id = e.target.value.trim();

        document.getElementById('modalEmailPreview').textContent =
            id ? `${id}@my.xu.edu.ph` : 'studentID@my.xu.edu.ph';

        warningBox.classList.add('hidden');
        warningBox.textContent = '';

        clearTimeout(checkTimeout);

        if (id.length !== 11) return;

        checkTimeout = setTimeout(() => {

            fetch(`/president-assignments/check-student-id?student_id_number=${id}`)
                .then(res => res.json())
                .then(data => {

                    if (!data.exists) return;

                    const fullName = data.full_name;

                    warningBox.textContent =
                        `Existing user found: "${fullName}". Fields will match this student.`;

                    warningBox.classList.remove('hidden');

                    // AUTO FILL
                    const parts = fullName.split(' ');

                    document.getElementById('modalFirstName').value = parts[0] || '';
                    document.getElementById('modalLastName').value = parts[parts.length - 1] || '';

                    if (parts.length > 2) {
                        document.getElementById('modalMiddleInitial').value =
                            parts[1].replace('.', '') || '';
                    }

                });

        }, 400);
    });
    </script>


    <script>
    document.querySelector('#assignModal form').addEventListener('submit', function(e) {

        const first = document.getElementById('modalFirstName').value.trim();
        const last = document.getElementById('modalLastName').value.trim();
        const mi = document.getElementById('modalMiddleInitial').value.trim();
        const prefix = document.getElementById('modalPrefix').value.trim();
        const id = document.getElementById('modalStudentId').value.trim();

        const nameRegex = /^[A-Za-z]+$/;
        const lastRegex = /^[A-Za-z\s\-]+$/;
        const prefixRegex = /^[A-Za-z\.]+$/;
        const idRegex = /^[0-9]{11}$/;


    });
    </script>



</x-app-layout>