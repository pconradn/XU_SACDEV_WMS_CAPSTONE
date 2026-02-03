<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">
                B-3 Officers List
                <span class="text-slate-500 font-normal">(Target SY: {{ $targetSyId }})</span>
            </h2>
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

        {{-- Unsubmit option (only when submitted) --}}
        @if($registration->status === 'submitted_to_sacdev')
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="font-semibold">Need to change something?</div>
                <p class="mt-1 text-sm">Pull back the submission to edit and resubmit.</p>

                <form method="POST" action="{{ route('org.b3.officers-list.unsubmit') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                            class="inline-flex justify-center rounded-lg border border-amber-300 bg-white px-4 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100">
                        Pull Back Submission
                    </button>
                </form>
            </div>
        @endif


        @if($isLocked && in_array($registration->status, ['approved_by_sacdev'], true))
            <div class="mb-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="font-semibold text-slate-900">This form is locked.</div>
                        <div class="mt-1 text-sm text-slate-600">
                            You can request SACDEV to allow edits if you need to update officer entries.
                        </div>

                        @if($registration->edit_requested)
                            <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3 text-amber-900">
                                <div class="font-semibold">Edit request pending</div>
                                <div class="mt-1 text-sm whitespace-pre-line">
                                    {{ $registration->edit_request_reason }}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!$registration->edit_requested)
                        <form method="POST" action="{{ route('org.b3.officers-list.requestEdit') }}" class="mt-3 sm:mt-0 sm:w-96">
                            @csrf
                            <label class="block text-sm font-medium text-slate-700">Reason (required)</label>
                            <textarea name="edit_request_reason" rows="3"
                                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                    placeholder="Explain what needs to be changed (e.g., officer replaced, wrong ID number, etc.)"
                                    required></textarea>

                            <button type="submit"
                                    class="mt-2 inline-flex w-full justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                Request Edit
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('org.b3.officers-list.saveDraft') }}">
            @csrf
            <input type="hidden" name="certified" value="0">

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-slate-900">List of Officers</h3>

                    <button type="button"
                            id="addRowBtn"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                            {{ $isLocked ? 'disabled' : '' }}>
                        + Add row
                    </button>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-2 px-2">Position</th>
                                <th class="py-2 px-2">Officer Name</th>
                                <th class="py-2 px-2">Student ID</th>
                                <th class="py-2 px-2">Course & Year</th>
                                <th class="py-2 px-2">Latest QPI</th>
                                <th class="py-2 px-2">Mobile #</th>
                                <th class="py-2 px-2 text-right">Remove</th>
                            </tr>
                        </thead>

                        <tbody id="rowsTbody" class="divide-y divide-slate-100">
                            @php
                                $oldItems = old('items');
                                $items = is_array($oldItems) ? $oldItems : $registration->items->map(fn($i) => $i->toArray())->toArray();
                                $items = $items ?: []; // allow empty
                            @endphp

                            @foreach($items as $idx => $row)
                                <tr class="row-item">
                                    <td class="py-2 px-2">
                                        <input type="text" name="items[{{ $idx }}][position]"
                                               value="{{ $row['position'] ?? '' }}"
                                               class="w-48 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                               {{ $isLocked ? 'disabled' : '' }}>
                                    </td>

                                    <td class="py-2 px-2">
                                        <input type="text" name="items[{{ $idx }}][officer_name]"
                                               value="{{ $row['officer_name'] ?? '' }}"
                                               class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                               {{ $isLocked ? 'disabled' : '' }}>
                                    </td>

                                    <td class="py-2 px-2">
                                        <input type="text" name="items[{{ $idx }}][student_id_number]"
                                               value="{{ $row['student_id_number'] ?? '' }}"
                                               class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                               {{ $isLocked ? 'disabled' : '' }}>
                                    </td>

                                    <td class="py-2 px-2">
                                        <input type="text" name="items[{{ $idx }}][course_and_year]"
                                               value="{{ $row['course_and_year'] ?? '' }}"
                                               class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                               {{ $isLocked ? 'disabled' : '' }}>
                                    </td>

                                    <td class="py-2 px-2">
                                        <input type="number" step="0.01" min="0" max="4"
                                               name="items[{{ $idx }}][latest_qpi]"
                                               value="{{ $row['latest_qpi'] ?? '' }}"
                                               class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                               {{ $isLocked ? 'disabled' : '' }}>
                                    </td>

                                    <td class="py-2 px-2">
                                        <input type="text" name="items[{{ $idx }}][mobile_number]"
                                               value="{{ $row['mobile_number'] ?? '' }}"
                                               class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                               {{ $isLocked ? 'disabled' : '' }}>
                                    </td>

                                    <td class="py-2 px-2 text-right">
                                        <button type="button"
                                                class="removeRowBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 disabled:opacity-50"
                                                {{ $isLocked ? 'disabled' : '' }}>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- If no rows at all, show a hint --}}
                            @if(count($items) === 0)
                                <tr id="emptyRowHint">
                                    <td colspan="7" class="py-6 px-2 text-sm text-slate-600">
                                        No officer rows yet. Click <span class="font-semibold">Add row</span> to start encoding.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Certification --}}
            <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Certification</h3>
                <p class="mt-1 text-sm text-slate-600">
                    By submitting this form, I certify that the information provided is true and correct.
                </p>

                <div class="mt-3 flex items-center gap-2">
                    <input type="checkbox" name="certified" value="1"
                           class="h-4 w-4 rounded border-slate-300"
                           {{ old('certified', $registration->certified) ? 'checked' : '' }}
                           {{ $isLocked ? 'disabled' : '' }}>
                    <span class="text-sm text-slate-800">I certify the information above.</span>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center">
                <button type="submit"
                        class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Save Draft
                </button>

                <button type="submit"
                        formaction="{{ route('org.b3.officers-list.submit') }}"
                        class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Submit to SACDEV
                </button>

                @if($isLocked)
                    <div class="text-sm text-slate-500 sm:ml-3">
                        This form is locked because it is already submitted or approved.
                    </div>
                @endif
            </div>
        </form>
    </div>

    {{-- Inline row template + JS --}}
    <script>
        (function () {
            const isLocked = @json($isLocked);

            const tbody = document.getElementById('rowsTbody');
            const addBtn = document.getElementById('addRowBtn');

            function reindex() {
                const rows = tbody.querySelectorAll('tr.row-item');
                rows.forEach((row, idx) => {
                    row.querySelectorAll('input[name^="items["]').forEach((input) => {
                        input.name = input.name.replace(/items\[\d+\]/, `items[${idx}]`);
                    });
                });
            }

            function removeEmptyHintIfExists() {
                const hint = document.getElementById('emptyRowHint');
                if (hint) hint.remove();
            }

            function addRow() {
                removeEmptyHintIfExists();

                const idx = tbody.querySelectorAll('tr.row-item').length;

                const tr = document.createElement('tr');
                tr.className = 'row-item';

                tr.innerHTML = `
                    <td class="py-2 px-2">
                        <input type="text" name="items[${idx}][position]"
                            class="w-48 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" name="items[${idx}][officer_name]"
                            class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" name="items[${idx}][student_id_number]"
                            class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" name="items[${idx}][course_and_year]"
                            class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                    </td>
                    <td class="py-2 px-2">
                        <input type="number" step="0.01" min="0" max="4" name="items[${idx}][latest_qpi]"
                            class="w-28 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" name="items[${idx}][mobile_number]"
                            class="w-40 rounded-lg border border-slate-300 px-3 py-2 text-sm" ${isLocked ? 'disabled' : ''}>
                    </td>
                    <td class="py-2 px-2 text-right">
                        <button type="button"
                            class="removeRowBtn inline-flex rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 ${isLocked ? 'opacity-50 pointer-events-none' : ''}">
                            Remove
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
            }

            function onRemoveClick(e) {
                const btn = e.target.closest('.removeRowBtn');
                if (!btn) return;

                const row = btn.closest('tr.row-item');
                if (!row) return;

                row.remove();
                reindex();

                const remaining = tbody.querySelectorAll('tr.row-item').length;
                if (remaining === 0) {
                    const hint = document.createElement('tr');
                    hint.id = 'emptyRowHint';
                    hint.innerHTML = `
                        <td colspan="7" class="py-6 px-2 text-sm text-slate-600">
                            No officer rows yet. Click <span class="font-semibold">Add row</span> to start encoding.
                        </td>
                    `;
                    tbody.appendChild(hint);
                }
            }

            if (addBtn && !isLocked) {
                addBtn.addEventListener('click', addRow);
            }
            tbody.addEventListener('click', onRemoveClick);
        })();
    </script>
</x-app-layout>
