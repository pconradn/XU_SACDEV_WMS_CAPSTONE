<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

    @php
        $pending = $sectionCounts[$sectionKey] ?? 0;

        $phaseOrder = [
            'other',
            'off-campus',
            'post_implementation',
            'notice',
        ];

        $groupedForms = collect($forms)
            ->groupBy(fn($form) => $form['phase'] ?? $form['document']?->formType->phase ?? 'other')
            ->sortBy(function ($_, $phase) use ($phaseOrder) {
                return array_search($phase, $phaseOrder);
            });

        $phaseLabels = [
            'other' => 'Supporting Documents',
            'off-campus' => 'Off-Campus Requirements',
            'post_implementation' => 'Post-Implementation',
            'notice' => 'Notices / Adjustments',
        ];
    @endphp

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b flex items-center justify-between bg-slate-50">

        <div class="flex items-center gap-2">
            <h3 class="text-sm font-semibold text-slate-800">
                {{ $title }}
            </h3>

            @if($pending > 0)
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold">
                    {{ $pending }} pending
                </span>
            @endif
        </div>

        <span class="text-xs text-slate-400">
            {{ count($forms) }} forms
        </span>

    </div>


    {{-- BODY --}}
    <div class="divide-y">

        @forelse($groupedForms as $phase => $phaseForms)

            {{-- PHASE HEADER --}}
            <div class="bg-slate-100 px-5 py-2 text-[11px] font-semibold text-slate-600 uppercase tracking-wide">
                {{ $phaseLabels[$phase] ?? ucfirst(str_replace('_',' ', $phase)) }}
            </div>

            @foreach($phaseForms as $form)

            @php
                $doc = $form['document'] ?? null;
                $status = $doc?->status ?? 'draft';
                $currentApprover = $doc?->currentPendingSignature();

                $isMine = $form['is_waiting_for_me'] ?? false;
                $isRequired = $sectionKey === 'required';
                $isOptional = $sectionKey === 'optional';
                $isMissingRequired = $isRequired && !$doc;
            @endphp

            <div class="flex items-start justify-between px-5 py-4 transition
                {{ $isMine ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}
                {{ $isMissingRequired ? 'bg-red-50/40' : '' }}">

                {{-- LEFT CONTENT --}}
                <div class="space-y-2 w-full">

                    {{-- TITLE --}}
                    <div class="flex items-center gap-2 flex-wrap">

                        <p class="text-sm font-semibold text-slate-800">
                            {{ $form['name'] }}
                        </p>

                        @if($isRequired)
                            <span class="text-[10px] px-2 py-0.5 bg-red-100 text-red-700 rounded-full font-semibold">
                                REQUIRED
                            </span>
                        @endif

                        @if($isOptional)
                            <span class="text-[10px] px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full">
                                OPTIONAL
                            </span>
                        @endif

                    </div>

                    {{-- STATUS ROW --}}
                    <div class="flex items-center justify-between gap-3">

                        {{-- LEFT --}}
                        <div class="flex items-center gap-2 flex-wrap">

                            <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full ring-1 {{ $form['status_class'] }}">
                                {{ $form['status_label'] }}
                            </span>

                            @if($isMine)
                                <span class="text-xs font-semibold text-blue-700">
                                    • Your action required
                                </span>
                            @endif

                        </div>

                        {{-- RIGHT (AWAITING) --}}
                        @if($status === 'submitted' && $currentApprover)
                            <div class="text-xs text-slate-500 whitespace-nowrap">
                                Awaiting
                                <span class="font-semibold text-slate-700 capitalize">
                                    {{ str_replace('_',' ', $currentApprover->role) }}
                                </span>
                            </div>
                        @endif

                    </div>

                    {{-- STATUS MESSAGE --}}
                    <div class="text-xs text-slate-500">

                        @if($status === 'approved_by_sacdev')
                            🟢 Fully approved and finalized

                        @elseif($status === 'submitted')
                            Under approval process

                        @elseif($status === 'draft')
                            Not yet submitted

                        @elseif($status === 'returned')
                            Needs revision before approval

                        @endif

                    </div>

                    {{-- REQUIRED WARNING --}}
                    @if($isMissingRequired)
                        <div class="text-xs text-red-600 font-medium">
                            ⚠ Required but not yet started
                        </div>
                    @endif

                </div>


                {{-- ACTION BUTTON --}}
                <div class="flex items-center gap-2 pl-4">

                    @if($form['can_create'] && $form['create_url'])
                        <a href="{{ $form['create_url'] }}"
                           class="text-xs px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Create
                        </a>

                    @elseif($form['can_edit'] && $form['edit_url'])
                        <a href="{{ $form['edit_url'] }}"
                           class="text-xs px-3 py-2 bg-amber-100 text-amber-800 rounded-md hover:bg-amber-200">
                            Continue
                        </a>

                    @elseif($form['can_review'] && $form['view_url'])
                        <a href="{{ $form['view_url'] }}"
                           class="text-xs px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Review
                        </a>

                    @elseif($form['document'] && $form['view_url'])
                        <a href="{{ $form['view_url'] }}"
                           class="text-xs px-3 py-2 bg-slate-200 text-slate-700 rounded-md hover:bg-slate-300">
                            View
                        </a>
                    @endif

                </div>

            </div>

            @endforeach

        @empty

            <div class="px-5 py-6 text-sm text-slate-500 text-center">
                No forms available in this section.
            </div>

        @endforelse

    </div>

</div>