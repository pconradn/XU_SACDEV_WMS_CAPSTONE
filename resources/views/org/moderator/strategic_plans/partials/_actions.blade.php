<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Return --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
        <h2 class="text-base font-semibold text-slate-900">Return to Organization</h2>
        <p class="text-sm text-slate-500 mt-1">
            Required. Use this when the submission needs changes.
        </p>

        <form method="POST" action="{{ route('org.moderator.strategic_plans.return', $submission) }}" class="mt-4 space-y-3">
            @csrf

            <textarea name="moderator_remarks" rows="4"
                      class="w-full rounded-lg border-slate-200 focus:border-rose-500 focus:ring-rose-500"
                      placeholder="Write your remarks clearly (what to fix, what’s missing, etc.)">{{ old('moderator_remarks', $submission->moderator_remarks) }}</textarea>

            <button type="submit"
                    class="inline-flex items-center rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                Return with Remarks
            </button>
        </form>
    </div>

    {{-- Forward --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
        <h2 class="text-base font-semibold text-slate-900">Forward to SACDEV</h2>
        <p class="text-sm text-slate-500 mt-1">
            Optional note. Use this when everything looks okay and you’re endorsing it for SACDEV review.
        </p>

        <form method="POST" action="{{ route('org.moderator.strategic_plans.forward', $submission) }}" class="mt-4 space-y-3">
            @csrf

            <textarea name="moderator_note" rows="4"
                      class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                      placeholder="(optional) Add moderator note for SACDEV...">{{ old('moderator_note') }}</textarea>

            <button type="submit"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Noted & Forward
            </button>
        </form>
    </div>
</div>
