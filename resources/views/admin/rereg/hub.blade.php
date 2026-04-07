<x-app-layout>

<div class="mx-auto max-w-7xl px-4 py-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="rounded-2xl border border-slate-200 border-t-4 border-t-blue-500 bg-gradient-to-b from-slate-50 to-white shadow-sm px-6 py-5">

        @include('admin.rereg.partials._header', [
            'organization' => $organization,
            'schoolYears' => $schoolYears,
            'encodeSyId' => $encodeSyId,
            'allApproved' => $allApproved,
            'alreadyActivated' => $alreadyActivated
        ])

    </div>


    {{-- ================= MAIN CONTENT ================= --}}
    <div class="rounded-2xl border border-slate-200 border-t-4 border-t-slate-300 bg-white shadow-sm px-6 py-6 space-y-6">

        @if(!$encodeSyId)

            {{-- EMPTY STATE --}}
            <div class="flex flex-col items-center justify-center py-12 text-center space-y-2">

                <i data-lucide="calendar-x" class="w-6 h-6 text-slate-400"></i>

                <div class="text-sm font-semibold text-slate-700">
                    No School Year Selected
                </div>

                <div class="text-xs text-slate-500">
                    Please select a target school year to view submissions.
                </div>

            </div>

        @else

            {{-- ================= SECTION HEADER ================= --}}
            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                     
                        Submission Forms
                    </h3>

                    <p class="text-xs text-slate-500">
                        Review and manage organization requirements
                    </p>
                </div>

                {{-- OPTIONAL STATUS INDICATOR --}}
                <div class="text-[10px] text-slate-400">
                    {{ count($forms ?? []) }} forms
                </div>

            </div>


            {{-- ================= FORMS GRID WRAPPER ================= --}}
           

                @include('admin.rereg.partials._forms_grid', [
                    'forms' => $forms
                ])

     

        @endif

    </div>

</div>

{{-- ================= ACTIVATE MODAL ================= --}}
@includeWhen(
    $encodeSyId && $allApproved && !$alreadyActivated,
    'admin.rereg.partials._activate_modal',
    ['organization' => $organization]
)

</x-app-layout>