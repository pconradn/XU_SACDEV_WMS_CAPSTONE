<div class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center">
    <button type="submit"
            class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
            {{ $isLocked ? 'disabled' : '' }}>
        Save Draft
    </button>

    <button type="submit"
            formaction="{{ route('org.rereg.b3.officers-list.submit') }}"
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