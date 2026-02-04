<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">

        <div>
            <h1 class="text-xl font-semibold text-slate-900">Registration Form B-1: Strategic Plan</h1>
            <div class="text-sm text-slate-600">
                Target School Year: <span class="font-semibold">{{ $schoolYear->name }}</span>
                <a href="{{ route('org.rereg.index') }}" class="text-blue-700 hover:underline ml-2">
                    Back to Re-Registration
                </a>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs px-2.5 py-1 rounded-full border
                @if($submission->status === 'approved' || $submission->status === 'approved_by_sacdev')
                    bg-emerald-50 border-emerald-200 text-emerald-700
                @elseif(str_contains($submission->status, 'returned'))
                    bg-rose-50 border-rose-200 text-rose-700
                @elseif(in_array($submission->status, ['submitted_to_moderator','forwarded_to_sacdev'], true))
                    bg-amber-50 border-amber-200 text-amber-700
                @else
                    bg-slate-50 border-slate-200 text-slate-700
                @endif
            ">
                Status: {{ $submission->status }}
            </span>
        </div>
    </div>

    @if(!empty($submission->moderator_remarks))
        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13 16h-1v-4h-1m1-4h.01M12 20.5a8.5 8.5 0 100-17 8.5 8.5 0 000 17z"/>
                    </svg>
                </div>

                <div class="flex-1">
                    <div class="text-sm font-semibold text-amber-800">
                        Moderator Remarks
                    </div>

                    <div class="mt-1 text-sm text-amber-900 whitespace-pre-line">
                        {{ $submission->moderator_remarks }}
                    </div>

                    @if($submission->moderator_reviewed_at)
                        <div class="mt-2 text-xs text-amber-700">
                            Reviewed on {{ $submission->moderator_reviewed_at->format('F j, Y g:i A') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(!empty($submission->sacdev_remarks))
        <div class="mt-4 rounded-xl border border-indigo-200 bg-indigo-50 p-4">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13 16h-1v-4h-1m1-4h.01M12 20.5a8.5 8.5 0 100-17 8.5 8.5 0 000 17z"/>
                    </svg>
                </div>

                <div class="flex-1">
                    <div class="text-sm font-semibold text-indigo-800">
                        SACDEV Remarks
                    </div>

                    <div class="mt-1 text-sm text-indigo-900 whitespace-pre-line">
                        {{ $submission->sacdev_remarks }}
                    </div>

                    @if($submission->sacdev_reviewed_at)
                        <div class="mt-2 text-xs text-indigo-700">
                            Reviewed on {{ $submission->sacdev_reviewed_at->format('F j, Y g:i A') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 text-sm">
            <div class="font-semibold mb-1">Please fix the following:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
