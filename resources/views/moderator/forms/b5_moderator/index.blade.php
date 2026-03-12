<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">B-5 Moderator Form</h2>
            <p class="mt-1 text-sm text-slate-600">
                Select which organization and target School Year you are moderating, then complete the B-5 form.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="font-semibold">Success</div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Error</div>
                <div class="text-sm mt-1">{{ session('error') }}</div>
            </div>
        @endif

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <div class="text-sm font-semibold text-slate-900">Your Assignments</div>
                <div class="text-xs text-slate-500 mt-1">Click “Open” to start encoding.</div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Organization</th>
                            <th class="py-3 px-4">Target SY</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($terms as $t)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $t->organization->name ?? ('Org #' . $t->organization_id) }}
                                    </div>
                                </td>

                                <td class="py-3 px-4 text-slate-700">
                                    {{ $t->schoolYear->label ?? $t->school_year_id }}
                                </td>

                                <td class="py-3 px-4">
                                    @php
                                        $badge = 'bg-slate-100 text-slate-700';
                                        if ($t->status === 'active') $badge = 'bg-emerald-100 text-emerald-800';
                                        if ($t->status === 'pending') $badge = 'bg-amber-100 text-amber-800';
                                        if (in_array($t->status, ['ended','replaced'], true)) $badge = 'bg-red-100 text-red-800';
                                    @endphp

                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $badge }}">
                                        {{ $t->status }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('moderator.b5.moderator.index', ['term_id' => $t->id]) }}"
                                       class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-6 px-4 text-slate-600" colspan="4">
                                    No moderator assignments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
