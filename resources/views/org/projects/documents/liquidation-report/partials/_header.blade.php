<div class="border border-slate-300 bg-white mb-6">

    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            Project Information
        </div>
    </div>

    <div class="px-4 py-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-[12px]">

        <div>
            <span class="font-medium text-slate-700">Project Name:</span>
            <span class="ml-2">{{ $project->title }}</span>
        </div>

        <div>
            <span class="font-medium text-slate-700">Organization:</span>
            <span class="ml-2">{{ $project->organization->name ?? '' }}</span>
        </div>

        <div>
            <span class="font-medium text-slate-700">Implementation Date:</span>
            <span class="ml-2">
                {{ $project->proposalDocument?->proposalData?->start_date ?? '' }}
            </span>
        </div>

        <div>
            <span class="font-medium text-slate-700">Project Head:</span>
            <span class="ml-2">
                {{ $project->projectHead?->user?->name ?? '' }}
            </span>
        </div>

        <div>
            <span class="font-medium text-slate-700">Position:</span>
            <span class="ml-2">
                {{ $project->projectHead?->role ?? '' }}
            </span>
        </div>

        <div>
            <label class="font-medium text-slate-700">
                Contact Number:
            </label>

            <input
                type="text"
                name="contact_number"
                value="{{ old('contact_number', $report->contact_number ?? '') }}"
                class="ml-2 border border-slate-300 px-2 py-1 text-[12px] w-[200px]"
            >
        </div>

    </div>

</div>