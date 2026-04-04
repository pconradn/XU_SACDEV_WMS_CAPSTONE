<div class="space-y-4 w-full">

    {{-- ================= PROPOSAL ================= --}}
    @if(!empty($combinedPreForm))
        <div class="rounded-2xl border border-indigo-200 bg-gradient-to-b from-indigo-50 to-white shadow-sm p-4 space-y-3">

            <div class="flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-indigo-600"></i>
                <h2 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide">
                    Project Proposal
                </h2>
            </div>

            @include('org.projects.documents.v2.partials.form-row', [
                'form' => $combinedPreForm
            ])

        </div>
    @endif


    @if($preSubmitted)

        {{-- ================= ACTION REQUIRED ================= --}}
        @if($documentsActionRequired->isNotEmpty())
            <div class="rounded-2xl border border-rose-300 bg-rose-50 shadow-sm p-4 space-y-3 ring-1 ring-rose-200">

                <div class="flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                    <h2 class="text-xs font-semibold text-rose-700 uppercase tracking-wide">
                        Action Required
                    </h2>

                    <span class="ml-auto text-[10px] px-2 py-0.5 rounded bg-rose-100 text-rose-700 font-semibold">
                        {{ $documentsActionRequired->count() }}
                    </span>
                </div>

                @foreach($documentsActionRequired as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


        {{-- ================= IN PROGRESS ================= --}}
        @if($documentsInProgress->isNotEmpty())
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 shadow-sm p-4 space-y-3">

                <div class="flex items-center gap-2">
                    <i data-lucide="loader" class="w-4 h-4 text-indigo-600"></i>
                    <h2 class="text-xs font-semibold text-indigo-700 uppercase tracking-wide">
                        In Progress
                    </h2>

                    <span class="ml-auto text-[10px] px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 font-semibold">
                        {{ $documentsInProgress->count() }}
                    </span>
                </div>

                @foreach($documentsInProgress as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


        {{-- ================= COMPLETED ================= --}}
        @if($documentsCompleted->isNotEmpty())
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm p-4 space-y-3">

                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                    <h2 class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">
                        Completed
                    </h2>

                    <span class="ml-auto text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 font-semibold">
                        {{ $documentsCompleted->count() }}
                    </span>
                </div>

                @foreach($documentsCompleted as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


        {{-- ================= OPTIONAL ================= --}}
        @if($optional->isNotEmpty())
            <div class="rounded-2xl border border-slate-200 bg-slate-50 shadow-sm p-4 space-y-3">

                <div class="flex items-center gap-2">
                    <i data-lucide="folder" class="w-4 h-4 text-slate-500"></i>
                    <h2 class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Other Documents
                    </h2>

                    <span class="ml-auto text-[10px] px-2 py-0.5 rounded bg-slate-200 text-slate-600 font-semibold">
                        {{ $optional->count() }}
                    </span>
                </div>

                @foreach($optional as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


    @else

        {{-- EMPTY STATE --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white px-4 py-5 text-center shadow-sm">

            <div class="flex flex-col items-center gap-2">

                <i data-lucide="info" class="w-5 h-5 text-slate-400"></i>

                <p class="text-xs text-slate-600 max-w-xs">
                    Other documents will be available once the Project Proposal and Budget Proposal are submitted.
                </p>

            </div>

        </div>

    @endif

</div>