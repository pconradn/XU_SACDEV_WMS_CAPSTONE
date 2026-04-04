<div class="space-y-2.5 w-full">

    @if(!empty($combinedPreForm))

        <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm space-y-2.5">

            <h2 class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">
                Project Proposal
            </h2>

            @include('org.projects.documents.v2.partials.form-row', [
                'form' => $combinedPreForm
            ])

        </div>

    @endif

    @if($preSubmitted)

        {{-- ================= ACTION REQUIRED ================= --}}
        @if($documentsActionRequired->isNotEmpty())
            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm space-y-2.5">

                <h2 class="text-xs font-semibold text-rose-600 uppercase tracking-wide">
                    Action Required
                </h2>

                @foreach($documentsActionRequired as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


        {{-- ================= IN PROGRESS ================= --}}
        @if($documentsInProgress->isNotEmpty())
            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm space-y-2.5">

                <h2 class="text-xs font-semibold text-blue-600 uppercase tracking-wide">
                    In Progress
                </h2>

                @foreach($documentsInProgress as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


        {{-- ================= COMPLETED ================= --}}
        @if($documentsCompleted->isNotEmpty())
            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm space-y-2.5">

                <h2 class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">
                    Completed
                </h2>

                @foreach($documentsCompleted as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


        {{-- ================= OPTIONAL ================= --}}
        @if($optional->isNotEmpty())
            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm space-y-2.5">

                <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    Other Documents
                </h2>

                @foreach($optional as $form)
                    @include('org.projects.documents.v2.partials.form-row', ['form' => $form])
                @endforeach

            </div>
        @endif


    @else

        <div class="bg-white border border-slate-200 rounded-xl px-4 py-4 text-center text-xs text-slate-500 shadow-sm">
            Other documents will be available once the Project Proposal and Budget Proposal are submitted.
        </div>

    @endif

</div>