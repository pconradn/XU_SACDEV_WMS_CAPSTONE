        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">Strategic Plan Submission</h1>
                    <div class="text-sm text-slate-600 mt-1">
                        Org: <span class="font-semibold">{{ $submission->organization->name ?? ($submission->org_name ?? '—') }}</span>
                        @if(!empty($submission->org_acronym))
                            <span class="text-slate-500">({{ $submission->org_acronym }})</span>
                        @endif
                    </div>
                    <div class="text-sm text-slate-600">
                        Target School Year:
                        <span class="font-semibold">{{ $submission->targetSchoolYear->name ?? '—' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs px-2.5 py-1 rounded-full border
                        @if($submission->status === 'approved_by_sacdev')
                            bg-emerald-50 border-emerald-200 text-emerald-700
                        @elseif(str_contains($submission->status, 'returned'))
                            bg-rose-50 border-rose-200 text-rose-700
                        @elseif(in_array($submission->status, ['submitted_to_moderator','forwarded_to_sacdev']))
                            bg-amber-50 border-amber-200 text-amber-700
                        @else
                            bg-slate-50 border-slate-200 text-slate-700
                        @endif
                    ">
                        Status: {{ $submission->status }}
                    </span>

                    @if(session('success'))
                        <span class="text-xs px-2.5 py-1 rounded-full border bg-emerald-50 border-emerald-200 text-emerald-700">
                            {{ session('success') }}
                        </span>
                    @endif
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 text-sm">
                    <div class="font-semibold mb-1">Please fix the following:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>