<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Moderator Background</h3>

    <div class="mt-4 space-y-4">
        <div class="rounded-lg border border-slate-200 p-4">
            <div class="flex items-center gap-2">
                <input type="checkbox" name="was_moderator_before" value="1"
                       class="h-4 w-4 rounded border-slate-300"
                       {{ old('was_moderator_before', $submission->was_moderator_before) ? 'checked' : '' }}
                       {{ $isLocked ? 'disabled' : '' }}>
                <span class="text-sm text-slate-800">Have you been moderator of a student organization before?</span>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-slate-700">If yes, please state the organization name</label>
                <input type="text" name="moderated_org_name"
                       value="{{ old('moderated_org_name', $submission->moderated_org_name) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       {{ $isLocked ? 'disabled' : '' }}>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 p-4">
            <div class="flex items-center gap-2">
                <input type="checkbox" name="served_nominating_org_before" value="1"
                       class="h-4 w-4 rounded border-slate-300"
                       {{ old('served_nominating_org_before', $submission->served_nominating_org_before) ? 'checked' : '' }}
                       {{ $isLocked ? 'disabled' : '' }}>
                <span class="text-sm text-slate-800">
                    Have you served/been serving as moderator of the nominating organization?
                </span>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-slate-700">If yes, how many years?</label>
                <input type="number" min="0" max="80" name="served_nominating_org_years"
                       value="{{ old('served_nominating_org_years', $submission->served_nominating_org_years) }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       {{ $isLocked ? 'disabled' : '' }}>
            </div>
        </div>
    </div>
</div>
