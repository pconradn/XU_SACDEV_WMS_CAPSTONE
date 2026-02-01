<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Registration Form B-1: Strategic Plan</h1>
            <p class="text-sm text-slate-500">
                School Year: <span class="font-medium text-slate-700">{{ $schoolYear?->name ?? 'N/A' }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs px-2.5 py-1 rounded-full border
                @if($submission->status === 'approved')
                    bg-emerald-50 border-emerald-200 text-emerald-700
                @elseif(str_contains($submission->status, 'returned'))
                    bg-rose-50 border-rose-200 text-rose-700
                @elseif($submission->status === 'submitted_to_moderator' || $submission->status === 'forwarded_to_sacdev')
                    bg-amber-50 border-amber-200 text-amber-700
                @else
                    bg-slate-50 border-slate-200 text-slate-700
                @endif
            ">
                Status: {{ $submission->status }}
            </span>
        </div>
    </div>

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
