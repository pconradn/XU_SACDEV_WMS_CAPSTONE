<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
            <h1 class="text-xl font-semibold text-slate-900">Moderator Review: Strategic Plans</h1>
            <p class="text-sm text-slate-600 mt-1">
                Review submissions for the organization you are currently viewing. You may return with remarks or “Noted by” and forward to SACDEV.
            </p>

            @if(session('success'))
                <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                    <tr class="text-left">
                        <th class="px-4 py-3">Target SY</th>
                        <th class="px-4 py-3">Organization Name</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                    @forelse($submissions as $s)
                        <tr class="align-top">
                            <td class="px-4 py-3 text-slate-800">
                                {{ $s->targetSchoolYear?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-slate-900 font-medium">
                                {{ $s->org_name ?: '—' }}
                                <div class="text-xs text-slate-500 mt-1">{{ $s->org_acronym ?: '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2.5 py-1 rounded-full border
                                    @if($s->status === 'approved_by_sacdev')
                                        bg-emerald-50 border-emerald-200 text-emerald-700
                                    @elseif(str_contains($s->status, 'returned'))
                                        bg-rose-50 border-rose-200 text-rose-700
                                    @elseif(in_array($s->status, ['submitted_to_moderator','forwarded_to_sacdev']))
                                        bg-amber-50 border-amber-200 text-amber-700
                                    @else
                                        bg-slate-50 border-slate-200 text-slate-700
                                    @endif
                                ">
                                    {{ $s->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">
                                {{ optional($s->submitted_to_moderator_at)->format('M d, Y h:i A') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('org.moderator.strategic_plans.show', $s) }}"
                                   class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-slate-500">
                                No submissions to review yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
