<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">
                        Moderator Re-Registration
                    </h2>
                    <div class="mt-1 text-sm text-slate-600">
                        Review the organization’s submissions and complete the moderator form (B5).
                    </div>
                </div>


            </div>
        </div>

        {{-- Alerts (show one only) --}}
        @if(session('error'))
            <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-900">
                <div class="text-sm font-semibold">Action blocked</div>
                <div class="mt-1 text-sm">{{ session('error') }}</div>
            </div>
        @elseif(session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="text-sm font-semibold">Success</div>
                <div class="mt-1 text-sm">{{ session('success') }}</div>
            </div>
        @endif

        {{-- Activated banner --}}
        @if(!empty($isActivated) && $isActivated)
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-emerald-900">
                            Organization already registered for this School Year
                        </div>
                        <div class="mt-1 text-sm text-emerald-800/90">
                            Re-registration is already complete, the forms below should be treated as read-only.
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                        Registered
                    </span>
                </div>
            </div>
        @endif

        @php
         
            $badge = function ($status) {
                $s = strtolower((string) $status);

                if ($s === '' || $s === '—') return ['text' => 'No submission', 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];

                
                if (str_contains($s, 'approved')) return ['text' => $status, 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'];
                if (str_contains($s, 'returned'))  return ['text' => $status, 'class' => 'bg-rose-50 text-rose-700 border-rose-200'];
                if (str_contains($s, 'submitted')) return ['text' => $status, 'class' => 'bg-amber-50 text-amber-800 border-amber-200'];
                if (str_contains($s, 'forwarded')) return ['text' => $status, 'class' => 'bg-blue-50 text-blue-700 border-blue-200'];
                if ($s === 'draft')                return ['text' => 'draft',  'class' => 'bg-slate-50 text-slate-700 border-slate-200'];

                return ['text' => $status, 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];
            };

            $b1Status = $badge($b1->status ?? '—');
            $b2Status = $badge($b2->status ?? '—');
            $b3Status = $badge($b3->status ?? '—');
            $b5Status = $badge($b5->status ?? 'draft');
        @endphp

        {{-- Forms Grid --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- B1 --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-base font-semibold text-slate-900">B1 — Strategic Plan</div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $b1Status['class'] }}">
                                {{ $b1Status['text'] }}
                            </span>
                        </div>
                    </div>

                    @if($b1)
                        <span class="text-xs text-slate-500">View only</span>
                    @else
                        <span class="text-xs text-slate-500">Waiting on org</span>
                    @endif
                </div>

                <div class="mt-4 text-sm text-slate-600">
                    @if($b1)
                        The organization submitted a Strategic Plan for this school year. Review it and either return with remarks or forward to SACDEV.
                    @else
                        No Strategic Plan submission yet.
                    @endif
                </div>

                <div class="mt-4">
                    @if($b1)
                        <a href="{{ route('org.moderator.strategic_plans.show', $b1) }}"
                           class="inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                            Open B1 Review
                        </a>
                    @else
                        <button type="button" disabled
                                class="inline-flex w-full items-center justify-center rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">
                            No B1 Submission Yet
                        </button>
                    @endif
                </div>
            </div>

            {{-- B2 --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-base font-semibold text-slate-900">B2 — President Registration</div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $b2Status['class'] }}">
                                {{ $b2Status['text'] }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">View only</span>
                </div>

                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-sm font-semibold text-slate-900">Assigned President</div>

                    @if(!empty($presidentMembership) && $presidentMembership->user)
                        <div class="mt-2 space-y-1 text-sm text-slate-700">
                            <div><span class="text-slate-500">Name:</span> {{ $presidentMembership->user->name }}</div>
                            <div><span class="text-slate-500">Email:</span> {{ $presidentMembership->user->email }}</div>
                        </div>
                    @else
                        <div class="mt-2 text-sm text-slate-600">
                            No president assigned for this school year.
                        </div>
                    @endif
                </div>

      
            </div>

            {{-- B3 --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-base font-semibold text-slate-900">B3 — Officers Submission</div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $b3Status['class'] }}">
                                {{ $b3Status['text'] }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">View only</span>
                </div>

                <div class="mt-4 text-sm text-slate-600">
                    @if($b3)
                        Officers list is submitted.
                    @else
                        No officers submission yet.
                    @endif
                </div>
            </div>


            {{-- B6 — Organization Constitution --}}
            @php
                $b6Status = $badge($constitutionSubmission->status ?? '—');
            @endphp

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <div class="text-base font-semibold text-slate-900">
                            B6 — Organization Constitution
                        </div>

                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $b6Status['class'] }}">
                                {{ $b6Status['text'] }}
                            </span>
                        </div>
                    </div>

                    @if($constitutionSubmission)
                        <span class="text-xs text-slate-500">View only</span>
                    @else
                        <span class="text-xs text-slate-500">Waiting on org</span>
                    @endif

                </div>


                <div class="mt-4 text-sm text-slate-600">

                    @if($constitutionSubmission)

                        Organization Constitution has been uploaded.

                    @else

                        No constitution submission yet.

                    @endif

                </div>


                <div class="mt-4">

                    @if($constitutionSubmission)

                        <a href="{{ route('org.moderator.constitution.download', $constitutionSubmission) }}"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">

                            Download Constitution

                        </a>

                        <div class="mt-2 text-xs text-slate-500 truncate">
                            {{ $constitutionSubmission->original_filename }}
                        </div>

                    @else

                        <button disabled
                            class="inline-flex w-full items-center justify-center rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">

                            No Constitution Uploaded Yet

                        </button>

                    @endif

                </div>

            </div>



            {{-- B5 --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-base font-semibold text-slate-900">B5 — Moderator Submission</div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $b5Status['class'] }}">
                                {{ $b5Status['text'] }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($isActivated) && $isActivated)
                        <span class="text-xs text-slate-500">Locked</span>
                    @else
                        <span class="text-xs text-slate-500">Action required</span>
                    @endif
                </div>

                <div class="mt-4 text-sm text-slate-600">
                    Fill out B5 once you are done reviewing the organization’s submissions. This is submitted to SACDEV as part of activation requirements.
                </div>

                <div class="mt-4 space-y-2">

                        <a href="{{ route('org.moderator.rereg.b5.edit') }}"
                           class="inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                            Open B5 Form
                        </a>
                   
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
