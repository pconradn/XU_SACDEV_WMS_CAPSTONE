<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>

    <div class="p-4 space-y-5">

        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600">
                <i data-lucide="layers" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Project Classification
                </h3>
                <p class="text-[11px] text-slate-500">
                    Define engagement type, project nature, and alignment areas
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4">

            <div class="flex items-center gap-2">
                <i data-lucide="users" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Nature of Engagement
                </span>
            </div>

            @php 
                $eng = old('engagement_type', $proposal->engagement_type ?? null); 
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">

                <div class="md:col-span-7 flex flex-wrap gap-2">

                    @foreach (['organizer' => 'Organizer', 'partner' => 'Partner', 'participant' => 'Participant'] as $val => $label)
                        <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs hover:bg-slate-100 transition">
                            <input type="radio"
                                   name="engagement_type"
                                   value="{{ $val }}"
                                   required
                                   class="{{ $errors->has('engagement_type') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                   @checked($eng === $val)>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach

                </div>

                <div class="md:col-span-5">
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        If participant, specify main organizer
                    </label>

                    <input type="text"
                        name="main_organizer"
                        value="{{ old('main_organizer', $proposal->main_organizer ?? '') }}"
                        class="w-full rounded-lg border px-3 py-2 text-xs 
                        {{ $errors->has('main_organizer') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                        focus:ring-2 focus:outline-none transition"
                        placeholder="Name of main organizer">
                </div>

            </div>

        </div>

        <div id="natureWrapper"
            class="rounded-xl border border-slate-200 bg-white p-4 space-y-4">

            <div class="flex items-center gap-2">
                <i data-lucide="tag" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Nature of Project
                </span>
            </div>

            <div class="text-[11px] text-slate-500">
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 text-xs text-slate-700">

                @foreach($natureOptions as $val => $label)

                    @if($val === 'other')
                        <div class="flex items-center gap-2">
                            <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 hover:bg-slate-100 transition">
                                <input type="checkbox"
                                    name="project_nature[]"
                                    value="{{ $val }}"
                                    class="{{ $errors->has('project_nature') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                    @checked(in_array($val, $nature, true))>
                                <span>{{ $label }}</span>
                            </label>

                            <input type="text"
                                name="project_nature_other"
                                value="{{ old('project_nature_other', $proposal->project_nature_other ?? '') }}"
                                class="rounded-lg border px-2 py-1 text-xs w-28 
                                {{ $errors->has('project_nature_other') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                                focus:ring-2 focus:outline-none transition"
                                placeholder="Specify">
                        </div>
                    @else
                        <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 hover:bg-slate-100 transition">
                            <input type="checkbox"
                                name="project_nature[]"
                                value="{{ $val }}"
                                class="{{ $errors->has('project_nature') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                @checked(in_array($val, $nature, true))>
                            <span>{{ $label }}</span>
                        </label>
                    @endif

                @endforeach

            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                <div id="sdgWrapper"
                    class="md:col-span-9 space-y-3
                    {{ $errors->has('sdg') ? 'border border-rose-500 ring-2 ring-rose-300 rounded-xl p-2' : '' }}">

                    <div class="flex items-center gap-2">
                        <i data-lucide="globe" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                            Sustainable Development Goals
                        </span>
                    </div>

                    <div class="text-[11px] text-slate-500">
                        Select all applicable goals
                    </div>

                    @php
                        $sdgs = [
                            'No Poverty','Zero Hunger','Quality Education','Gender Equality',
                            'Clean Water and Sanitation','Affordable and Clean Energy',
                            'Decent Work and Economic Growth','Industry, Innovation and Infrastructure',
                            'Reduce Inequalities','Sustainable Cities and Communities',
                            'Responsible Consumption and Production','Climate Action',
                            'Life Below Water','Peace and Justice Strong institutions',
                            'Partnerships for the Goals',
                        ];

                    $sdg = old('sdg');
                    if (is_null($sdg) && isset($proposal->sdg)) {
                        $sdg = explode('|', $proposal->sdg);
                    }
                    if (!is_array($sdg)) $sdg = [];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 text-xs text-slate-700">
                        @foreach($sdgs as $s)
                            <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 hover:bg-slate-100 transition">
                                <input type="checkbox"
                                       name="sdg[]"
                                       value="{{ $s }}"
                                       class="{{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                       @checked(in_array($s, $sdg, true))>
                                <span>{{ $s }}</span>
                            </label>
                        @endforeach
                    </div>

                </div>

                <div class="md:col-span-3 space-y-3
                    {{ $errors->has('area_focus') ? 'border border-rose-500 ring-2 ring-rose-300 rounded-xl p-3' : '' }}">
                    <div class="flex items-center gap-2">
                        <i data-lucide="target" class="w-3.5 h-3.5 text-blue-600"></i>
                        <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                            Area Focus
                        </span>
                    </div>

                    @php
                        $af = old('area_focus');
                        if (is_null($af) && isset($proposal->area_focus)) {
                            $af = explode(', ', $proposal->area_focus);
                        }
                        if (!is_array($af)) $af = [];
                    @endphp

                    <div class="space-y-2 text-xs text-slate-700">

                        <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 hover:bg-slate-100 transition">
                            <input type="checkbox"
                                   name="area_focus[]"
                                   value="organizational_development"
                                   class="{{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                   @checked(in_array('organizational_development', $af, true))>
                            <span>Organizational Development</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 hover:bg-slate-100 transition">
                            <input type="checkbox"
                                   name="area_focus[]"
                                   value="student_services"
                                   class="{{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                   @checked(in_array('student_services', $af, true))>
                            <span>Student Services and Formation</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 hover:bg-slate-100 transition">
                            <input type="checkbox"
                                   name="area_focus[]"
                                   value="community_involvement"
                                   class="{{ $errors->has('area_focus') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                   @checked(in_array('community_involvement', $af, true))>
                            <span>Community Involvement</span>
                        </label>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>