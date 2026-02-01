<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
    <h2 class="text-base font-semibold text-slate-900">Organization Identity</h2>
    <p class="text-sm text-slate-500 mt-1">Fill in your organization’s basic details for this school year.</p>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-1">
            <label class="block text-sm font-medium text-slate-700">Organization Logo</label>
            <input type="file" name="logo"
                   class="mt-1 block w-full text-sm text-slate-700 file:mr-3 file:py-2 file:px-3
                          file:rounded-lg file:border-0 file:text-sm file:font-medium
                          file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200
                          border border-slate-200 rounded-lg">
            @error('logo') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror

            @if($submission->logo_path)
                <p class="text-xs text-slate-500 mt-2">Current logo uploaded.</p>
            @endif
        </div>

        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Acronym</label>
                <input type="text" name="org_acronym"
                       value="{{ old('org_acronym', $submission->org_acronym) }}"
                       class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Complete Organization Name</label>
                <input type="text" name="org_name"
                       value="{{ old('org_name', $submission->org_name) }}"
                       class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                @error('org_name') <p class="text-sm text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Mission</label>
                <textarea name="mission" rows="3"
                          class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">{{ old('mission', $submission->mission) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Vision</label>
                <textarea name="vision" rows="3"
                          class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">{{ old('vision', $submission->vision) }}</textarea>
            </div>
        </div>
    </div>
</div>
