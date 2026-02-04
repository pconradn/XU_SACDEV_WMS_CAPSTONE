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
                                        {{ $org->name ?? ('Org #' . $org->id) }}
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
