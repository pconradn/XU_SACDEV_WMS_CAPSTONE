<div class="mt-4 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="p-5 border-b">
        <h3 class="text-xs font-semibold text-slate-500 uppercase">
            Moderator Background
        </h3>
    </div>

    <div class="grid md:grid-cols-2">

        <div class="p-5 md:border-r">
            <div class="divide-y">

                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-400">Was moderator before</span>
                    <span class="text-sm font-medium">
                        @if($submission->was_moderator_before)
                            <span class="px-2 py-0.5 text-xs rounded-full 
                                bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Yes
                            </span>
                        @else
                            <span class="px-2 py-0.5 text-xs rounded-full 
                                bg-slate-100 text-slate-600 border">
                                No
                            </span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Organization</span>
                    <span class="text-slate-900 font-medium">
                        {{ $submission->moderated_org_name ?? '—' }}
                    </span>
                </div>

            </div>
        </div>


        <div class="p-5">
            <div class="divide-y">

                <div class="flex justify-between items-center py-2">
                    <span class="text-slate-400">Served nominating org</span>
                    <span class="text-sm font-medium">
                        @if($submission->served_nominating_org_before)
                            <span class="px-2 py-0.5 text-xs rounded-full 
                                bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Yes
                            </span>
                        @else
                            <span class="px-2 py-0.5 text-xs rounded-full 
                                bg-slate-100 text-slate-600 border">
                                No
                            </span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Years</span>
                    <span class="text-slate-900 font-medium">
                        {{ $submission->served_nominating_org_years ?? '—' }}
                    </span>
                </div>

            </div>
        </div>

    </div>


    <div class="border-t p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase mb-3">
            Special Skills / Interests
        </h3>

        <div class="text-sm text-slate-900 whitespace-pre-line leading-relaxed">
            {{ $submission->skills_and_interests ?? '—' }}
        </div>
    </div>

</div>