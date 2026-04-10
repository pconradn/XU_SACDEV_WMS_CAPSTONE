<div x-show="openApprove"
     x-cloak
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         @click="openApprove = false"></div>

    <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">

        <div class="border-b border-slate-200 px-5 py-4 bg-gradient-to-b from-slate-50 to-white flex justify-between items-start">

            <div class="space-y-1">
                <div class="text-base font-semibold text-slate-900">
                    Approve Officer Submission
                </div>

                <p class="text-[11px] text-slate-500">
                    Review changes before applying to the organization.
                </p>
            </div>

            <button @click="openApprove = false"
                    class="text-slate-400 hover:text-slate-600 text-base leading-none">
                ✕
            </button>

        </div>

        <div class="p-5 space-y-4 max-h-[420px] overflow-y-auto text-[11px]">

            <div class="flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-700">
                <i data-lucide="check-circle" class="w-4 h-4 mt-0.5"></i>
                <span>
                    This will update officer records and provision required system roles.
                </span>
            </div>

            <div class="font-semibold text-slate-800 text-xs">
                The following actions will occur:
            </div>

            <ul class="space-y-2">

                @foreach($submission->items as $item)

                    @php
                        $conflicts = $conflictsByItemId[$item->id] ?? [];
                        $exists = in_array($item->student_id_number, $existingMap ?? []);
                    @endphp

                    <li class="rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm">

                        <div class="flex flex-col gap-1">

                            <div class="flex flex-wrap items-center gap-1 text-xs">

                                <span class="font-semibold text-slate-900">
                                    {{ $item->officer_name }}
                                </span>

                                <span class="text-slate-400">—</span>

                                @if($exists)
                                    <span class="text-blue-700 font-medium">
                                        Update existing
                                    </span>
                                @else
                                    <span class="text-emerald-700 font-medium">
                                        Create new
                                    </span>
                                @endif

                            </div>

                            <div class="flex flex-wrap gap-2 text-[10px]">

                                @if($item->isTreasurer())
                                    <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Treasurer Account
                                    </span>
                                @endif

                                @if($item->isFinance_Officer())
                                    <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Finance Officer Account
                                    </span>
                                @endif

                                @if(!empty($conflicts))
                                    <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                        Conflict Detected
                                    </span>
                                @endif

                            </div>

                        </div>

                    </li>

                @endforeach

            </ul>

        </div>

        <div class="border-t border-slate-200 px-5 py-4 flex justify-end gap-2 bg-white">

            <button @click="openApprove = false"
                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-700 hover:bg-slate-50 transition">
                Cancel
            </button>

            <button type="button"
                    onclick="submitApproval()"
                    class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition shadow-sm">
                Confirm Approval
            </button>

        </div>

    </div>

</div>