<div style="border:1px solid #000; border-top:none; margin-bottom:10px;">

    {{-- ================= NATURE OF PROJECT ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td colspan="3" style="border:1px solid #000; padding:6px;">
                <strong>Nature of Project:</strong>
            </td>
        </tr>

        @php
            $nature = $proposal->project_nature
                ? explode(', ', $proposal->project_nature)
                : [];

            $natureOther = $proposal->project_nature_other ?? null;

            function checked($val, $arr) {
                return in_array($val, $arr) ? '☑' : '☐';
            }
        @endphp

        <tr>
            <td style="border:1px solid #000; padding:6px;">
                {{ checked('assembly', $nature) }} Assembly<br>
                {{ checked('film_showing', $nature) }} Film Showing<br>
                {{ checked('lecture_seminar_workshop', $nature) }} Lecture/Seminar/Workshop
            </td>

            <td style="border:1px solid #000; padding:6px;">
                {{ checked('convention', $nature) }} Convention/Congress<br>
                {{ checked('outreach', $nature) }} Outreach<br>
                {{ checked('fund_raising', $nature) }} Fund Raising
            </td>

            <td style="border:1px solid #000; padding:6px;">
                {{ checked('contest', $nature) }} Contest/Competition<br>
                {{ checked('other', $nature) }} Others
                @if(in_array('other', $nature) && $natureOther)
                    — {{ $natureOther }}
                @endif
            </td>
        </tr>

    </table>


    {{-- ================= SDG ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td colspan="3" style="border:1px solid #000; padding:6px;">
                <strong>Target Sustainable Development Goal:</strong>
            </td>
        </tr>

        @php
            $sdg = $proposal->sdg
                ? explode(', ', $proposal->sdg)
                : [];

            function sdgCheck($val, $arr) {
                return in_array($val, $arr) ? '☑' : '☐';
            }
        @endphp

        <tr>
            <td style="border:1px solid #000; padding:6px;">
                {{ sdgCheck('No Poverty', $sdg) }} No Poverty<br>
                {{ sdgCheck('Affordable and Clean Energy', $sdg) }} Affordable and Clean Energy<br>
                {{ sdgCheck('Sustainable Cities and Communities', $sdg) }} Sustainable Cities and Communities<br>
                {{ sdgCheck('Life Below Water', $sdg) }} Life Below Water
            </td>

            <td style="border:1px solid #000; padding:6px;">
                {{ sdgCheck('Zero Hunger', $sdg) }} Zero Hunger<br>
                {{ sdgCheck('Decent Work and Economic Growth', $sdg) }} Decent Work and Economic Growth<br>
                {{ sdgCheck('Responsible Consumption and Production', $sdg) }} Responsible Consumption and Production<br>
                {{ sdgCheck('Peace and Justice Strong institutions', $sdg) }} Peace and Justice Strong institutions
            </td>

            <td style="border:1px solid #000; padding:6px;">
                {{ sdgCheck('Quality Education', $sdg) }} Quality Education<br>
                {{ sdgCheck('Industry, Innovation and Infrastructure', $sdg) }} Industry, Innovation and Infrastructure<br>
                {{ sdgCheck('Clean Water and Sanitation', $sdg) }} Clean Water and Sanitation<br>
                {{ sdgCheck('Gender Equality', $sdg) }} Gender Equality<br>
                {{ sdgCheck('Reduce Inequalities', $sdg) }} Reduce Inequalities<br>
                {{ sdgCheck('Climate Action', $sdg) }} Climate Action<br>
                {{ sdgCheck('Partnerships for the Goals', $sdg) }} Partnerships for the Goals
            </td>
        </tr>

    </table>


    {{-- ================= AREA FOCUS ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td colspan="3" style="border:1px solid #000; padding:6px;">
                <strong>Area Focus:</strong>
            </td>
        </tr>

        @php
            $af = $proposal->area_focus
                ? explode(', ', $proposal->area_focus)
                : [];
        @endphp

        <tr>
            <td style="border:1px solid #000; padding:6px;">
                {{ in_array('organizational_development', $af) ? '☑' : '☐' }}
                Organizational Development
            </td>

            <td style="border:1px solid #000; padding:6px;">
                {{ in_array('student_services', $af) ? '☑' : '☐' }}
                Student Services and Formation
            </td>

            <td style="border:1px solid #000; padding:6px;">
                {{ in_array('community_involvement', $af) ? '☑' : '☐' }}
                Community Involvement
            </td>
        </tr>

    </table>

</div>