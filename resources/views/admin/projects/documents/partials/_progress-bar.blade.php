@php
    $percentage = (int) ($progress['percentage'] ?? 0);
    $approved = $progress['approved'] ?? 0;
    $required = $progress['required'] ?? 0;
    $remaining = max($required - $approved, 0);

    $barColor = match(true) {
        $percentage === 100 => 'from-emerald-500 to-emerald-400',
        $percentage >= 60 => 'from-blue-500 to-blue-400',
        $percentage >= 30 => 'from-amber-500 to-amber-400',
        default => 'from-rose-500 to-rose-400',
    };

    $cardStyle = match(true) {
        $percentage === 100 => 'border-emerald-300 bg-gradient-to-br from-emerald-50 to-white',
        $percentage >= 60 => 'border-blue-200 bg-gradient-to-br from-blue-50 to-white',
        $percentage >= 30 => 'border-amber-200 bg-gradient-to-br from-amber-50 to-white',
        default => 'border-rose-200 bg-gradient-to-br from-rose-50 to-white',
    };

  
@endphp


<div class="w-full rounded-2xl border shadow-sm p-4 space-y-4 {{ $cardStyle }}">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-2">

            <h2 class="text-[11px] font-semibold text-slate-700">
                Workflow Progress
            </h2>

            @if($percentage === 100)
                <span class="px-2 py-0.5 text-[10px] rounded-full bg-emerald-100 text-emerald-700 font-medium">
                    Complete
                </span>
            @endif

        </div>

        <span class="text-sm font-semibold text-slate-800">
            {{ $percentage }}%
        </span>

    </div>


    {{-- BAR --}}
    <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">

        <div
            class="h-2.5 rounded-full transition-all duration-500
                @if($percentage === 100)
                    bg-gradient-to-r from-emerald-500 to-emerald-400
                @elseif($percentage >= 60)
                    bg-gradient-to-r from-blue-500 to-blue-400
                @elseif($percentage >= 30)
                    bg-gradient-to-r from-amber-500 to-amber-400
                @else
                    bg-gradient-to-r from-rose-500 to-rose-400
                @endif
            "
            style="width: {{ $percentage }}%">
        </div>

    </div>


    {{-- META --}}
    <div class="flex items-center justify-between text-[10px] text-slate-600">

        <div class="font-medium">
            {{ $approved }} / {{ $required }} approved
        </div>

        @if($percentage < 100)
            <div class="text-slate-400">
                {{ $remaining }} remaining
            </div>
        @endif

    </div>


    {{-- HINT --}}
    <div class="text-[10px] border-t pt-2
        @if($percentage === 100) text-emerald-600 border-emerald-200
        @elseif($percentage >= 60) text-blue-600 border-blue-200
        @elseif($percentage >= 30) text-amber-600 border-amber-200
        @else text-rose-600 border-rose-200
        @endif
    ">

        @if($percentage === 100)
            All required documents completed
        @elseif($percentage >= 60)
            Progress is on track — nearing completion
        @elseif($percentage >= 30)
            Several documents still need attention
        @else
            Start completing required documents
        @endif

    </div>

</div>