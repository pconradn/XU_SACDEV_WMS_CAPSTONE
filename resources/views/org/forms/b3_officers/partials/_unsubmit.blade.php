@if(($registration->status ?? null) === 'submitted_to_sacdev' && $isPresident)

<div class="rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white shadow-sm p-4">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">

        <div class="space-y-1">

            <div class="flex items-center gap-2 text-[11px] font-semibold text-amber-900">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                Submission Sent
            </div>

            <div class="text-[11px] text-amber-800">
                You can pull back this submission to edit officer details and resubmit.
            </div>

        </div>

        <form method="POST"
              action="{{ route('org.rereg.b3.officers-list.unsubmit') }}"
              class="w-full sm:w-auto">
            @csrf

            <button type="submit"
                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl border border-amber-300 bg-white px-4 py-2 text-xs font-semibold text-amber-900 hover:bg-amber-100 transition shadow-sm">
                <i data-lucide="undo-2" class="w-3.5 h-3.5"></i>
                Pull Back
            </button>
        </form>

    </div>

</div>

@endif