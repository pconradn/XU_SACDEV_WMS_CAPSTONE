@php
    $canRequestEdit = $isLocked && in_array(($registration->status ?? null), ['approved_by_sacdev'], true);
    $hasEditFields = isset($registration->edit_requested);
@endphp

@if($canRequestEdit)
    <div class="mb-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="font-semibold text-slate-900">This form is locked.</div>
                <div class="mt-1 text-sm text-slate-600">
                    You can request SACDEV to allow edits if you need to update officer entries.
                </div>

                @if($hasEditFields && $registration->edit_requested)
                    <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3 text-amber-900">
                        <div class="font-semibold">Edit request pending</div>
                        <div class="mt-1 text-sm whitespace-pre-line">
                            {{ $registration->edit_request_reason }}
                        </div>
                    </div>
                @endif
            </div>

            @if(!$hasEditFields)
                <div class="mt-3 sm:mt-0 sm:w-96 rounded-lg border border-rose-200 bg-rose-50 p-3 text-rose-900">
                    <div class="font-semibold text-sm">Edit request fields not enabled</div>
                    <div class="text-sm mt-1">
                        Your OfficerSubmission table/model doesn’t have edit-request columns yet.
                        If you want, we can add a migration for: edit_requested, edit_request_reason, edit_requested_by_user_id, edit_requested_at.
                    </div>
                </div>
            @else
                @if(!$registration->edit_requested)
                    <form method="POST" action="{{ route('org.rereg.b3.officers-list.requestEdit') }}" class="mt-3 sm:mt-0 sm:w-96">
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
            @endif
        </div>
    </div>
@endif