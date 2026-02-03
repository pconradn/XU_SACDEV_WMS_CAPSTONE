        {{-- PROJECTS --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h2 class="text-base font-semibold text-slate-900">Projects</h2>
            <p class="text-sm text-slate-500 mt-1">Complete project list including objectives, beneficiaries, deliverables, and partners.</p>

            <div class="mt-4 overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-[1400px] w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                    <tr class="text-left">
                        <th class="px-3 py-2 w-44">Category</th>
                        <th class="px-3 py-2 w-40">Target Date</th>
                        <th class="px-3 py-2">Project / Initiative</th>
                        <th class="px-3 py-2 w-56">Implementing Body</th>
                        <th class="px-3 py-2 w-40">Budget</th>
                        <th class="px-3 py-2 w-64">Details</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                    @forelse($submission->projects as $p)
                        <tr class="align-top">
                            <td class="px-3 py-2 text-slate-700">{{ $p->category }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ optional($p->target_date)->format('Y-m-d') ?: '—' }}</td>
                            <td class="px-3 py-2 text-slate-900 font-medium">{{ $p->title ?: '—' }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ $p->implementing_body ?: '—' }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ number_format((float)$p->budget, 2) }}</td>

                            <td class="px-3 py-2">
                                <div class="space-y-2 text-xs text-slate-700">
                                    <div>
                                        <div class="font-semibold text-slate-800">Objectives</div>
                                        @if($p->objectives->count())
                                            <ul class="list-disc pl-5 mt-1 space-y-1">
                                                @foreach($p->objectives as $o)
                                                    <li>{{ $o->text ?? $o->objective ?? $o->name ?? '—' }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-slate-500 mt-1">—</div>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="font-semibold text-slate-800">Beneficiaries</div>
                                        @if($p->beneficiaries->count())
                                            <ul class="list-disc pl-5 mt-1 space-y-1">
                                                @foreach($p->beneficiaries as $b)
                                                    <li>{{ $b->text ?? $b->beneficiary ?? $b->name ?? '—' }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-slate-500 mt-1">—</div>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="font-semibold text-slate-800">Deliverables</div>
                                        @if($p->deliverables->count())
                                            <ul class="list-disc pl-5 mt-1 space-y-1">
                                                @foreach($p->deliverables as $d)
                                                    <li>{{ $d->text ?? $d->deliverable ?? $d->name ?? '—' }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-slate-500 mt-1">—</div>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="font-semibold text-slate-800">Partners</div>
                                        @if($p->partners->count())
                                            <ul class="list-disc pl-5 mt-1 space-y-1">
                                                @foreach($p->partners as $x)
                                                    <li>{{ $x->text ?? $x->partner ?? $x->name ?? '—' }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-slate-500 mt-1">—</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-slate-500">No projects found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>