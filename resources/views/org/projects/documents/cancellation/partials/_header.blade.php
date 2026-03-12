<div class="border border-slate-300 bg-white mb-6">

    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            Notice of Cancellation
        </div>
    </div>

    <div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-[12px]">

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Organization
            </label>

            <input
            type="text"
            value="{{ $project->organization->name }}"
            disabled
            class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">
        </div>

        <div>
            <label class="block text-[10px] font-medium text-blue-900 italic">
                Name of Activity
            </label>

            <input
            type="text"
            value="{{ $project->title }}"
            disabled
            class="mt-1 w-full border border-slate-300 px-3 py-1 text-[12px] bg-slate-50">
        </div>

    </div>

</div>