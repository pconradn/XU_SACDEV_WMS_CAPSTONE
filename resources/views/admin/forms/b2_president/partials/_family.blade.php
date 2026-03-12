<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between">

        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Family Information
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Family and guardian details submitted by the organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2.5 w-2.5 rounded-full
                    {{
                        $registration->father_name ||
                        $registration->mother_name ||
                        $registration->guardian_name
                        ? 'bg-emerald-500'
                        : 'bg-slate-400'
                    }}">
                </span>
            </span>

            <span>
                {{
                    $registration->father_name ||
                    $registration->mother_name ||
                    $registration->guardian_name
                    ? 'Family info provided'
                    : 'No family info provided'
                }}
            </span>

        </div>

    </div>



    <div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-6">


        {{-- Father --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

            <div class="text-sm font-semibold text-slate-900 mb-3">
                Father
            </div>

            <div class="space-y-3">

                <div>
                    <div class="text-xs text-slate-500">Name</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->father_name ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Occupation</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->father_occupation ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Mobile</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->father_mobile ?: '—' }}
                    </div>
                </div>

            </div>

        </div>



        {{-- Mother --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

            <div class="text-sm font-semibold text-slate-900 mb-3">
                Mother
            </div>

            <div class="space-y-3">

                <div>
                    <div class="text-xs text-slate-500">Name</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->mother_name ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Occupation</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->mother_occupation ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Mobile</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->mother_mobile ?: '—' }}
                    </div>
                </div>

            </div>

        </div>



        {{-- Guardian --}}
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

            <div class="text-sm font-semibold text-slate-900 mb-3">
                Guardian
            </div>

            <div class="space-y-3">

                <div>
                    <div class="text-xs text-slate-500">Name</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->guardian_name ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Relationship</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->guardian_relationship ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Mobile</div>
                    <div class="text-sm text-slate-900 mt-1">
                        {{ $registration->guardian_mobile ?: '—' }}
                    </div>
                </div>

            </div>

        </div>


    </div>



    {{-- Siblings --}}
    <div class="mt-6">

        <div class="text-xs text-slate-500">
            Number of Siblings
        </div>

        <div class="mt-1 text-sm text-slate-900">
            {{ $registration->siblings_count !== null ? $registration->siblings_count : '—' }}
        </div>

    </div>

</div>