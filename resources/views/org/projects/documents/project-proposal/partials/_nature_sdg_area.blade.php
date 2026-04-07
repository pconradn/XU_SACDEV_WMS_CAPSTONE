<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= SECTION HEADER ================= --}}
        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Project Classification
            </h3>
            <p class="text-xs text-blue-700">
                Define engagement type, project nature, and alignment areas
            </p>
        </div>

        {{-- ================= NATURE OF ENGAGEMENT ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            {{-- MAIN LABEL --}}
            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Nature of Engagement
            </div>

            @php 
                $eng = old('engagement_type', $proposal->engagement_type ?? null); 
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">

                {{-- Radio Choices --}}
                <div class="md:col-span-7">
                    <div class="flex flex-wrap gap-4 text-sm text-slate-700">

                        @foreach (['organizer' => 'Organizer', 'partner' => 'Partner', 'participant' => 'Participant'] as $val => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio"
                                       name="engagement_type"
                                       value="{{ $val }}"
                                       class="border {{ $errors->has('engagement_type') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                       @checked($eng === $val)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach

                    </div>
                </div>

                {{-- Main Organizer --}}
                <div class="md:col-span-5" id="mainOrganizerWrap">
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        If participant, specify main organizer
                    </label>

                    <input type="text"
                        name="main_organizer"
                        value="{{ old('main_organizer', $proposal->main_organizer ?? '') }}"
                        class="w-full rounded-lg border px-3 py-2 text-sm 
                            {{ $errors->has('main_organizer') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                            focus:ring-2 focus:outline-none transition"
                        placeholder="Name of main organizer">
                </div>

            </div>
        </div>

        {{-- ================= NATURE OF PROJECT ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            {{-- MAIN LABEL --}}
            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Nature of Project
            </div>

            {{-- SUBTEXT --}}
            <div class="text-xs text-blue-700 mb-3">
                Select all that apply
            </div>

            @php
                $natureOptions = [
                    'assembly' => 'Assembly',
                    'convention' => 'Convention/Congress',
                    'contest' => 'Contest/Competition',
                    'film_showing' => 'Film Showing',
                    'outreach' => 'Outreach',
                    'lecture_seminar_workshop' => 'Lecture/Seminar/Workshop',
                    'fund_raising' => 'Fund Raising',
                    'other' => 'Other',
                ];

                $nature = old('project_nature');
                if (is_null($nature) && isset($proposal->project_nature)) {
                    $nature = explode(', ', $proposal->project_nature);
                }
                if (!is_array($nature)) $nature = [];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm text-slate-700">

                @foreach($natureOptions as $val => $label)

                    @if($val === 'other')
                        <div class="flex items-center gap-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                    name="project_nature[]"
                                    value="{{ $val }}"
                                    class="border {{ $errors->has('project_nature') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                    @checked(in_array($val, $nature, true))>
                                <span>{{ $label }}</span>
                            </label>

                            <input type="text"
                                name="project_nature_other"
                                value="{{ old('project_nature_other', $proposal->project_nature_other ?? '') }}"
                                class="rounded-lg border px-2 py-1 text-sm w-32 
                                    {{ $errors->has('project_nature_other') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                                    focus:ring-2 focus:outline-none transition"
                                placeholder="Specify">
                        </div>
                    @else
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                name="project_nature[]"
                                value="{{ $val }}"
                                class="border {{ $errors->has('project_nature') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                @checked(in_array($val, $nature, true))>
                            <span>{{ $label }}</span>
                        </label>
                    @endif

                @endforeach

            </div>
        </div>

        {{-- ================= SDG + AREA FOCUS ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                {{-- SDG --}}
                <div class="md:col-span-9">

                    <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                        Sustainable Development Goals (SDG)
                    </div>

                    <div class="text-xs text-blue-700 mb-3">
                        Select all applicable goals
                    </div>

                    @php
                        $sdgs = [
                            'No Poverty',
                            'Zero Hunger',
                            'Quality Education',
                            'Gender Equality',
                            'Clean Water and Sanitation',
                            'Affordable and Clean Energy',
                            'Decent Work and Economic Growth',
                            'Industry, Innovation and Infrastructure',
                            'Reduce Inequalities',
                            'Sustainable Cities and Communities',
                            'Responsible Consumption and Production',
                            'Climate Action',
                            'Life Below Water',
                            'Peace and Justice Strong institutions',
                            'Partnerships for the Goals',
                        ];

                        $sdg = old('sdg');
                        if (is_null($sdg) && isset($proposal->sdg)) {
                            $sdg = explode(', ', $proposal->sdg);
                        }
                        if (!is_array($sdg)) $sdg = [];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 text-sm text-slate-700">
                        @foreach($sdgs as $s)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       name="sdg[]"
                                       value="{{ $s }}"
                                       class="border {{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                       @checked(in_array($s, $sdg, true))>
                                <span>{{ $s }}</span>
                            </label>
                        @endforeach
                    </div>

                </div>

                {{-- AREA FOCUS --}}
                <div class="md:col-span-3">

                    <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                        Area Focus
                    </div>

                    <div class="text-xs text-blue-700 mb-3">
                        Select all that apply
                    </div>

                    @php
                        $af = old('area_focus');
                        if (is_null($af) && isset($proposal->area_focus)) {
                            $af = explode(', ', $proposal->area_focus);
                        }
                        if (!is_array($af)) $af = [];
                    @endphp

                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="area_focus[]"
                                   value="organizational_development"
                                   class="border {{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                   @checked(in_array('organizational_development', $af, true))>
                            <span>Organizational Development</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="area_focus[]"
                                   value="student_services"
                                   class="border {{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                   @checked(in_array('student_services', $af, true))>
                            <span>Student Services and Formation</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="area_focus[]"
                                   value="community_involvement"
                                   class="border {{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                   @checked(in_array('community_involvement', $af, true))>
                            <span>Community Involvement</span>
                        </label>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>