@if($canEdit)
<div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

    {{-- LEFT TEXT --}}
    <div class="text-[11px] text-slate-500">
        Ensure all officer details are complete before submitting
    </div>

    {{-- ACTIONS --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-2 shrink-0">

        {{-- SAVE DRAFT --}}
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition whitespace-nowrap">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            Save Draft
        </button>

        {{-- SUBMIT --}}
        <button type="submit"
                formaction="{{ route('org.rereg.b3.officers-list.submit') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition whitespace-nowrap">
            <i data-lucide="send" class="w-3.5 h-3.5"></i>
            Submit to SACDEV
        </button>

    </div>

</div>
@else
<div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-[11px] text-blue-700 flex items-center gap-2">
    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
    View Only Mode — Only the President can edit while status is Draft or Returned
</div>
@endif