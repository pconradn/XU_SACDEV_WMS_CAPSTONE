<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-slate-600">
            Saving creates/updates a draft. Submission + signatures will be added next.
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                Cancel
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Save Draft
            </button>
        </div>
    </div>
</div>