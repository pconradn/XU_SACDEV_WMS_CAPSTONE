<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 shadow-sm overflow-hidden transition hover:shadow-md">

    @php
        $badge = $form['badge'] ?? null;
        $rawStatus = strtolower($badge['text'] ?? 'no submission');

        $editRequested = $form['edit_requested'] ?? false;

        if ($editRequested) {
            $status = 'Edit Requested';
            $statusType = 'edit_requested';

        } elseif (in_array($rawStatus, ['submitted to sacdev', 'forwarded to sacdev'])) {
            $status = 'Ready for Approval';
            $statusType = 'ready';

        } elseif (str_contains($rawStatus, 'approved')) {
            $status = 'Approved';
            $statusType = 'approved';

        } elseif (str_contains($rawStatus, 'returned')) {
            $status = 'Returned';
            $statusType = 'returned';

        } elseif (str_contains($rawStatus, 'submitted')) {
            $status = 'Submitted';
            $statusType = 'submitted';

        } else {
            $status = 'No submission';
            $statusType = 'none';
        }

       
        $statusClasses = match($statusType) {
            'edit_requested' => 'bg-purple-50 text-purple-700 border-purple-200',
            'ready' => 'bg-amber-50 text-amber-700 border-amber-200',
            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'returned' => 'bg-rose-50 text-rose-700 border-rose-200',
            'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
            default => 'bg-slate-50 text-slate-600 border-slate-200',
        };
    @endphp


    <div class="p-5 space-y-4">

        {{-- ================= HEADER ================= --}}
        <div class="flex items-start justify-between gap-4">

            {{-- LEFT --}}
            <div class="space-y-2">

                {{-- TITLE --}}
                <div class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i>
                    {{ $form['label'] ?? 'Form' }}
                </div>

                {{-- STATUS BADGE --}}
                <div class="inline-flex items-center gap-2 rounded-md border px-2.5 py-1 text-[11px] font-semibold {{ $statusClasses }}">
                    
                    <span class="w-1.5 h-1.5 rounded-full
                        @if($statusType === 'edit_requested') bg-purple-500
                        @elseif($statusType === 'approved') bg-emerald-500
                        @elseif($statusType === 'returned') bg-rose-500
                        @elseif($statusType === 'ready') bg-amber-400
                        @elseif($statusType === 'submitted') bg-blue-500
                        @else bg-slate-400
                        @endif
                    "></span>

                    {{ $status }}
                </div>

                {{-- META --}}
                <div class="text-[11px] text-slate-500 space-y-0.5">

                    @if(!empty($form['meta']['submitted_at']))
                        <div>
                            Submitted: {{ $form['meta']['submitted_at'] }}
                        </div>
                    @endif

                    @if(!empty($form['meta']['reviewed_at']))
                        <div>
                            Reviewed: {{ $form['meta']['reviewed_at'] }}
                        </div>
                    @endif

                </div>

            </div>


            {{-- ================= ACTIONS ================= --}}
            <div class="flex flex-col items-end gap-2">

                {{-- VIEW --}}
                @if(!empty($form['viewRoute']))
                    <a href="{{ route($form['viewRoute'], $form['routeParams'] ?? []) }}"
                       class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white
                              px-3 py-1.5 text-xs font-semibold text-slate-700
                              hover:bg-slate-50 transition">

                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        View
                    </a>
                @endif


                {{-- APPROVE --}}
                @if(
                    isset($key) &&
                    $key === 'b6' &&
                    ($status !== 'Approved') &&
                    !empty($form['routeParams']['submission'])
                )
                    <form method="POST"
                          action="{{ route('admin.constitution.approve', $form['routeParams']['submission']) }}">
                        @csrf

                        <button type="submit"
                            class="inline-flex items-center gap-1 rounded-lg
                                   bg-slate-900 px-3 py-1.5 text-xs font-semibold
                                   text-white hover:bg-slate-800 transition">

                            <i data-lucide="check" class="w-3 h-3"></i>
                            Approve
                        </button>
                    </form>
                @endif

            </div>

        </div>


        {{-- ================= REMARKS ================= --}}
        @if(!empty($form['remarksPreview']))
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">

                <div class="text-[11px] font-semibold text-slate-600 flex items-center gap-1">
                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                    Latest remarks
                </div>

                <div class="text-sm text-slate-700 mt-1 line-clamp-2">
                    {{ $form['remarksPreview'] }}
                </div>

            </div>
        @endif

    </div>

</div>