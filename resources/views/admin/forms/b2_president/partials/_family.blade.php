<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-start justify-between gap-4">

        <div>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Family Information
            </h3>

            <p class="mt-1 text-xs text-slate-500 max-w-md">
                Family and guardian details submitted by the organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[11px] font-medium">

            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2 w-2 rounded-full
                    {{
                        $registration->father_name ||
                        $registration->mother_name ||
                        $registration->guardian_name
                        ? 'bg-emerald-500'
                        : 'bg-slate-400'
                    }}">
                </span>
            </span>

            <span class="{{
                $registration->father_name ||
                $registration->mother_name ||
                $registration->guardian_name
                ? 'text-emerald-700'
                : 'text-slate-500'
            }}">
                {{
                    $registration->father_name ||
                    $registration->mother_name ||
                    $registration->guardian_name
                    ? 'Provided'
                    : 'None'
                }}
            </span>

        </div>

    </div>


    {{-- BODY --}}
    <div class="p-5 space-y-5">

        <div class="grid xl:grid-cols-3 gap-4 text-xs">

            {{-- Father --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Father
                </div>

                <div class="space-y-2">

                    <div>
                        <div class="text-slate-400">Name</div>
                        <div class="text-slate-900 font-medium">
                            {{ $registration->father_name ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Occupation</div>
                        <div class="text-slate-900">
                            {{ $registration->father_occupation ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Mobile</div>
                        <div class="text-slate-900">
                            {{ $registration->father_mobile ?: '—' }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- Mother --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Mother
                </div>

                <div class="space-y-2">

                    <div>
                        <div class="text-slate-400">Name</div>
                        <div class="text-slate-900 font-medium">
                            {{ $registration->mother_name ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Occupation</div>
                        <div class="text-slate-900">
                            {{ $registration->mother_occupation ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Mobile</div>
                        <div class="text-slate-900">
                            {{ $registration->mother_mobile ?: '—' }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- Guardian --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-3">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                    Guardian
                </div>

                <div class="space-y-2">

                    <div>
                        <div class="text-slate-400">Name</div>
                        <div class="text-slate-900 font-medium">
                            {{ $registration->guardian_name ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Relationship</div>
                        <div class="text-slate-900">
                            {{ $registration->guardian_relationship ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-slate-400">Mobile</div>
                        <div class="text-slate-900">
                            {{ $registration->guardian_mobile ?: '—' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- SIBLINGS --}}
        <div class="border-t border-slate-200 pt-4 text-xs">

            <div class="text-slate-400">Number of Siblings</div>

            <div class="mt-1 text-slate-900 font-medium">
                {{ $registration->siblings_count !== null ? $registration->siblings_count : '—' }}
            </div>

        </div>

    </div>

</div>