<x-app-layout>

    <div class="mx-auto max-w-6xl px-4 py-6">

        {{-- Header --}}
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">
                B-3 Officers Submission
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                Organization:
                <span class="font-semibold text-slate-900">
                    {{ $submission->organization->name ?? ('Org #' . $submission->organization_id) }}
                </span>

                • Target SY:
                <span class="font-semibold text-slate-900">
                    {{ $submission->targetSchoolYear->label ?? $submission->target_school_year_id }}
                </span>
            </p>
        </div>


        {{-- Success --}}
        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="font-semibold">Success</div>
                <div class="text-sm mt-1">
                    {{ session('success') }}
                </div>
            </div>
        @endif


        {{-- Error --}}
        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Error</div>
                <div class="text-sm mt-1">
                    {{ session('error') }}
                </div>
            </div>
        @endif


        {{-- Submission Info --}}
        <div class="mb-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="text-sm text-slate-600">
                        Status
                    </div>

                    <div class="font-semibold text-slate-900">
                        {{ $submission->status }}
                    </div>
                </div>

                <div class="text-sm text-slate-600">
                    Submitted:

                    <span class="font-medium text-slate-900">
                        {{ $submission->submitted_at?->format('M d, Y h:i A') ?? '—' }}
                    </span>
                </div>

            </div>


            {{-- SACDEV remarks --}}
            @if($submission->sacdev_remarks)

                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-900">

                    <div class="font-semibold">
                        SACDEV Remarks
                    </div>

                    <div class="mt-1 text-sm whitespace-pre-line">
                        {{ $submission->sacdev_remarks }}
                    </div>

                </div>

            @endif

        </div>



        {{-- Edit request pending --}}
        @if($submission->edit_requested)

            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-900">

                <div class="font-semibold">
                    Edit Request Pending
                </div>

                <div class="mt-2 text-sm whitespace-pre-line">
                    {{ $submission->edit_request_reason }}
                </div>


                <form method="POST"
                      action="{{ route('admin.officer_submissions.allow_edit', $submission->id) }}"
                      class="mt-4">

                    @csrf

                    <label class="block text-sm font-medium text-amber-900">
                        SACDEV Note (optional)
                    </label>

                    <textarea name="sacdev_remarks"
                              rows="3"
                              class="mt-1 w-full rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm"></textarea>

                    <button type="submit"
                            class="mt-3 inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Allow Edit (Return to Org)
                    </button>

                </form>

            </div>

        @endif



        {{-- Officers table --}}
        <div class="mb-4">

            @include('admin.forms.b3_officers.partials._officers_table', [
                'items' => $submission->items,
                'conflictsByItemId' => $conflictsByItemId ?? [],
            ])

        </div>



        {{-- Actions --}}
        @if($submission->status === 'submitted_to_sacdev')

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

                <h3 class="text-base font-semibold text-slate-900">
                    SACDEV Actions
                </h3>

                <p class="mt-1 text-sm text-slate-600">
                    Return with remarks or approve this submission.
                </p>



                {{-- Return form --}}
                <form method="POST"
                      action="{{ route('admin.officer_submissions.return', $submission->id) }}"
                      class="mt-4">

                    @csrf

                    <label class="block text-sm font-medium text-slate-700">
                        Return Remarks (required)
                    </label>

                    <textarea name="sacdev_remarks"
                              rows="3"
                              class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                              required></textarea>


                    <div class="mt-3 flex flex-col gap-2 sm:flex-row">

                        <button type="submit"
                                class="inline-flex justify-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100">
                            Return to Organization
                        </button>


                        {{-- Approve preview button --}}
                        <button type="button"
                                onclick="showApprovalPreview()"
                                class="inline-flex justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">

                            Approve

                        </button>

                    </div>

                </form>


                {{-- Hidden approve form --}}
                <form id="approveForm"
                      method="POST"
                      action="{{ route('admin.officer_submissions.approve', $submission->id) }}">

                    @csrf

                </form>


                {{-- Approval Preview Modal --}}
                <div id="approvalPreviewModal"
                    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">

                    <div class="w-full max-w-2xl rounded-xl bg-white shadow-xl">

                        {{-- Header --}}
                        <div class="border-b px-5 py-4 flex justify-between items-center">

                            <div class="font-semibold text-slate-900">
                                Approval Preview
                            </div>

                            <button onclick="closeApprovalPreview()"
                                    class="text-slate-400 hover:text-slate-600 text-lg">
                                ✕
                            </button>

                        </div>


                        {{-- Body --}}
                        <div class="p-5 space-y-4 max-h-[400px] overflow-y-auto text-sm">

                            <div class="font-semibold text-slate-800">
                                The following actions will occur:
                            </div>

                            <ul class="list-disc pl-5 space-y-1 text-slate-700">

                                @foreach($submission->items as $item)

                                    @php
                                        $conflicts = $conflictsByItemId[$item->id] ?? [];
                                        $exists = \App\Models\OfficerEntry::query()
                                            ->where('organization_id', $submission->organization_id)
                                            ->where('school_year_id', $submission->target_school_year_id)
                                            ->where('student_id_number', $item->student_id_number)
                                            ->exists();
                                    @endphp


                                    <li>

                                        <span class="font-semibold">
                                            {{ $item->officer_name }}
                                        </span>

                                        —

                                        @if($exists)
                                            <span class="text-blue-700">
                                                UPDATE existing officer entry
                                            </span>
                                        @else
                                            <span class="text-emerald-700">
                                                CREATE new officer entry
                                            </span>
                                        @endif


                                        @if($item->isTreasurer())
                                            <span class="text-indigo-700">
                                                • Provision Treasurer account
                                            </span>
                                        @endif


                                        @if(!empty($conflicts))
                                            <div class="text-amber-700 mt-1">
                                                ⚠ Conflict: also major officer in:

                                                <ul class="list-disc pl-5">

                                                    @foreach($conflicts as $conflict)
                                                        <li>
                                                            {{ $conflict['organization_name'] }}
                                                            ({{ ucfirst(str_replace('_',' ',$conflict['major_officer_role'])) }})
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        @endif

                                    </li>

                                @endforeach

                            </ul>

                        </div>


                        {{-- Footer --}}
                        <div class="border-t px-5 py-4 flex justify-end gap-2">

                            <button onclick="closeApprovalPreview()"
                                    class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-semibold">
                                Cancel
                            </button>

                            <button onclick="submitApproval()"
                                    class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                                Confirm Approval
                            </button>

                        </div>

                    </div>

                </div>




            </div>

        @else

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm text-sm text-slate-600">
                No actions available for this status.
            </div>

        @endif

    </div>



    {{-- Approval preview script --}}
    <script>

        function showApprovalPreview()
        {
            document
                .getElementById('approvalPreviewModal')
                .classList.remove('hidden');

            document
                .getElementById('approvalPreviewModal')
                .classList.add('flex');
        }


        function closeApprovalPreview()
        {
            document
                .getElementById('approvalPreviewModal')
                .classList.add('hidden');

            document
                .getElementById('approvalPreviewModal')
                .classList.remove('flex');
        }


        function submitApproval()
        {
            document.getElementById('approveForm').submit();
        }

    </script>

</x-app-layout>