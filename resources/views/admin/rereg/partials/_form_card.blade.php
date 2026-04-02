<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    @php
        $badge = $form['badge'] ?? null;
        $rawStatus = strtolower($badge['text'] ?? 'no submission');
        //dd($rawStatus);
        
        if (in_array($rawStatus, ['submitted to sacdev', 'forwarded to sacdev'])) {
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

        // 🔹 Status colors
        $statusClasses = match($statusType) {
            'ready' => 'bg-amber-50 text-amber-700 border-amber-200',
            'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'returned' => 'bg-rose-50 text-rose-700 border-rose-200',
            'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
            default => 'bg-slate-50 text-slate-600 border-slate-200',
        };

        // 🔹 Form color identity (based on key)
        $formColor = match($key ?? null) {
            'b1' => 'bg-emerald-50',   // Strategic Plan
            'b2' => 'bg-violet-50',    // President
            'b3' => 'bg-orange-50',    // Officers
            'b4' => 'bg-blue-50',      // Moderator
            default => 'bg-slate-50',
        };
    @endphp


    {{-- Top Accent --}}
    <div class="h-1 w-full {{ $formColor }}"></div>


    <div class="p-5">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">

            <div class="space-y-2">

                {{-- Title --}}
                <div class="font-semibold text-slate-900 text-sm">
                    {{ $form['label'] ?? 'Form' }}
                </div>

                {{-- Status Badge --}}
                <div class="inline-flex items-center gap-2 rounded-md border px-2.5 py-1 text-xs font-medium {{ $statusClasses }}">
                    {{ $status }}
                </div>

                {{-- Meta --}}
                <div class="text-xs text-slate-500 space-y-0.5">

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


            {{-- Actions --}}
            <div class="flex flex-col items-end gap-2">

                {{-- View --}}
                @if(!empty($form['viewRoute']))
                    <a href="{{ route($form['viewRoute'], $form['routeParams'] ?? []) }}"
                       class="inline-flex items-center rounded-md border border-slate-200
                              px-3 py-1.5 text-xs font-medium text-slate-700
                              hover:bg-slate-50">
                        View
                    </a>
                @endif


                {{-- Approve (B6 only) --}}
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
                            class="inline-flex items-center gap-1 rounded-md
                                   bg-emerald-600 px-3 py-1.5 text-xs font-semibold
                                   text-white hover:bg-emerald-700">

                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 13l4 4L19 7"/>
                            </svg>

                            Approve
                        </button>
                    </form>
                @endif

            </div>

        </div>


        {{-- Remarks --}}
        @if(!empty($form['remarksPreview']))
            <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3">
                <div class="text-xs font-semibold text-slate-700">
                    Latest remarks
                </div>
                <div class="text-sm text-slate-700 mt-1 line-clamp-2">
                    {{ $form['remarksPreview'] }}
                </div>
            </div>
        @endif

    </div>

</div>