@php
    $orderedSignatures = collect();

    if (!empty($form['document']) && $form['document']->signatures) {
        $orderedSignatures = collect($form['document']->signatures)->sortBy(function ($sig) {
            return match ($sig->role) {
                'project_head' => 1,
                'treasurer' => 2,
                'president' => 3,
                'moderator' => 4,
                'sacdev_admin' => 5,
                default => 99,
            };
        })->values();
    }

    $doc = $form['document'] ?? null;

    $statusLabel = strtolower($form['status_label'] ?? '');
    $phase = $form['phase'] ?? 'other';

    $phaseMap = [
        'pre_implementation' => ['icon' => 'file-text', 'color' => 'text-blue-600', 'label' => 'Pre Implementation'],
        'off_campus' => ['icon' => 'map-pin', 'color' => 'text-purple-600', 'label' => 'Off Campus'],
        'other' => ['icon' => 'layers', 'color' => 'text-slate-500', 'label' => 'Other'],
        'post_implementation' => ['icon' => 'check-circle', 'color' => 'text-emerald-600', 'label' => 'Post Implementation'],
        'notice' => ['icon' => 'alert-triangle', 'color' => 'text-amber-600', 'label' => 'Notice'],
    ];

    $phaseStyle = $phaseMap[$phase] ?? $phaseMap['other'];

    $needsAttention =
        str_contains($statusLabel, 'returned') ||
        str_contains($statusLabel, 'pending') ||
        str_contains($statusLabel, 'action');

    $pendingSignature = $orderedSignatures->first(fn($sig) => ($sig->status ?? null) !== 'signed');

    $pendingApprover = $form['waiting_for']
        ?? ($pendingSignature ? ucfirst(str_replace('_', ' ', $pendingSignature->role)) : null);

    $signedCount = $orderedSignatures->where('status', 'signed')->count();
    $totalSignatures = $orderedSignatures->count();

    $rowTone = 'slate';

    if (str_contains($statusLabel, 'approved') || str_contains($statusLabel, 'completed')) {
        $rowTone = 'emerald';
    } elseif (str_contains($statusLabel, 'pending') || str_contains($statusLabel, 'review')) {
        $rowTone = 'blue';
    } elseif (str_contains($statusLabel, 'returned') || str_contains($statusLabel, 'rejected')) {
        $rowTone = 'rose';
    }

    $toneStyles = [
        'emerald' => 'border-emerald-200 bg-gradient-to-b from-emerald-50/70 to-white',
        'blue' => 'border-blue-200 bg-gradient-to-b from-blue-50/70 to-white',
        'rose' => 'border-rose-200 bg-gradient-to-b from-rose-50/70 to-white',
        'slate' => 'border-slate-200 bg-gradient-to-b from-slate-50 to-white',
    ];

    $rowClass = $toneStyles[$rowTone];
@endphp

<div class="w-full">
    <div class="rounded-2xl border shadow-sm transition hover:shadow-md {{ $rowClass }}">
        <div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-4 px-4 py-3">

            {{-- LEFT --}}
            <div class="min-w-0 space-y-1">

                {{-- TITLE + ICON BADGES --}}
                <div class="flex items-center gap-2 flex-wrap">

                    <div class="text-sm font-semibold text-slate-900 truncate">
                        {{ $form['name'] }}
                    </div>

                    {{-- PHASE ICON BADGE --}}
                    <span title="{{ $phaseStyle['label'] }}"
                          class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-slate-100 border border-slate-200">
                        <i data-lucide="{{ $phaseStyle['icon'] }}" class="w-3.5 h-3.5 {{ $phaseStyle['color'] }}"></i>
                    </span>

                    {{-- REQUIRED ICON --}}
                    @if($form['is_required'] ?? false)
                        <span title="Required for completion"
                              class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-amber-50 border border-amber-200">
                            <i data-lucide="star" class="w-3.5 h-3.5 text-amber-600"></i>
                        </span>
                    @endif

                    {{-- ACTION REQUIRED --}}
                    @if($needsAttention)
                        <span title="Needs your action"
                              class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-rose-50 border border-rose-200">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                        </span>
                    @endif

                </div>

                {{-- META --}}
                <div class="flex items-center flex-wrap gap-2 text-[11px] text-slate-500">

                    <span class="font-medium text-slate-700">
                        {{ $form['status_label'] }}
                    </span>

                    @if($pendingApprover)
                        <span>•</span>
                        <span>
                            Waiting:
                            <span class="font-medium text-slate-800">
                                {{ $pendingApprover }}
                            </span>
                        </span>
                    @endif

                    @if(optional($doc)->updated_at)
                        <span>•</span>
                        <span>
                            {{ \Carbon\Carbon::parse($doc->updated_at)->format('M d') }}
                        </span>
                    @endif

                </div>

                {{-- SIGNATURE PROGRESS --}}
                @if($totalSignatures > 0)
                    <div class="flex items-center gap-2 pt-1">

                        <div class="flex items-center gap-1">
                            @foreach($orderedSignatures as $sig)
                                <div class="w-2 h-2 rounded-full
                                    {{ $sig->status === 'signed'
                                        ? 'bg-emerald-500'
                                        : 'bg-slate-300' }}">
                                </div>
                            @endforeach
                        </div>

                        <span class="text-[10px] text-slate-500">
                            {{ $signedCount }}/{{ $totalSignatures }}
                        </span>

                    </div>
                @endif

            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center gap-2">

                @if($form['can_create'])
                    <a href="{{ $form['create_url'] }}"
                       class="px-3 py-1.5 text-[11px] font-semibold rounded-xl border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                        Create
                    </a>
                @endif

                @if($form['can_review'])
                    <a href="{{ $form['view_url'] }}"
                       class="px-3 py-1.5 text-[11px] font-semibold rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                        Review
                    </a>
                @endif

                @if($form['view_url'] && !$form['can_review'])
                    <a href="{{ $form['view_url'] }}"
                       class="px-3 py-1.5 text-[11px] font-semibold rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-100 transition">
                        Open
                    </a>
                @endif

                @php
                    $isNotice = in_array($form['code'] ?? '', [
                        'POSTPONEMENT_NOTICE',
                        'CANCELLATION_NOTICE'
                    ]);
                @endphp

                @if($isNotice && $doc && $doc->status === 'draft')
                    <form method="POST"
                          action="{{ route('org.projects.documents.notices.archive', [$project, $doc]) }}"
                          onsubmit="return confirm('Remove draft notice?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-3 py-1.5 text-[11px] font-semibold rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                            Cancel
                        </button>
                    </form>
                @endif

            </div>

        </div>
    </div>
</div>