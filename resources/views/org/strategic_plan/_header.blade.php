<div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5 space-y-4">

    {{-- TOP ROW --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        {{-- TITLE --}}
        <div>
            <h1 class="text-xl font-semibold text-slate-900">
                Registration Form B-1: Strategic Plan
            </h1>

            <div class="text-sm text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                <span>
                    Target School Year:
                    <span class="font-semibold text-slate-700">{{ $schoolYear->name }}</span>
                </span>

                <span class="hidden sm:inline">•</span>

                <a href="{{ route('org.rereg.index') }}"
                   class="text-blue-600 hover:underline font-medium">
                    Back to Re-Registration
                </a>
            </div>
        </div>

      
{{-- ACTION GROUP --}}
<div class="flex items-center gap-2 flex-wrap">

    @php
        $status = $submission->status;

        $config = match($status) {
            'draft' => [
                'label' => 'Draft',
                'class' => 'bg-slate-50 text-slate-700 border-slate-200',
                'icon' => ''
            ],

            'submitted_to_moderator' => [
                'label' => 'Under Moderator Review',
                'class' => 'bg-amber-50 text-amber-700 border-amber-200',
                'icon' => ''
            ],

            'returned_by_moderator' => [
                'label' => 'Returned by Moderator',
                'class' => 'bg-rose-50 text-rose-700 border-rose-200',
                'icon' => ''
            ],

            'forwarded_to_sacdev' => [
                'label' => 'Under SACDEV Review',
                'class' => 'bg-blue-50 text-blue-700 border-blue-200',
                'icon' => ''
            ],

            'returned_by_sacdev' => [
                'label' => 'Returned by SACDEV',
                'class' => 'bg-red-50 text-red-700 border-red-200',
                'icon' => ''
            ],

            'approved_by_sacdev' => [
                'label' => 'Approved',
                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'icon' => ''
            ],

            default => [
                'label' => ucwords(str_replace('_', ' ', $status)),
                'class' => 'bg-slate-50 text-slate-700 border-slate-200',
                'icon' => ''
            ],
        };
    @endphp

    {{-- STATUS BADGE --}}
    <span class="inline-flex items-center gap-2 text-xs px-3 py-1.5 rounded-full border font-semibold {{ $config['class'] }}">

        <span class="text-sm leading-none">
            {{ $config['icon'] }}
        </span>

        <span>
            {{ $config['label'] }}
        </span>

    </span>

</div>

{{-- REMARKS PARTIAL --}}
@include('org.strategic_plan.partials._remarks', [
    'submission' => $submission
])

{{-- TIMELINE PARTIAL --}}
@include('admin.strategic_plans.partials._timeline', [
    'submission' => $submission,
    'compact' => true
])

</div>