<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<div 
    x-data="{
        active: 0,
        count: 4,
        update() {
            let el = this.$refs.scroller
            let index = Math.round(el.scrollLeft / el.offsetWidth)
            this.active = index
        }
    }"
    class="space-y-2"
>

   
    <div 
        x-ref="scroller"
        @scroll="update"
        class="flex lg:grid lg:grid-cols-5 gap-4 overflow-x-auto lg:overflow-visible snap-x snap-mandatory no-scrollbar"
    >

    
        <div class="card p-4 flex-shrink-0 w-[85vw] sm:w-[60vw] lg:w-full snap-start">

            <div class="card-header">
                Selected Organization
            </div>

            <div class="mt-1 text-[14px] font-semibold max-w-full truncate">
                {{ $currentOrg?->name ?? '—' }}
            </div>

            <div class="mt-1 text-xs text-slate-600">
                Acronym: {{ $currentOrg?->acronym ?? '—' }}
            </div>

            @if(!$currentOrg)
                <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-2 text-xs text-slate-600">
                    You are not currently assigned to an organization.
                </div>
            @endif

        </div>


     
        <div class="relative flex-shrink-0 w-[85vw] sm:w-[60vw] lg:w-full snap-start rounded-2xl border border-rose-200 bg-gradient-to-b from-rose-50 to-white shadow-sm p-4">

            <div class="absolute top-3 right-3 text-rose-400">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
            </div>

            <div class="text-[10px] font-semibold uppercase tracking-wide text-rose-700">
                Pending Tasks
            </div>

            <div class="mt-1 text-2xl font-semibold text-rose-800">
                {{ $pendingCount ?? 0 }}
            </div>

            <p class="mt-1 text-xs text-rose-700/80">
                Awaiting your approval or action.
            </p>
        </div>



        <div class="relative flex-shrink-0 w-[85vw] sm:w-[60vw] lg:w-full snap-start rounded-2xl border border-blue-200 bg-gradient-to-b from-blue-50 to-white shadow-sm p-4">

            <div class="absolute top-3 right-3 text-blue-400">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
            </div>

            <div class="text-[10px] font-semibold uppercase tracking-wide text-blue-700">
                Assigned Projects
            </div>

            <div class="mt-1 text-2xl font-semibold text-blue-900">
                {{ $assignedProjects->count() }}
            </div>

            <p class="mt-1 text-xs text-blue-700/80">
                Projects under your responsibility.
            </p>
        </div>


        <div class="relative flex-shrink-0 w-[85vw] sm:w-[60vw] lg:w-full snap-start rounded-2xl border border-indigo-200 bg-gradient-to-b from-indigo-50 to-white shadow-sm p-4">

            <div class="absolute top-3 right-3 text-indigo-400">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>

            <div class="text-[10px] font-semibold uppercase tracking-wide text-indigo-700">
                Organization Projects
            </div>

            <div class="mt-1 text-2xl font-semibold text-indigo-900">
                {{ $projectCount ?? 0 }}
            </div>

            <p class="mt-1 text-xs text-indigo-700/80">
                Total tracked projects this school year.
            </p>
        </div>



        <div class="relative flex-shrink-0 w-[85vw] sm:w-[60vw] lg:w-full snap-start rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50 to-white shadow-sm p-4">

            <div class="absolute top-3 right-3 text-emerald-400">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>

            <div class="text-[10px] font-semibold uppercase tracking-wide text-emerald-700">
                Project Documents
            </div>

            <div class="mt-1 text-2xl font-semibold text-emerald-900">
                {{ $documentCount ?? 0 }}
            </div>

            <p class="mt-1 text-xs text-emerald-700/80">
                All submitted and generated forms.
            </p>
        </div>

    </div>

  
    <div class="flex justify-center gap-1.5 lg:hidden">
        <template x-for="i in count" :key="i">
            <div 
                class="h-1.5 rounded-full transition-all"
                :class="active === (i - 1) 
                    ? 'w-5 bg-slate-800' 
                    : 'w-2 bg-slate-300'"
            ></div>
        </template>
    </div>

</div>