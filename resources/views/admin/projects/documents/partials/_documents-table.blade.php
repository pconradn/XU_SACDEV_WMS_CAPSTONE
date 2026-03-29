<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">

        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Project Documents
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Review and approve submitted forms
            </p>
        </div>

        {{-- PROGRESS --}}
        <div class="text-right">
            <div class="text-xs text-slate-500">
                Progress
            </div>
            <div class="text-sm font-semibold text-slate-900">
                {{ $progress['approved'] }} / {{ $progress['total'] }}
            </div>
        </div>

    </div>

    {{-- PROGRESS BAR --}}
    <div class="px-6 pt-3">
        <div class="w-full bg-slate-100 rounded-full h-2">
            <div class="bg-emerald-500 h-2 rounded-full"
                 style="width: {{ $progress['percentage'] }}%">
            </div>
        </div>
    </div>


    <div class="mt-6 space-y-6">

        @forelse($groupedForms as $phase => $forms)

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

                {{-- SECTION HEADER --}}
                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-sm font-semibold text-slate-800">
                        {{ strtoupper(str_replace('_',' ', $phase)) }}
                    </h3>

                    <span class="text-xs text-slate-400">
                        {{ count($forms) }} forms
                    </span>

                </div>

                {{-- GRID --}}
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">

                    @foreach($forms as $form)
                        @include('admin.projects.documents.partials._form-card')
                    @endforeach

                </div>

            </div>

        @empty

            <div class="text-center text-sm text-slate-500 py-10">
                No documents found.
            </div>

        @endforelse

    </div>

</div>