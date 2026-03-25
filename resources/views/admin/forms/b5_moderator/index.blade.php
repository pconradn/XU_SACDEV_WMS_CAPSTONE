<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">B-5 Moderator Submissions</h2>
            <p class="mt-1 text-sm text-slate-600">
                Review moderator submissions per organization and target School Year.
            </p>
        </div>


        <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.moderator_submissions.index') }}"
                  class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:items-end">

                <div>
                    <label class="block text-sm font-medium text-slate-700">Target School Year</label>
                    <select name="target_school_year_id"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="0">All</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}" @selected((int)$sy->id === (int)$targetSyId)>
                                {{ $sy->label ?? $sy->name ?? ('SY #' . $sy->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="">All</option>
                        @foreach(['draft','submitted_to_sacdev','returned_by_sacdev','approved_by_sacdev'] as $st)
                            <option value="{{ $st }}" @selected($st === $status)>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Filter
                    </button>
                    <a href="{{ route('admin.moderator_submissions.index') }}"
                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Organization</th>
                            <th class="py-3 px-4">Target SY</th>
                            <th class="py-3 px-4">Moderator</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($submissions as $s)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $s->organization->name ?? ('Org #' . $s->organization_id) }}
                                    </div>
                                </td>

                                <td class="py-3 px-4 text-slate-700">
                                    {{ $s->targetSchoolYear->label ?? $s->target_school_year_id }}
                                </td>

                                <td class="py-3 px-4 text-slate-700">
                                    {{ $s->moderatorUser->name ?? $s->full_name ?? ('User #' . $s->moderator_user_id) }}
                                    @if($s->email)
                                        <div class="text-xs text-slate-500">{{ $s->email }}</div>
                                    @endif
                                </td>

                                <td class="py-3 px-4">
                                    @include('admin.forms.b5_moderator.partials._status_badge', ['status' => $s->status])
                                </td>

                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.moderator_submissions.show', $s) }}"
                                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-6 px-4 text-slate-600" colspan="5">
                                    No submissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-slate-200">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
