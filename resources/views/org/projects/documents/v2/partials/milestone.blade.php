<div class="bg-white border rounded-2xl p-5 shadow-sm">

    <div class="flex items-center justify-between">

        @foreach($milestones as $index => $milestone)

        @php
            $currentIndex = collect($milestones)->pluck('key')->search($currentStage);
            $isActive = $index <= $currentIndex;
            $isCurrent = $index === $currentIndex;
        @endphp
            <div class="flex-1 flex items-center">

                {{-- NODE --}}
                <div class="flex flex-col items-center text-center w-full">

                    <div class="
                        w-8 h-8 flex items-center justify-center rounded-full text-xs font-semibold
                        {{ $isActive ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500' }}
                        {{ $isCurrent ? 'ring-4 ring-indigo-100' : '' }}
                    ">
                        {{ $index + 1 }}
                    </div>

                    <p class="mt-2 text-xs
                        {{ $isActive ? 'text-slate-800 font-medium' : 'text-slate-400' }}">
                        {{ $milestone['label'] }}
                    </p>

                </div>

                {{-- LINE --}}
                @if(!$loop->last)
                    <div class="h-[2px] flex-1
                        {{ $isActive ? 'bg-indigo-600' : 'bg-slate-200' }}">
                    </div>
                @endif

            </div>

        @endforeach

    </div>

</div>