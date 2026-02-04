<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">
                Moderator Re-Registration
            </h2>
            <div class="mt-1 text-sm text-slate-600">
                Review organization re-registration progress and complete the moderator submission.
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="text-sm">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="text-sm">{{ session('success') }}</div>
            </div>
        @endif

        {{-- Context Summary --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">
                Current Context
            </div>
            <div class="mt-1 text-sm text-slate-600">
                Organization ID: <span class="font-semibold text-slate-800">{{ $orgId }}</span>
                <span class="mx-1">•</span>
                Target School Year ID: <span class="font-semibold text-slate-800">{{ $syId }}</span>
            </div>
        </div>

        {{-- Forms Grid --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            {{-- B1 --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-base font-semibold text-slate-900">
                            B1 — Strategic Plan
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $b1->status ?? '—' }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">View only</span>
                </div>

                <div class="mt-4 text-sm text-slate-600">
                    @if($b1)
                        A strategic plan submission exists for this organization and school year.
                    @else
                        No strategic plan has been submitted yet.
                    @endif
                </div>
            </div>

            {{-- B2 --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-base font-semibold text-slate-900">
                            B2 — President Registration
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $b2->status ?? '—' }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">View only</span>
                </div>

                {{-- President info --}}
                <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Assigned President
                    </div>

                    @if(!empty($presidentMembership) && $presidentMembership->user)
                        <div class="mt-2 space-y-1 text-sm text-slate-700">
                            <div>
                                <span class="text-slate-500">Name:</span>
                                {{ $presidentMembership->user->name }}
                            </div>
                            <div>
                                <span class="text-slate-500">Email:</span>
                                {{ $presidentMembership->user->email }}
                            </div>
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
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-base font-semibold text-slate-900">
                            B3 — Officers Submission
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $b3->status ?? '—' }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">View only</span>
                </div>

                <div class="mt-4 text-sm text-slate-600">
                    @if($b3)
                        An officers list submission exists for this organization.
                    @else
                        No officers submission yet for this school year.
                    @endif
                </div>
            </div>

            {{-- B5 --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-base font-semibold text-slate-900">
                            B5 — Moderator Submission
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $b5->status ?? 'draft' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <a href="{{ route('org.moderator.rereg.b5.edit') }}"
                       class="inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Open B5 Form
                    </a>


                </div>
            </div>

        </div>
    </div>
</x-app-layout>
