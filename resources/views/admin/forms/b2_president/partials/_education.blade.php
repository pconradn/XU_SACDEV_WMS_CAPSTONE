<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between">

        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Educational Background
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Academic history and scholarship information submitted by the organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2.5 w-2.5 rounded-full
                    {{
                        $registration->high_school_name ||
                        $registration->grade_school_name ||
                        $registration->scholarship_name
                        ? 'bg-emerald-500'
                        : 'bg-slate-400'
                    }}">
                </span>
            </span>

            <span>
                {{
                    $registration->high_school_name ||
                    $registration->grade_school_name ||
                    $registration->scholarship_name
                    ? 'Education info provided'
                    : 'No education info provided'
                }}
            </span>

        </div>

    </div>



    <div class="mt-6 grid grid-cols-1 xl:grid-cols-2 gap-6">


        {{-- High School --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

            <div class="text-sm font-semibold text-slate-900 mb-3">
                High School
            </div>

            <div class="space-y-3">

                <div>
                    <div class="text-xs text-slate-500">School Name</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->high_school_name ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Address</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->high_school_address ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Year Graduated</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->high_school_year_graduated ?: '—' }}
                    </div>
                </div>

            </div>

        </div>



        {{-- Grade School --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

            <div class="text-sm font-semibold text-slate-900 mb-3">
                Grade School
            </div>

            <div class="space-y-3">

                <div>
                    <div class="text-xs text-slate-500">School Name</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->grade_school_name ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Address</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->grade_school_address ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Year Graduated</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->grade_school_year_graduated ?: '—' }}
                    </div>
                </div>

            </div>

        </div>


    </div>



    {{-- Scholarship --}}
    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4">

        <div class="text-sm font-semibold text-slate-900 mb-3">
            Scholarship
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>
                <div class="text-xs text-slate-500">Scholarship Name</div>
                <div class="text-sm text-slate-900 mt-1">
                    {{ $registration->scholarship_name ?: '—' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-slate-500">Year Granted</div>
                <div class="text-sm text-slate-900 mt-1">
                    {{ $registration->scholarship_year_granted ?: '—' }}
                </div>
            </div>

        </div>

    </div>

</div>