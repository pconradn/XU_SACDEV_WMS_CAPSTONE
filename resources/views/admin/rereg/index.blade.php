<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">Re-Registration Review</h2>
            <div class="text-sm text-slate-600 mt-1">
                Select a target school year (SY), then choose an organization to open its hub.
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <div class="text-sm">{{ session('status') }}</div>
            </div>
        @endif

        {{-- SY selector --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-3">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Target School Year</div>
                    <div class="text-sm text-slate-600 mt-1">
                        Quick switch using Prev / Active / Next. Use “More…” for older school years.
                    </div>
                </div>

                {{-- Quick buttons --}}
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($schoolYears as $sy)
                        @php
                            $isSelected = $encodeSyId && (int)$encodeSyId === (int)$sy->id;
                            $count = (int)($syBadges[$sy->id] ?? 0);
                            $isActive = !empty($activeSy) && (int)$activeSy->id === (int)$sy->id;

                            $labelPrefix = $isActive ? 'Active' : ((int)$sy->id < (int)($activeSy->id ?? $sy->id) ? 'Prev' : 'Next');
                        @endphp

                        <form method="POST" action="{{ route('rereg.setSy') }}">
                            @csrf
                            <input type="hidden" name="encode_school_year_id" value="{{ $sy->id }}">

                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition
                                    {{ $isSelected
                                        ? 'bg-slate-900 text-white border-slate-900'
                                        : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'
                                    }}">
                                <span class="opacity-80">{{ $labelPrefix }}:</span>
                                <span>{{ $sy->name ?? ('SY #' . $sy->id) }}</span>

                                @if($count > 0)
                                    <span class="inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">
                                        {{ $count }}
                                    </span>
                                @endif
                            </button>
                        </form>
                    @endforeach

                    {{-- More button --}}
                    <button type="button"
                        onclick="document.getElementById('syModal').classList.remove('hidden')"
                        class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        More School Years…
                    </button>
                </div>

                {{-- Optional: show which SY is selected --}}
                <div class="text-xs text-slate-500">
                    Selected: <span class="font-semibold text-slate-800">
                        {{ $selectedSy?->name ?? ('SY #' . ($encodeSyId ?? '—')) }}
                    </span>
                </div>
            </div>

            {{-- Modal --}}
            <div id="syModal" class="hidden fixed inset-0 z-50">
                <div class="absolute inset-0 bg-black/40"
                    onclick="document.getElementById('syModal').classList.add('hidden')"></div>

                <div class="relative mx-auto mt-20 w-full max-w-xl px-4">
                    <div class="rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-200 flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-semibold text-slate-900">Select School Year</div>
                                <div class="mt-1 text-sm text-slate-600">
                                    Pick any school year to filter the re-registration review list.
                                </div>
                            </div>

                            <button type="button"
                                onclick="document.getElementById('syModal').classList.add('hidden')"
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Close
                            </button>
                        </div>

                        <div class="px-6 py-5">
                            <div class="max-h-[60vh] overflow-y-auto space-y-2">
                                @foreach($allSchoolYears as $sy)
                                    @php
                                        $isSelected = $encodeSyId && (int)$encodeSyId === (int)$sy->id;
                                        $count = (int)($syBadges[$sy->id] ?? 0);
                                        $isActive = !empty($activeSy) && (int)$activeSy->id === (int)$sy->id;
                                    @endphp

                                    <form method="POST" action="{{ route('rereg.setSy') }}">
                                        @csrf
                                        <input type="hidden" name="encode_school_year_id" value="{{ $sy->id }}">

                                        <button type="submit"
                                            class="w-full flex items-center gap-3 rounded-xl border px-4 py-3 text-left transition
                                                {{ $isSelected
                                                    ? 'border-slate-900 bg-slate-900 text-white'
                                                    : 'border-slate-200 bg-white text-slate-800 hover:bg-slate-50'
                                                }}">
                                            <div class="flex-1">
                                                <div class="text-sm font-semibold">
                                                    {{ $sy->name ?? ('SY #' . $sy->id) }}
                                                    @if($isActive)
                                                        <span class="ml-2 inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">
                                                            Active
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-xs opacity-80">
                                                    ID: {{ $sy->id }}
                                                </div>
                                            </div>

                                            @if($count > 0)
                                                <span class="inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">
                                                    {{ $count }}
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end">
                            <button type="button"
                                onclick="document.getElementById('syModal').classList.add('hidden')"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Done
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @if(!$encodeSyId)
            <div class="rounded-xl border border-slate-200 bg-white p-6 text-slate-700">
                Please select a target school year to continue.
            </div>
        @else
            {{-- Organization list --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <div class="text-sm font-semibold text-slate-900">Organizations</div>
                    <div class="text-sm text-slate-600 mt-1">
                        Open the hub to review B-1 to B-5 submissions under the selected SY.
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-5 py-3 font-semibold">Organization</th>
                                <th class="text-right px-5 py-3 font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($organizations as $org)
                                <tr>

                                    <td class="px-5 py-3 text-slate-800">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium">
                                                {{ $org->name ?? ('Org #' . $org->id) }}
                                            </span>

                                            @php
                                                $pending = (int)(($orgBadges[$org->id] ?? 0));
                                                $isReady = in_array((int)$org->id, $readyOrgIds ?? [], true);
                                                $isActivated = in_array((int)$org->id, $activatedOrgIds ?? [], true);
                                            @endphp

                                            @if($isActivated)
                                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                                                    Activated
                                                </span>
                                            @elseif($isReady)
                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                                    Ready for activation
                                                </span>
                                            @elseif($pending > 0)
                                                <span class="inline-flex items-center rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">
                                                    {{ $pending }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>





                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('rereg.hub', $org->id) }}"
                                           class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                            Open Hub
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @if($organizations->isEmpty())
                                <tr>
                                    <td colspan="2" class="px-5 py-6 text-center text-slate-600">
                                        No organizations found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
