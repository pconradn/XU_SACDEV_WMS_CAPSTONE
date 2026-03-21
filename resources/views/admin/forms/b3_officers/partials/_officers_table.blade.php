<div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-200">
        <div class="text-sm font-semibold text-slate-800">
            Officers List 
        </div>

        <div class="text-xs text-slate-500 mt-1">
            Academic performance shown is from previous school year.
        </div>
    </div>


    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50">
                <tr class="text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">

                    <th class="px-4 py-3">Name</th>

                    <th class="px-4 py-3">Student ID</th>

                    <th class="px-4 py-3">Position</th>

                    <th class="px-4 py-3">Prev 1st Sem</th>

                    <th class="px-4 py-3">Prev 2nd Sem</th>

                    <th class="px-4 py-3">Prev Inter</th>

                    <th class="px-4 py-3 text-center">Info</th>

                </tr>
            </thead>


            <tbody class="divide-y divide-slate-200">

                @foreach($items as $item)

                    @php
                        $conflicts = $conflictsByItemId[$item->id] ?? [];
                        $hasConflict = count($conflicts) > 0;
                        $modalId = "conflictModal_" . $item->id;
                    @endphp


                    <tr class="{{ $hasConflict ? 'bg-amber-50' : '' }}">

                        <td class="px-4 py-3 font-semibold text-slate-900">
                            {{ $item->officer_name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->student_id_number }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->position }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->first_sem_qpi ?? '—' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->second_sem_qpi ?? '—' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->intersession_qpi ?? '—' }}
                        </td>


                        <td class="px-4 py-3 text-center">

                            @if($hasConflict)

                                <button
                                    onclick="openModal('{{ $modalId }}')"
                                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500 text-white font-bold hover:bg-amber-600"
                                >
                                    !
                                </button>

                            @else
                                <span class="text-slate-300">—</span>
                            @endif

                        </td>

                    </tr>


                    {{-- Conflict Modal --}}
                    @if($hasConflict)

                        <div id="{{ $modalId }}"
                             class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

                                <div class="flex justify-between items-center mb-3">
                                    <div class="font-semibold text-lg text-amber-700">
                                        Major Officer Conflict
                                    </div>

                                    <button onclick="closeModal('{{ $modalId }}')"
                                            class="text-slate-400 hover:text-slate-600 text-xl">
                                        ×
                                    </button>
                                </div>


                                <div class="text-sm text-slate-700 mb-3">

                                    {{ $item->officer_name }}
                                    is already assigned as major officer in:

                                </div>


                                <div class="space-y-2">

                                    @foreach($conflicts as $conflict)

                                        <div class="border rounded-lg p-3 bg-amber-50">

                                            <div class="font-semibold">
                                                {{ ucfirst(str_replace('_',' ',$conflict['role'])) }}
                                            </div>

                                            <div class="text-xs text-slate-600">
                                                {{ $conflict['organization_name'] }}
                                            </div>

                                        </div>

                                    @endforeach

                                </div>


                                <div class="mt-5 text-right">
                                    <button onclick="closeModal('{{ $modalId }}')"
                                            class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900">
                                        Close
                                    </button>
                                </div>

                            </div>

                        </div>

                    @endif

                @endforeach

            </tbody>

        </table>

    </div>

</div>




{{-- Modal Script --}}
<script>

function openModal(id)
{
    const modal = document.getElementById(id);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(id)
{
    const modal = document.getElementById(id);

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

</script>