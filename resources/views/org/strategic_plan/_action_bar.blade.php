<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

    {{-- TOP ROW --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        {{-- STATUS --}}
        <div class="flex items-center gap-2 text-xs font-semibold">

            @php
                $statusColors = [
                    'draft' => 'bg-slate-100 text-slate-700',
                    'submitted_to_moderator' => 'bg-blue-100 text-blue-700',
                    'returned_by_moderator' => 'bg-amber-100 text-amber-700',
                    'approved_by_moderator' => 'bg-emerald-100 text-emerald-700',
                    'returned_by_sacdev' => 'bg-rose-100 text-rose-700',
                    'approved_by_sacdev' => 'bg-green-100 text-green-700',
                ];
            @endphp

            <span class="px-2.5 py-1 rounded-lg {{ $statusColors[$submission->status] ?? 'bg-slate-100 text-slate-700' }}">
                {{ str_replace('_', ' ', $submission->status) }}
            </span>

        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-wrap items-center gap-2">

            {{-- ================= PRESIDENT ================= --}}
            @if($mode === 'org')

                @if($canSubmitToModerator)
                    <form method="POST" action="{{ route('org.rereg.b1.submit') }}">
                        @csrf
                        <input type="hidden" name="confirm" value="yes">
                        <button
                            class="inline-flex items-center rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition">
                            Submit to Moderator
                        </button>
                    </form>
                @endif

            @endif


            {{-- ================= MODERATOR ================= --}}
            @if($mode === 'moderator')

                @if($canReviewAsModerator)

                    <button
                        @click="openRemarks = true"
                        class="inline-flex items-center rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-100 transition">
                        Return with Remarks
                    </button>

                    <form method="POST" action="{{ route('org.moderator.strategic_plans.approve', $submission->id) }}">
                        @csrf
                        <button
                            class="inline-flex items-center rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition">
                            Approve
                        </button>
                    </form>

                @endif

                @if($canSubmitToSacdev)
                    <form method="POST" action="{{ route('org.moderator.strategic_plans.submitToSacdev', $submission->id) }}">
                        @csrf
                        <button
                            class="inline-flex items-center rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700 transition">
                            Submit to SACDEV
                        </button>
                    </form>
                @endif

            @endif


            {{-- ================= ADMIN ================= --}}
            @if($mode === 'admin')

                <button
                    @click="openRemarks = true"
                    class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition">
                    Return
                </button>

                <form method="POST" action="{{ route('admin.strategic_plans.approve', $submission->id) }}">
                    @csrf
                    <button
                        class="inline-flex items-center rounded-xl bg-green-600 px-3 py-2 text-xs font-semibold text-white hover:bg-green-700 transition">
                        Approve
                    </button>
                </form>

            @endif

        </div>
    </div>


    {{-- REMARKS MODAL --}}
    <div
        x-show="openRemarks"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

        <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-xl">

            <div class="text-sm font-semibold text-slate-900 mb-3">
                Add Remarks
            </div>

            <form method="POST"
                  :action="mode === 'admin'
                        ? '{{ route('admin.strategic_plans.return', $submission->id) }}'
                        : '{{ route('org.moderator.strategic_plans.return', $submission->id) }}'">

                @csrf

                <textarea name="remarks"
                          rows="4"
                          required
                          class="w-full rounded-xl border border-slate-200 text-xs p-2 focus:ring-1 focus:ring-blue-500"></textarea>

                <div class="mt-4 flex justify-end gap-2">

                    <button type="button"
                            @click="openRemarks = false"
                            class="px-3 py-2 text-xs font-semibold text-slate-600">
                        Cancel
                    </button>

                    <button
                        class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                        Submit
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>