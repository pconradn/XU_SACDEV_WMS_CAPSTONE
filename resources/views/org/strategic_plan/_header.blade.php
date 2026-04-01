<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="px-6 py-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        {{-- LEFT --}}
        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                Strategic Plan (B-1)
            </h1>

            <div class="mt-1 text-sm text-slate-500 flex flex-wrap items-center gap-2">
                <span>
                    Target SY:
                    <span class="font-semibold text-slate-700">{{ $schoolYear->name }}</span>
                </span>

                <span class="hidden sm:inline">•</span>

                <a href="{{ route('org.rereg.index') }}"
                   class="text-blue-600 hover:underline font-medium">
                    Back to Re-Registration
                </a>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="flex items-center gap-2 flex-wrap">

            {{-- STATUS --}}
            @php
                $status = $submission->status;

                $config = match($status) {
                    'draft' => ['label' => 'Draft', 'class' => 'bg-slate-100 text-slate-700'],
                    'submitted_to_moderator' => ['label' => 'Moderator Review', 'class' => 'bg-amber-50 text-amber-700'],
                    'returned_by_moderator' => ['label' => 'Returned (Moderator)', 'class' => 'bg-rose-50 text-rose-700'],
                    'forwarded_to_sacdev' => ['label' => 'SACDEV Review', 'class' => 'bg-blue-50 text-blue-700'],
                    'returned_by_sacdev' => ['label' => 'Returned (SACDEV)', 'class' => 'bg-red-50 text-red-700'],
                    'approved_by_sacdev' => ['label' => 'Approved', 'class' => 'bg-emerald-50 text-emerald-700'],
                    default => ['label' => ucwords(str_replace('_', ' ', $status)), 'class' => 'bg-slate-100 text-slate-700'],
                };
            @endphp

            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $config['class'] }}">
                {{ $config['label'] }}
            </span>

            {{-- TIMELINE BUTTON --}}
            @include('admin.strategic_plans.partials._timeline', [
                'submission' => $submission,
                'compact' => true
            ])

            {{-- REMARKS BUTTON --}}
            @include('org.strategic_plan.partials._remarks', [
                'submission' => $submission
            ])

        </div>

    </div>

    {{-- ACTION HINT --}}
    @php
        $nextAction = match($status) {
            'draft' => 'You can edit and submit this form.',
            'submitted_to_moderator' => 'Waiting for moderator review.',
            'returned_by_moderator' => 'Please revise and resubmit.',
            'forwarded_to_sacdev' => 'Waiting for SACDEV review.',
            'returned_by_sacdev' => 'Please revise based on SACDEV feedback.',
            'approved_by_sacdev' => 'This form is fully approved.',
            default => null,
        };
    @endphp

    @if($nextAction)
        <div class="px-6 py-3 border-t bg-slate-50 text-xs text-slate-600">
            {{ $nextAction }}
        </div>
    @endif

</div>
