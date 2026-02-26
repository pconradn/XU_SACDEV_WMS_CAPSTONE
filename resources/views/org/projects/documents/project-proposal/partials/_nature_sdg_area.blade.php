<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Nature of Engagement --}}
        <div>
            <div class="text-sm font-semibold text-slate-900">Nature of Engagement</div>
            <div class="mt-3 space-y-2">
                @php $eng = old('engagement_type'); @endphp

                @foreach (['organizer' => 'Organizer', 'partner' => 'Partner', 'participant' => 'Participant'] as $val => $label)
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="radio" name="engagement_type" value="{{ $val }}"
                               class="rounded border-slate-300"
                               @checked($eng === $val)>
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <div class="mt-4" id="mainOrganizerWrap">
                <label class="block text-sm font-medium text-slate-700">
                    If participant, state the main organizer
                </label>
                <input type="text" name="main_organizer" value="{{ old('main_organizer') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                       placeholder="Name of main organizer">
            </div>
        </div>

        {{-- Nature of Project --}}
        <div>
            <div class="text-sm font-semibold text-slate-900">Nature of Project</div>

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
            @endphp

            <select name="project_nature"
                    id="projectNature"
                    class="mt-3 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                    required>
                <option value="" disabled @selected(!$nature)>Select...</option>
                @foreach($natureOptions as $val => $label)
                    <option value="{{ $val }}" @selected($nature === $val)>{{ $label }}</option>
                @endforeach
            </select>

            <div class="mt-4 hidden" id="natureOtherWrap">
                <label class="block text-sm font-medium text-slate-700">If other, specify</label>
                <input type="text" name="project_nature_other" value="{{ old('project_nature_other') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                       placeholder="Specify other nature">
            </div>
        </div>
    </div>

    <div class="mt-6 border-t border-slate-200 pt-5 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- SDG --}}
        <div>
            <div class="text-sm font-semibold text-slate-900">Target Sustainable Development Goal</div>
            @php
                $sdgs = [
                    'No Poverty',
                    'Affordable and Clean Energy',
                    'Sustainable Cities and Communities',
                    'Life Below Water',
                    'Zero Hunger',
                    'Decent Work and Economic Growth',
                    'Responsible Consumption and Production',
                    'Peace and Justice Strong institutions',
                    'Quality Education',
                    'Industry, Innovation and Infrastructure',
                    'Clean Water and Sanitation',
                    'Gender Equality',
                    'Reduce Inequalities',
                    'Climate Action',
                    'Partnerships for the Goals',
                ];
                $sdg = old('sdg');
            @endphp

            <select name="sdg"
                    class="mt-3 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                    required>
                <option value="" disabled @selected(!$sdg)>Select...</option>
                @foreach($sdgs as $s)
                    <option value="{{ $s }}" @selected($sdg === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>

        {{-- Area focus --}}
        <div>
            <div class="text-sm font-semibold text-slate-900">Area Focus</div>
            @php $af = old('area_focus'); @endphp

            <div class="mt-3 space-y-2">
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="area_focus" value="organizational_development"
                           class="rounded border-slate-300"
                           @checked($af === 'organizational_development')>
                    Organizational Development
                </label>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="area_focus" value="student_services"
                           class="rounded border-slate-300"
                           @checked($af === 'student_services')>
                    Student Services and Formation
                </label>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="area_focus" value="community_involvement"
                           class="rounded border-slate-300"
                           @checked($af === 'community_involvement')>
                    Community Involvement
                </label>
            </div>
        </div>

    </div>
</div>