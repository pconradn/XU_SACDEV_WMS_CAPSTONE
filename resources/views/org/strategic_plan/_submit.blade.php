<form method="POST" action="{{ route('org.rereg.b1.submit') }}">
    @csrf

    <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
        <h2 class="text-base font-semibold text-slate-900">
            Submit to Moderator
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Once submitted, you won’t be able to edit until it is returned.
        </p>

        <div class="mt-4 flex items-start gap-3">
            <input
                id="confirmSubmit"
                type="checkbox"
                name="confirm"
                value="yes"
                class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            >
            <label for="confirmSubmit" class="text-sm text-slate-700">
                I confirm that the Strategic Plan details are complete and ready for review.
            </label>
        </div>

        @error('confirm')
            <p class="text-sm text-rose-600 mt-2">{{ $message }}</p>
        @enderror

        <div class="mt-4">
            <button
                type="submit"
                class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold text-white"
                :class="allProjectsValid()
                    ? 'bg-emerald-600 hover:bg-emerald-700'
                    : 'bg-slate-300 cursor-not-allowed'"
                :disabled="!allProjectsValid()"
            >
                Submit
            </button>

            <p class="text-sm mt-2"
               :class="allProjectsValid()
                    ? 'text-emerald-700'
                    : 'text-rose-700'">
                <span
                    x-text="allProjectsValid()
                        ? 'Ready to submit.'
                        : 'Each project must have at least 1 Objective, 1 Beneficiary, and 1 Deliverable.'">
                </span>
            </p>
        </div>
    </div>
</form>
