<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-start justify-between gap-4">

        <div>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Educational Background
            </h3>

            <p class="mt-1 text-xs text-slate-500 max-w-md">
                Academic history and scholarship information submitted by the organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[11px] font-medium">

            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2 w-2 rounded-full
                    {{
                        $registration->high_school_name ||
                        $registration->grade_school_name ||
                        $registration->scholarship_name
                        ? 'bg-emerald-500'
                        : 'bg-slate-400'
                    }}">
                </span>
            </span>

            <span class="{{
                $registration->high_school_name ||
                $registration->grade_school_name ||
                $registration->scholarship_name
                ? 'text-emerald-700'
                : 'text-slate-500'
            }}">
                {{
                    $registration->high_school_name ||
                    $registration->grade_school_name ||
                    $registration->scholarship_name
                    ? 'Provided'
                    : 'None'
                }}
            </span>

        </div>

    </div>


    {{-- BODY --}}
    <div class="p-5 space-y-5 text-xs">

        <div class="grid xl:grid-cols-2 gap-4">

            {{-- High School --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    High School
                </div>

                <div class="space-y-2">

                    <div>
                        <div class="text-slate-400">School Name</div>
                        <div class="text-slate-900 font-medium">
                            {{ $registration->high_school_name ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Address</div>
                        <div class="text-slate-900">
                            {{ $registration->high_school_address ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Year Graduated</div>
                        <div class="text-slate-900">
                            {{ $registration->high_school_year_graduated ?: '—' }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- Grade School --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Grade School
                </div>

                <div class="space-y-2">

                    <div>
                        <div class="text-slate-400">School Name</div>
                        <div class="text-slate-900 font-medium">
                            {{ $registration->grade_school_name ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Address</div>
                        <div class="text-slate-900">
                            {{ $registration->grade_school_address ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Year Graduated</div>
                        <div class="text-slate-900">
                            {{ $registration->grade_school_year_graduated ?: '—' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Scholarship --}}
        <div class="border-t border-slate-200 pt-4">

            <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
                Scholarship
            </div>

            <div class="grid sm:grid-cols-2 gap-4">

                <div>
                    <div class="text-slate-400">Scholarship Name</div>
                    <div class="text-slate-900 font-medium">
                        {{ $registration->scholarship_name ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-400">Year Granted</div>
                    <div class="text-slate-900">
                        {{ $registration->scholarship_year_granted ?: '—' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>