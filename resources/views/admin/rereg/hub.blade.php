<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">
                    Re-Registration Submissions
                </h2>
                <div class="text-sm text-slate-600 mt-1">
                    Review B-1 to B-5 submissions for the selected organization and target school year.
                </div>

                <div class="mt-2 text-sm text-slate-700">
                    <span class="text-slate-500">Organization:</span>
                    <span class="font-semibold">{{ $organization->name ?? ('Org #' . $organization->id) }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2">


                <a href="{{ route('admin.home') }}"
                   class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                    Back to Dashboard
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="text-sm">{{ session('status') }}</div>
            </div>
        @endif

        {{-- Target SY selector (admin) --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="POST" action="{{ route('rereg.setSy') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                @csrf

                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700">
                        Target School Year
                    </label>
                    <select name="encode_school_year_id"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none"
                            required>
                        <option value="" disabled @selected(!$encodeSyId)>Select a school year...</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}" @selected($encodeSyId && (int)$sy->id === (int)$encodeSyId)>
                                {{ $sy->name ?? $sy->label ?? ('SY #' . $sy->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Set SY
                </button>
            </form>
        </div>

        @if(!$encodeSyId)
            <div class="rounded-xl border border-slate-200 bg-white p-6 text-slate-700">
                Please select a target school year to view submissions.
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach($forms as $key => $f)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-base font-semibold text-slate-900">
                                    {{ $f['label'] }}
                                </div>

                                <div class="mt-1">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $f['badge']['class'] }}">
                                        {{ $f['badge']['text'] }}
                                    </span>
                                </div>

                                @if(!empty($f['meta']))
                                    <div class="mt-2 space-y-0.5 text-xs text-slate-500">
                                        @if(!empty($f['meta']['submitted_at']))
                                            <div>Submitted: {{ $f['meta']['submitted_at'] }}</div>
                                        @endif
                                        @if(!empty($f['meta']['reviewed_at']))
                                            <div>Reviewed: {{ $f['meta']['reviewed_at'] }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            @if(!empty($f['viewRoute']) && Route::has($f['viewRoute']))
                                <a href="{{ route($f['viewRoute'], $f['routeParams'] ?? []) }}"
                                   class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                                    View
                                </a>
                            @else
                                <span class="text-xs text-slate-500">No record</span>
                            @endif
                        </div>

                        @if(!empty($f['remarksPreview']))
                            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-xs font-semibold text-slate-700">Latest remarks</div>
                                <div class="mt-1 text-sm text-slate-700">
                                    {{ $f['remarksPreview'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Re-registration readiness</div>
                        <div class="text-sm text-slate-600 mt-1">
                            All required forms must be approved by SACDEV to be considered complete.
                        </div>
                    </div>

                    @if(!empty($allApproved) && $allApproved)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-sm font-semibold text-emerald-800">
                            Complete
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-700">
                            In progress
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
