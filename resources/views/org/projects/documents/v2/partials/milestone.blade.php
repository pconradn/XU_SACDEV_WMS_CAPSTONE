<div class="bg-white border rounded-xl p-4 shadow-sm space-y-4">

    {{-- ================= TIPS ================= --}}
    <div 
        x-data="{
            tips: @js($tips),
            index: 0
        }"
        x-init="setInterval(() => index = (index + 1) % tips.length, 3500)"
        class="relative bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 overflow-hidden"
    >

        {{-- LABEL --}}
        <div class="text-[10px] font-semibold text-blue-700 mb-1">
            Helpful Tips
        </div>

        {{-- TIP ROTATOR --}}
        <div class="h-4 relative overflow-hidden text-[11px] text-blue-800">

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
                            class="underline hover:opacity-80 transition"
                            x-text="tip.text"
                        ></a>
                    </template>

                    {{-- WITHOUT LINK --}}
                    <template x-if="!tip.url">
                        <span x-text="tip.text"></span>
                    </template>
                </div>
            </template>

        </div>

        {{-- OPTIONAL PROGRESS DOTS --}}
        <div class="absolute right-2 bottom-1 flex gap-1">
            <template x-for="(tip, i) in tips" :key="i">
                <div 
                    class="w-1.5 h-1.5 rounded-full"
                    :class="index === i ? 'bg-blue-600' : 'bg-blue-300'"
                ></div>
            </template>
        </div>

    </div>


    {{-- ================= PROGRESS HEADER ================= --}}
    <div class="flex items-center justify-between">

        <div>
            <div class="text-xs font-semibold text-slate-700">
                Workflow Progress
            </div>

            <div class="text-[10px] text-slate-500 mt-0.5">
                {{ $completedRequired }} / {{ $requiredCount }} required documents completed
            </div>
        </div>

        <div class="text-[11px] font-semibold text-slate-600">
            {{ round($progressPercent) }}%
        </div>

    </div>


    {{-- ================= PROGRESS BAR ================= --}}
    <div class="w-full bg-slate-100 rounded h-2 overflow-hidden">

        <div
            class="h-2 transition-all duration-500 ease-out
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

</div>