<div x-show="openApprove"
     x-cloak
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-black/40"
         @click="openApprove = false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-2xl rounded-xl bg-white shadow-xl">

        {{-- HEADER --}}
        <div class="border-b px-5 py-4 flex justify-between items-center">
            <div class="font-semibold text-slate-900">
                Approval Preview
            </div>

            <button @click="openApprove = false"
                    class="text-slate-400 hover:text-slate-600 text-lg">
                ✕
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-4 max-h-[400px] overflow-y-auto text-sm">

            <div class="font-semibold text-slate-800">
                The following actions will occur:
            </div>

            <ul class="list-disc pl-5 space-y-1 text-slate-700">

                @foreach($submission->items as $item)

                    @php
                        $conflicts = $conflictsByItemId[$item->id] ?? [];
                        $exists = in_array($item->student_id_number, $existingMap ?? []);
                    @endphp

                    <li>
                        <span class="font-semibold">
                            {{ $item->officer_name }}
                        </span>

                        —

                        @if($exists)
                            <span class="text-blue-700">
                                UPDATE existing officer entry
                            </span>
                        @else
                            <span class="text-emerald-700">
                                CREATE new officer entry
                            </span>
                        @endif

                        @if($item->isTreasurer())
                            <span class="text-indigo-700">
                                • Provision Treasurer account
                            </span>
                        @endif

                        @if($item->isFinance_Officer())
                            <span class="text-indigo-700">
                                • Provision finance_officer account
                            </span>
                        @endif

                        @if(!empty($conflicts))
                            <div class="text-amber-700 mt-1">
                                ⚠ Conflict detected
                            </div>
                        @endif
                    </li>

                @endforeach

            </ul>

        </div>

        {{-- FOOTER --}}
        <div class="border-t px-5 py-4 flex justify-end gap-2">

            <button @click="openApprove = false"
                    class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-semibold">
                Cancel
            </button>

            <button type="button"
                    onclick="submitApproval()"
                    class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                Confirm Approval
            </button>

        </div>

    </div>

</div>