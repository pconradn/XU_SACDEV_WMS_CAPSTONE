<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-slate-900">Review Strategic Plan</h1>
                    <div class="text-sm text-slate-700 mt-1">
                        Target SY: <span class="font-semibold">{{ $submission->targetSchoolYear?->name }}</span>
                    </div>
                    <div class="text-sm text-slate-700">
                        Org: <span class="font-semibold">{{ $submission->org_name }}</span>
                        <span class="text-slate-500">{{ $submission->org_acronym ? "({$submission->org_acronym})" : '' }}</span>
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
                </div>
            </div>

            @if(session('success'))
                <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

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

        {{-- Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
                <p class="text-sm text-slate-600">Total Overall</p>
                <p class="text-xl font-semibold text-slate-900 mt-1">{{ number_format((float)$submission->total_overall, 2) }}</p>
            </div>
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
                <p class="text-sm text-slate-600">Submitted</p>
                <p class="text-sm font-semibold text-slate-900 mt-1">
                    {{ optional($submission->submitted_to_moderator_at)->format('M d, Y h:i A') ?? '—' }}
                </p>
            </div>
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
                <p class="text-sm text-slate-600">Last Moderator Review</p>
                <p class="text-sm font-semibold text-slate-900 mt-1">
                    {{ optional($submission->moderator_reviewed_at)->format('M d, Y h:i A') ?? '—' }}
                </p>
            </div>
        </div>

        {{-- Projects (read-only) --}}
        <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
            <h2 class="text-base font-semibold text-slate-900">Projects</h2>
            <p class="text-sm text-slate-500 mt-1">Read-only view for moderator review.</p>

            <div class="mt-4 overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-[1200px] w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                    <tr class="text-left">
                        <th class="px-3 py-2 w-40">Category</th>
                        <th class="px-3 py-2 w-40">Target Date</th>
                        <th class="px-3 py-2">Project / Initiative</th>
                        <th class="px-3 py-2 w-56">Implementing Body</th>
                        <th class="px-3 py-2 w-40">Budget</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($submission->projects as $p)
                        <tr class="align-top">
                            <td class="px-3 py-2 text-slate-700">{{ $p->category }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ optional($p->target_date)->format('Y-m-d') }}</td>
                            <td class="px-3 py-2 text-slate-900 font-medium">
                                {{ $p->title }}
                                <div class="text-xs text-slate-500 mt-1">
                                    Objectives: {{ $p->objectives->count() }},
                                    Beneficiaries: {{ $p->beneficiaries->count() }},
                                    Deliverables: {{ $p->deliverables->count() }},
                                    Partners: {{ $p->partners->count() }}
                                </div>
                            </td>
                            <td class="px-3 py-2 text-slate-700">{{ $p->implementing_body ?? '—' }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ number_format((float)$p->budget, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-slate-500">No projects.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Moderator Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Return --}}
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
                <h2 class="text-base font-semibold text-slate-900">Return to Organization</h2>
                <p class="text-sm text-slate-500 mt-1">Provide required remarks for revision.</p>

                <form method="POST" action="{{ route('org.moderator.strategic_plans.return', $submission) }}" class="mt-4 space-y-3">
                    @csrf
                    <textarea name="moderator_remarks" rows="4"
                              class="w-full rounded-lg border-slate-200 focus:border-rose-500 focus:ring-rose-500"
                              placeholder="State what needs to be fixed...">{{ old('moderator_remarks', $submission->moderator_remarks) }}</textarea>

                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        Return with Remarks
                    </button>
                </form>
            </div>

            {{-- Forward --}}
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
                <h2 class="text-base font-semibold text-slate-900">Noted by Moderator → Forward to SACDEV</h2>
                <p class="text-sm text-slate-500 mt-1">Optional note, then forward for SACDEV review.</p>

                <form method="POST" action="{{ route('org.moderator.strategic_plans.forward', $submission) }}" class="mt-4 space-y-3">
                    @csrf
                    <textarea name="moderator_note" rows="4"
                              class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="(optional) Notes...">{{ old('moderator_note') }}</textarea>

                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Noted & Forward to SACDEV
                    </button>
                </form>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('org.moderator.strategic_plans.index') }}"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Back
            </a>
        </div>
    </div>
</x-app-layout>
