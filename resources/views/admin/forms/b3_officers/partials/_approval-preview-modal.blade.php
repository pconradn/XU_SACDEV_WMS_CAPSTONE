<div x-show="openApprove"
     x-cloak
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50"
         @click="openApprove = false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-xl border border-slate-200">

        {{-- HEADER --}}
        <div class="border-b border-slate-200 px-5 py-4 flex justify-between items-start">

            <div>
                <div class="text-lg font-semibold text-slate-900">
                    Approve Officer Submission
                </div>

                <p class="text-sm text-slate-500 mt-1">
                    Please review the changes that will be applied to the organization.
                </p>
            </div>

            <button @click="openApprove = false"
                    class="text-slate-400 hover:text-slate-600 text-lg leading-none">
                ✕
            </button>

        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-5 max-h-[420px] overflow-y-auto text-sm">

            {{-- CONTEXT --}}
            <div class="flex items-start gap-3">

                <div class="mt-1 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <p class="text-slate-600">
                    Approving this will update officer records and provision required system roles.
                </p>

            </div>


            {{-- LIST TITLE --}}
            <div class="font-semibold text-slate-800">
                The following actions will occur:
            </div>

            {{-- ACTION LIST --}}
            <ul class="list-disc pl-5 space-y-2 text-slate-700">

                @foreach($submission->items as $item)

                    @php
                        $conflicts = $conflictsByItemId[$item->id] ?? [];
                        $exists = in_array($item->student_id_number, $existingMap ?? []);
                    @endphp

                    <li>
                        <span class="font-semibold text-slate-900">
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
                                • Provision Budget and Finance Officer account
                            </span>
                        @endif

                        @if(!empty($conflicts))
                            <div class="text-amber-700 mt-1 text-xs">
                                ⚠ Conflict detected
                            </div>
                        @endif
                    </li>

                @endforeach

            </ul>

        </div>

        {{-- FOOTER --}}
        <div class="border-t border-slate-200 px-5 py-4 flex justify-end gap-2">

            <button @click="openApprove = false"
                    class="px-4 py-2 rounded-lg border border-slate-300 text-sm text-slate-700 hover:bg-slate-50">
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