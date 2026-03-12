<div class="border border-slate-300">

    {{-- Row 1: Nature of Engagement --}}
    <div class="px-4 pt-2 pb-3">

        <div class="flex items-start justify-between gap-4">
            <div class="text-[12px] font-medium text-slate-700">
                Nature of Engagement:
            </div>
        </div>

        @php 
            $eng = old('engagement_type', $proposal->engagement_type ?? null); 
        @endphp

        <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-12 items-start">

            {{-- Choices (same row) --}}
            <div class="md:col-span-7">
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-[10px] text-slate-700">
                    @foreach (['organizer' => 'Organizer', 'partner' => 'Partner', 'participant' => 'Participant'] as $val => $label)
                        <label class="flex items-center gap-2">
                            <input type="radio"
                                   name="engagement_type"
                                   value="{{ $val }}"
                                   class="border-slate-300"
                                   @checked($eng === $val)>
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Participant box (right side, same row) --}}
            <div class="md:col-span-5" id="mainOrganizerWrap">
                <label class="block text-[10px] font-medium text-blue-900 italic">
                    If participant, state the main organizer:
                </label>

                <input type="text"
                       name="main_organizer"
                       value="{{ old('main_organizer', $proposal->main_organizer ?? '') }}"
                       class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[10px]"
                       placeholder="Name of main organizer">
            </div>

        </div>

    </div>

    <div class="border-t border-slate-300"></div>

    {{-- Row 2: Nature of Project (multiple) --}}
    <div class="px-4 pt-2 pb-3">

        <div class="text-[12px] font-medium text-slate-700">
            Nature of Project:
            <span class="text-[10px] text-slate-500">(check all that apply)</span>
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

        <div class="mt-2 grid grid-cols-1 gap-1 md:grid-cols-4 text-[10px] text-slate-700">
            @foreach($natureOptions as $val => $label)

                @if($val === 'other')
                    <div class="flex items-center gap-2 ">
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                name="project_nature[]"
                                value="{{ $val }}"
                                class="border border-slate-300 bg-white px-2 py-0 text-[9px]"
                                @checked(in_array($val, $nature, true))>
                            {{ $label }}
                        </label>

                        <input type="text"
                            name="project_nature_other"
                            value="{{ old('project_nature_other', $proposal->project_nature_other ?? '') }}"
                            class="border border-slate-300 bg-white px-2 py-1 text-[10px] w-32"
                            placeholder="Specify">
                    </div>
                @else
                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                            name="project_nature[]"
                            value="{{ $val }}"
                            class="border-slate-300"
                            @checked(in_array($val, $nature, true))>
                        {{ $label }}
                    </label>
                @endif

            @endforeach
        </div>

    </div>

    <div class="border-t border-slate-300"></div>

    {{-- Row 3: SDG (multiple) + Area Focus (multiple) --}}
    <div class="px-4 pt-2 pb-4">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-12 items-start">

            {{-- SDG (multiple) --}}
            <div class="md:col-span-9">
                <div class="text-[12px] font-medium text-slate-700">
                    Target Sustainable Development Goal(s):
                    <span class="text-[10px] text-slate-500">(check all that apply)</span>
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

                <div class="mt-2 grid grid-cols-1 gap-2 md:grid-cols-3 text-[10px] text-slate-700">
                    @foreach($sdgs as $s)
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="sdg[]"
                                   value="{{ $s }}"
                                   class="border-slate-300"
                                   @checked(in_array($s, $sdg, true))>
                            {{ $s }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Area Focus (multiple) --}}
            <div class="md:col-span-3">
                <div class="text-[12px] font-medium text-slate-700">
                    Area Focus:
                    <span class="text-[10px] text-slate-500">(check all that apply)</span>
                </div>

                @php
                    $af = old('area_focus');
                    if (is_null($af) && isset($proposal->area_focus)) {
                        $af = explode(', ', $proposal->area_focus);
                    }
                    if (!is_array($af)) $af = [];
                @endphp

                <div class="mt-2 space-y-2 text-[10px] text-slate-700">
                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               name="area_focus[]"
                               value="organizational_development"
                               class="border-slate-300"
                               @checked(in_array('organizational_development', $af, true))>
                        Organizational Development
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               name="area_focus[]"
                               value="student_services"
                               class="border-slate-300"
                               @checked(in_array('student_services', $af, true))>
                        Student Services and Formation
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox"
                               name="area_focus[]"
                               value="community_involvement"
                               class="border-slate-300"
                               @checked(in_array('community_involvement', $af, true))>
                        Community Involvement
                    </label>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.nature-checkbox');
    const otherWrap = document.getElementById('natureOtherWrap');

    function toggleOther() {
        const otherChecked = document.querySelector('input[name="project_nature[]"][value="other"]').checked;
        otherWrap.classList.toggle('hidden', !otherChecked);

        if (!otherChecked) {
            otherWrap.querySelector('input').value = '';
        }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', toggleOther));
});
</script>