<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Organization Officers
            </h2>
            <p class="text-xs text-slate-500">
                Academic performance and eligibility tracking
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- STATUS MESSAGE --}}
            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- TABLE CARD --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                {{-- HEADER --}}
                <div class="px-5 py-4 border-b bg-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">
                            Officer Directory
                        </h3>
                        <p class="text-xs text-slate-500">
                            Displays academic eligibility and officer standing
                        </p>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto max-h-[450px] overflow-y-auto pr-2">

                    <table id="officersTable" class="min-w-full text-sm">

                        {{-- STICKY HEADER --}}
                        <thead class="sticky top-0 bg-white z-10 border-b text-slate-600">
                            <tr>
                                <th class="py-3 px-4">Name</th>
                                <th class="py-3 px-4">Position</th>
                                <th class="py-3 px-4">Previous QPI</th>
                                <th class="py-3 px-4">Current QPI</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">

                        @forelse ($officers as $o)

                            @php
                                $membership = $o->membership ?? null;

                                $rowClass = '';

                                if ($membership) {
                                    if ($membership->is_suspended) {
                                        $rowClass = 'bg-red-50 hover:bg-red-100';
                                    } elseif ($membership->is_under_probation) {
                                        $rowClass = 'bg-amber-50 hover:bg-amber-100';
                                    }
                                }
                            @endphp

                            <tr class="{{ $rowClass }} transition">

                                {{-- NAME --}}
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-900">
                                        {{ $o->full_name }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $o->email }}
                                    </div>
                                </td>

                                {{-- POSITION --}}
                                <td class="py-3 px-4 text-slate-700">
                                    {{ $o->position ?? '-' }}
                                </td>

                                {{-- PREVIOUS QPI --}}
                                <td class="py-3 px-4">
                                    <div class="space-y-1 text-xs text-slate-700">

                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Prev 1st</span>
                                            <span class="font-medium">{{ $o->prev_first_sem_qpi ?? '-' }}</span>
                                        </div>

                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Prev 2nd</span>
                                            <span class="font-medium">{{ $o->prev_second_sem_qpi ?? '-' }}</span>
                                        </div>

                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Inter</span>
                                            <span class="font-medium">{{ $o->prev_intersession_qpi ?? '-' }}</span>
                                        </div>

                                    </div>
                                </td>

                                {{-- CURRENT QPI --}}
                                <td class="py-3 px-4">

                                    @if($myRole === 'president')

                                        <form method="POST"
                                              action="{{ route('org.officers.update-qpi', $o) }}"
                                              class="space-y-2">

                                            @csrf
                                            @method('PUT')

                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] text-slate-500 w-20">
                                                    Current 1st
                                                </span>
                                                <input type="number"
                                                    step="0.01"
                                                    min="0"
                                                    max="4"
                                                    name="current_first_sem_qpi"
                                                    value="{{ $o->current_first_sem_qpi }}"
                                                    class="w-24 text-xs border border-slate-200 rounded-lg px-2 py-1 focus:ring-1 focus:ring-blue-500">
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] text-slate-500 w-20">
                                                    Current 2nd
                                                </span>
                                                <input type="number"
                                                    step="0.01"
                                                    min="0"
                                                    max="4"
                                                    name="current_second_sem_qpi"
                                                    value="{{ $o->current_second_sem_qpi }}"
                                                    class="w-24 text-xs border border-slate-200 rounded-lg px-2 py-1 focus:ring-1 focus:ring-blue-500">
                                            </div>

                                            <button class="text-xs px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                Save
                                            </button>

                                        </form>

                                    @else

                                        <div class="space-y-1 text-xs text-slate-700">
                                            <div class="flex justify-between">
                                                <span class="text-slate-500">Current 1st</span>
                                                <span class="font-medium">{{ $o->current_first_sem_qpi ?? '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-500">Current 2nd</span>
                                                <span class="font-medium">{{ $o->current_second_sem_qpi ?? '-' }}</span>
                                            </div>
                                        </div>

                                    @endif

                                </td>

                                {{-- STATUS --}}
                                <td class="py-3 px-4">

                                    @if($membership)

                                        @if($membership->is_suspended)
                                            <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">
                                                Suspended
                                            </span>

                                        @elseif($membership->is_under_probation)
                                            <span class="text-xs px-2 py-1 bg-amber-100 text-amber-700 rounded-full">
                                                Under Probation
                                            </span>

                                        @else
                                            <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full">
                                                Good Standing
                                            </span>
                                        @endif

                                    @else
                                        <span class="text-xs text-slate-400">N/A</span>
                                    @endif

                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">
                                    No officers found.
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>