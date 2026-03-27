<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="font-semibold text-lg text-slate-800">
                Major Officer Roles — {{ $organization->name }}
            </div>

            <a href="{{ route('admin.orgs_by_sy.show', $organization) }}"
               class="text-sm font-semibold text-blue-700 hover:underline">
                Back to Org
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4">

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!$canEdit)
            <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
                Major officers can only be edited for the <b>Active School Year</b>.
            </div>
        @endif

        @php
            $roles = [
                'president' => 'President',
                'vice_president' => 'Vice President',
                'treasurer' => 'Treasurer',
                'finance_officer' => 'finance_officer',
            ];

            $officerOptions = $officers;



    
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            @foreach($roles as $roleKey => $roleLabel)

                @php
                    $current = $currentByRole[$roleKey] ?? null;

                    // membership for current role holder
                    $currentMembership = null;
                    if ($current) {
                        $currentMembership = \App\Models\OrgMembership::query()
                            ->where('organization_id', $organization->id)
                            ->where('school_year_id', $syId)
                            ->where('role', $roleKey)
                            ->where('user_id', $current->user_id)
                            ->latest('id')
                            ->first();
                        
                        
                    }
                    //dd($current);
                    $isArchived = (bool) ($currentMembership && $currentMembership->archived_at);
                    $isProbation = (bool) ($current && $current->is_under_probation);
                    $isSuspendedish = $isArchived || $isProbation;

                    $badgeText = $current
                        ? ($isSuspendedish ? 'Suspended / Flagged' : 'Active')
                        : 'Unassigned';

                    $badgeClass = $current
                        ? ($isSuspendedish
                            ? 'bg-amber-50 text-amber-800 border-amber-200'
                            : 'bg-emerald-50 text-emerald-800 border-emerald-200')
                        : 'bg-slate-50 text-slate-700 border-slate-200';
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-sm text-slate-500">Major Officer</div>
                            <div class="text-lg font-semibold text-slate-900">{{ $roleLabel }}</div>
                        </div>

                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                            {{ $badgeText }}
                        </span>
                    </div>

                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs font-semibold text-slate-500 uppercase">Currently Assigned</div>

                        @if($current)
                            <div class="mt-2 text-sm text-slate-900 font-semibold">
                                {{ $current->full_name }}
                            </div>
                            <div class="mt-1 text-sm text-slate-600">
                                {{ $current->student_id_number }}
                                <span class="mx-2 text-slate-300">•</span>
                                {{ $current->position }}
                            </div>

                            @if($isProbation)
                                <div class="mt-2 text-xs text-amber-800">
                                    Under probation (QPI flag).
                                </div>
                            @endif

                            @if(!$isSuspendedish)
                                <div class="mt-3 text-xs text-slate-700">
                                    <b>Warning:</b> replacing an active major officer will remove their role privileges for this org & school year.
                                    Previously approved forms remain as-is.
                                </div>
                            @endif
                        @else
                            <div class="mt-2 text-sm text-slate-600">
                                None assigned yet.
                            </div>
                        @endif
                    </div>

                    <form method="POST"
                          action="{{ route('admin.orgs_by_sy.major_officers.update_role', ['organization' => $organization->id, 'role' => $roleKey]) }}"
                          class="mt-4 major-role-form"
                          data-role="{{ $roleLabel }}"
                          data-current-active="{{ $current && !$isSuspendedish ? '1' : '0' }}"
                    >
                        @csrf

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Assign / Replace
                        </label>

                        <select name="officer_entry_id"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm officer-select"
                                data-role-label="{{ $roleLabel }}"
                                {{ !$canEdit ? 'disabled' : '' }}
                                required
                        >
                            <option value="">-- select officer --</option>

                            @foreach($officerOptions as $o)

                                @php
                                    $failCount = collect([
                                        $o->prev_first_sem_qpi,
                                        $o->prev_second_sem_qpi,
                                        $o->prev_intersession_qpi
                                    ])
                                    ->filter()
                                    ->filter(fn($qpi) => $qpi < 2.0)
                                    ->count();

                                    $willBeProbation = $failCount >= 2;
                                @endphp

                                <option value="{{ $o->id }}"
                                    data-name="{{ $o->full_name }}"
                                    data-probation="{{ $willBeProbation ? '1' : '0' }}"
                                    @selected($current && $current->id == $o->id)
                                >
                                    {{ $o->full_name }}
                                    ({{ $o->student_id_number }})
                                    {{ $willBeProbation ? '⚠ PROBATION RISK' : '' }}
                                </option>

                            @endforeach

                        </select>

                        {{-- probation preview area --}}
                        <div class="mt-2 hidden probation-warning rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-900">
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-2">
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                                    {{ !$canEdit ? 'disabled' : '' }}
                            >
                                Save {{ $roleLabel }}
                            </button>
                        </div>
                    </form>
                </div>

            @endforeach
        </div>
    </div>

    <script>
        (function () {

            /*
            |--------------------------------------------------------------------------
            | Submit confirmation (existing logic)
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.major-role-form').forEach((form) => {

                form.addEventListener('submit', (e) => {

                    const role = form.dataset.role || 'this role';
                    const currentActive = form.dataset.currentActive === '1';

                    if (!currentActive) return;

                    const ok = confirm(
                        `Caution: You are replacing an ACTIVE ${role}.\n\n` +
                        `This will remove that user's privileges for this organization and school year.\n\n` +
                        `Continue?`
                    );

                    if (!ok) e.preventDefault();

                });

            });


            /*
            |--------------------------------------------------------------------------
            | Probation preview logic
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.officer-select').forEach(select => {

                const warningBox =
                    select.closest('form').querySelector('.probation-warning');

                function updatePreview() {

                    const option = select.options[select.selectedIndex];

                    if (!option || !option.value) {

                        warningBox.classList.add('hidden');
                        warningBox.innerHTML = '';
                        return;
                    }

                    const name = option.dataset.name;
                    const probation = option.dataset.probation === '1';

                    if (probation) {

                        warningBox.innerHTML =
                            `<b>Probation Warning:</b> ${name} will be placed on probation due to 2 or more failing QPI values.<br>
                            This may restrict officer privileges depending on SACDEV policy.`;

                        warningBox.classList.remove('hidden');

                    }
                    else {

                        warningBox.classList.add('hidden');
                        warningBox.innerHTML = '';

                    }

                }

                select.addEventListener('change', updatePreview);

                updatePreview();

            });

        })();
    </script>
</x-app-layout>