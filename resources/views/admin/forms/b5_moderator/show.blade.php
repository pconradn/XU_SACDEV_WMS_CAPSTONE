<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">B-5 Moderator Submission</h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $submission->organization->name ?? ('Org #' . $submission->organization_id) }}
                    • Target SY: {{ $submission->targetSchoolYear->label ?? $submission->target_school_year_id }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                @include('admin.forms.b5_moderator.partials._status_badge', ['status' => $submission->status])

                <a href="{{ route('admin.moderator_submissions.index') }}"
                   class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                    Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="font-semibold">Success</div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Error</div>
                <div class="text-sm mt-1">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Please fix the errors below.</div>
                <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($submission->edit_requested)
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="font-semibold">Edit Request Pending</div>
                <div class="text-sm mt-1">
                    The moderator requested to edit this form.
                    @if($submission->edit_requested_at)
                        <span class="ml-1 text-amber-800/80">({{ $submission->edit_requested_at->format('M d, Y h:i A') }})</span>
                    @endif
                </div>

                @if($submission->edit_request_message)
                    <div class="mt-3 rounded-lg border border-amber-200 bg-white/60 p-3 text-sm whitespace-pre-line">
                        <div class="font-semibold mb-1">Message</div>
                        {{ $submission->edit_request_message }}
                    </div>
                @endif
            </div>
        @endif


        {{-- SACDEV remarks display --}}
        @if($submission->sacdev_remarks && in_array($submission->status, ['returned_by_sacdev','approved_by_sacdev'], true))
            <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="text-sm font-semibold text-slate-900">SACDEV Notes</div>
                <div class="mt-2 text-sm text-slate-700 whitespace-pre-line">{{ $submission->sacdev_remarks }}</div>
                <div class="mt-2 text-xs text-slate-500">
                    @if($submission->sacdevReviewedBy)
                        Reviewed by: {{ $submission->sacdevReviewedBy->name }}
                    @endif
                    @if($submission->sacdev_reviewed_at)
                        <span class="ml-2">at {{ $submission->sacdev_reviewed_at->format('M d, Y h:i A') }}</span>
                    @endif
                </div>
            </div>
        @endif

        {{-- ACTIONS --}}
        <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-slate-700">
                    Actions:
                    <span class="font-semibold">Approve</span> or <span class="font-semibold">Return</span> when submitted.
                    <span class="font-semibold">Revert Approval</span> only when approved.
                    <span class="font-semibold">Allow Edit</span> only when an edit request is pending.
                </div>

                <div class="flex flex-wrap gap-2">
                    {{-- Approve / Return (only if submitted) --}}
                    @if($submission->status === 'submitted_to_sacdev')
                        <form method="POST" action="{{ route('admin.moderator_submissions.approve', $submission) }}">
                            @csrf
                            <button class="inline-flex justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                Approve
                            </button>
                        </form>

                        <button type="button" id="openReturnModalBtn"
                                class="inline-flex justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                            Return w/ Remarks
                        </button>
                    @endif

                    {{-- Allow Edit (only if request pending and currently locked) --}}
                    @if($submission->edit_requested && in_array($submission->status, ['submitted_to_sacdev','approved_by_sacdev'], true))
                        <button type="button" id="openAllowEditModalBtn"
                                class="inline-flex justify-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100">
                            Allow Edit
                        </button>
                    @endif

                    {{-- Revert Approval (only when approved) --}}
                    @if($submission->status === 'approved_by_sacdev')
                        <button type="button" id="openRevertApprovalModalBtn"
                                class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                            Revert Approval
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @include('admin.forms.b5_moderator.partials._allow_edit_modal', ['submission' => $submission])
        @include('admin.forms.b5_moderator.partials._revert_approval_modal', ['submission' => $submission])


        {{-- DETAILS --}}
        @component('admin.forms.b5_moderator.partials._detail_section', ['title' => 'Moderator Identity'])
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Full Name</div>
                    <div class="mt-1 text-sm text-slate-900 font-semibold">{{ $submission->full_name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Email</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->email ?? '—' }}</div>
                </div>
                <div class="sm:col-span-2">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Photo ID</div>
                    <div class="mt-1 text-sm text-slate-900 break-all">
                        {{ $submission->photo_id_path ?? '—' }}
                    </div>
                    <div class="mt-1 text-xs text-slate-500">
                        We’ll add a Storage URL preview later once your public disk is confirmed.
                    </div>
                </div>
            </div>
        @endcomponent




        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Personal Information</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Birthday</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->birthday ? $submission->birthday->format('M d, Y') : '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Age</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->age ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Sex</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->sex ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Religion</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->religion ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Employment Information</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Designation</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->university_designation ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Unit/Department</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->unit_department ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Status</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->employment_status ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Years of Service</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->years_of_service ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Contact Information</h3>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Mobile</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->mobile_number ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Landline</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->landline ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Facebook URL</div>
                    <div class="mt-1 text-sm text-slate-900 break-all">{{ $submission->facebook_url ?? '—' }}</div>
                </div>
                <div class="sm:col-span-2">
                    <div class="text-xs uppercase tracking-wide text-slate-500">City Address</div>
                    <div class="mt-1 text-sm text-slate-900 whitespace-pre-line">{{ $submission->city_address ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Leadership Involvement</h3>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2 px-2">Organization</th>
                            <th class="py-2 px-2">Position</th>
                            <th class="py-2 px-2">Address</th>
                            <th class="py-2 px-2">Years</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($submission->leaderships as $row)
                            <tr>
                                <td class="py-2 px-2">{{ $row->organization_name ?? '—' }}</td>
                                <td class="py-2 px-2">{{ $row->position ?? '—' }}</td>
                                <td class="py-2 px-2">{{ $row->organization_address ?? '—' }}</td>
                                <td class="py-2 px-2">{{ $row->inclusive_years ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 px-2 text-slate-600">No entries.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Moderator Background</h3>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Was moderator before?</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->was_moderator_before ? 'Yes' : 'No' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">If yes, organization name</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->moderated_org_name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Served nominating org before?</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->served_nominating_org_before ? 'Yes' : 'No' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">If yes, years</div>
                    <div class="mt-1 text-sm text-slate-900">{{ $submission->served_nominating_org_years ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Special Skills / Interests</h3>
            <div class="mt-3 text-sm text-slate-900 whitespace-pre-line">
                {{ $submission->skills_and_interests ?? '—' }}
            </div>
        </div>

        {{-- Return Modal --}}
        @include('admin.forms.b5_moderator.partials._return_modal', ['submission' => $submission])
    </div>

    <script>
        (function () {
            const btn = document.getElementById('openReturnModalBtn');
            const modal = document.getElementById('returnModal');
            const closeBtns = document.querySelectorAll('[data-close-return-modal]');

            if (!btn || !modal) return;

            btn.addEventListener('click', () => modal.classList.remove('hidden'));
            closeBtns.forEach(b => b.addEventListener('click', () => modal.classList.add('hidden')));
        })();
    </script>
    <script>
        (function () {
            // Return modal
            const returnBtn = document.getElementById('openReturnModalBtn');
            const returnModal = document.getElementById('returnModal');
            const closeReturnBtns = document.querySelectorAll('[data-close-return-modal]');
            if (returnBtn && returnModal) {
                returnBtn.addEventListener('click', () => returnModal.classList.remove('hidden'));
                closeReturnBtns.forEach(b => b.addEventListener('click', () => returnModal.classList.add('hidden')));
            }

            // Allow edit modal
            const allowBtn = document.getElementById('openAllowEditModalBtn');
            const allowModal = document.getElementById('allowEditModal');
            const closeAllowBtns = document.querySelectorAll('[data-close-allow-edit-modal]');
            if (allowBtn && allowModal) {
                allowBtn.addEventListener('click', () => allowModal.classList.remove('hidden'));
                closeAllowBtns.forEach(b => b.addEventListener('click', () => allowModal.classList.add('hidden')));
            }

            // Revert approval modal
            const revertBtn = document.getElementById('openRevertApprovalModalBtn');
            const revertModal = document.getElementById('revertApprovalModal');
            const closeRevertBtns = document.querySelectorAll('[data-close-revert-approval-modal]');
            if (revertBtn && revertModal) {
                revertBtn.addEventListener('click', () => revertModal.classList.remove('hidden'));
                closeRevertBtns.forEach(b => b.addEventListener('click', () => revertModal.classList.add('hidden')));
            }
        })();
        </script>
</x-app-layout>
