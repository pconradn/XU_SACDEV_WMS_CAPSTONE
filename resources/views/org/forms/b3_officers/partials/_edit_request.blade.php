@php
    $canRequestEdit = !$canEdit && in_array(($registration->status ?? null), ['approved_by_sacdev'], true);
    $hasEditFields = isset($registration->edit_requested);
@endphp

@if($canRequestEdit)
<div class="rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-sm p-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        {{-- LEFT --}}
        <div class="space-y-2">

            <div class="flex items-center gap-2 text-xs font-semibold text-amber-900">
                <i data-lucide="lock" class="w-4 h-4"></i>
                Form Locked
            </div>

            <div class="text-[11px] text-amber-800">
                This form has already been approved. You may request SACDEV to reopen it for edits.
            </div>

            @if($hasEditFields && $registration->edit_requested)
                <div class="mt-2 rounded-xl border border-amber-300 bg-white p-3 text-[11px] text-amber-900">
                    <div class="font-semibold">Edit request pending</div>
                    <div class="mt-1 whitespace-pre-line text-amber-800">
                        {{ $registration->edit_request_reason }}
                    </div>
                </div>
            @endif

        </div>

        {{-- RIGHT --}}
        @if(!$hasEditFields)

            <div class="sm:w-80 rounded-xl border border-rose-200 bg-rose-50 p-3 text-[11px] text-rose-800">
                <div class="font-semibold">Edit request unavailable</div>
                <div class="mt-1">
                    Edit request fields are not enabled for this form.
                </div>
            </div>

        @else

            @if(!$registration->edit_requested)

                <form method="POST"
                      action="{{ route('org.rereg.b3.officers-list.requestEdit') }}"
                      class="sm:w-80 space-y-2">
                    @csrf

                    <textarea name="edit_request_reason"
                              rows="3"
                              class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:ring-1 focus:ring-amber-400"
                              placeholder="Explain what needs to be updated..."
                              required></textarea>

                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-700 transition">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                        Request Edit
                    </button>
                </form>

            @endif

        @endif

    </div>

</div>
@endif