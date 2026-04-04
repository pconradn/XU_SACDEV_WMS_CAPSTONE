<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5 space-y-5">


    <div 
        x-data="{
            tips: @js($tips),
            index: 0
        }"
        x-init="setInterval(() => index = (index + 1) % tips.length, 3500)"
        class="flex items-center gap-3 bg-indigo-50 border border-indigo-200 rounded-xl px-3 py-2"
    >

        {{-- LEFT LABEL --}}
        <div class="flex items-center gap-1 shrink-0">

            <i data-lucide="lightbulb" class="w-4 h-4 text-indigo-600"></i>

            <span class="text-[10px] font-semibold text-indigo-700">
                Helpful Tips
            </span>

        </div>


        {{-- DIVIDER (subtle) --}}
        <div class="w-px h-4 bg-indigo-200"></div>


        {{-- RIGHT CONTENT --}}
        <div class="relative flex-1 h-4 overflow-hidden text-[11px] text-indigo-900">

            <template x-for="(tip, i) in tips" :key="i">
                <div 
                    x-show="index === i"
                    x-transition.opacity
                    class="absolute inset-0 flex items-center leading-tight"
                >

                    {{-- WITH LINK --}}
                    <template x-if="tip.url">
                        <a 
                            :href="tip.url"
                            class="underline hover:opacity-80 transition truncate"
                            x-text="tip.text"
                        ></a>
                    </template>

                    {{-- WITHOUT LINK --}}
                    <template x-if="!tip.url">
                        <span class="truncate" x-text="tip.text"></span>
                    </template>

                </div>
            </template>

        </div>


        {{-- OPTIONAL DOTS (RIGHT SIDE) --}}
        <div class="flex gap-1 shrink-0">
            <template x-for="(tip, i) in tips" :key="i">
                <div 
                    class="w-1.5 h-1.5 rounded-full transition-all"
                    :class="index === i ? 'bg-indigo-600 scale-110' : 'bg-indigo-300'"
                ></div>
            </template>
        </div>

    </div>


    {{-- ================= PROGRESS ================= --}}
    <div class="space-y-2">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-2">
                <i data-lucide="activity" class="w-4 h-4 text-slate-500"></i>

                <div>
                    <div class="text-xs font-semibold text-slate-800">
                        Workflow Progress
                    </div>

                    <div class="text-[10px] text-slate-500">
                        {{ $completedRequired }} / {{ $requiredCount }} required documents completed
                    </div>
                </div>
            </div>

            <div class="text-[11px] font-semibold text-slate-700">
                {{ round($progressPercent) }}%
            </div>

        </div>


        {{-- PROGRESS BAR --}}
        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">

            <div
                class="h-2 transition-all duration-500 ease-out rounded-full

                @if($progressPercent == 100)
                    bg-emerald-500
                @elseif($progressPercent >= 50)
                    bg-indigo-600
                @else
                    bg-amber-500
                @endif
                "
                style="width: {{ $progressPercent }}%"
            ></div>

        </div>


        {{-- STATUS LABEL (NEW UX BOOST) --}}
        <div class="text-[10px]">

            @if($progressPercent == 100)
                <span class="text-emerald-600 font-semibold">
                    All required documents completed
                </span>
            @elseif($progressPercent >= 50)
                <span class="text-indigo-600 font-semibold">
                    More than halfway there
                </span>
            @else
                <span class="text-amber-600 font-semibold">
                    Getting started — continue submitting documents
                </span>
            @endif

        </div>

        

    </div>

</div>