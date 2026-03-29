<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Target Benefactors
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Select the groups or entities you intend to solicit support from.
        </p>
    </div>


    @php
        $studentOrgs = old('target_student_orgs', $data->target_student_orgs ?? false);
        $xuOfficers = old('target_xu_officers', $data->target_xu_officers ?? false);
        $privateIndividuals = old('target_private_individuals', $data->target_private_individuals ?? false);
        $alumni = old('target_alumni', $data->target_alumni ?? false);
        $companies = old('target_private_companies', $data->target_private_companies ?? false);
        $others = old('target_others', $data->target_others ?? false);
        $othersText = old('target_others_specify', $data->target_others_specify ?? '');
    @endphp


    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- CARD ITEM --}}
        @foreach([
            'target_student_orgs' => ['label' => 'Student Organizations within XU', 'value' => $studentOrgs],
            'target_xu_officers' => ['label' => 'University Officers', 'value' => $xuOfficers],
            'target_private_individuals' => ['label' => 'Private Individuals / Relatives', 'value' => $privateIndividuals],
            'target_alumni' => ['label' => 'Alumni', 'value' => $alumni],
            'target_private_companies' => ['label' => 'Private Companies', 'value' => $companies],
        ] as $name => $item)

        <label class="flex items-center gap-3 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:bg-slate-50 transition">

            <input type="checkbox"
                   name="{{ $name }}"
                   value="1"
                   class="h-4 w-4 text-blue-600 border-slate-300 rounded"
                   {{ $item['value'] ? 'checked' : '' }}>

            <span class="text-sm text-slate-800">
                {{ $item['label'] }}
            </span>

        </label>

        @endforeach


        {{-- OTHERS --}}
        <div class="md:col-span-2 border border-slate-200 rounded-xl px-4 py-3">

            <label class="flex items-center gap-3">

                <input type="checkbox"
                       id="othersCheckbox"
                       name="target_others"
                       value="1"
                       class="h-4 w-4 text-blue-600 border-slate-300 rounded"
                       {{ $others ? 'checked' : '' }}>

                <span class="text-sm text-slate-800">
                    Others
                </span>

            </label>


            {{-- SPECIFY --}}
            <div class="mt-3">
                <input
                    type="text"
                    id="othersInput"
                    name="target_others_specify"
                    value="{{ $othersText }}"
                    placeholder="Please specify"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                    {{ !$others ? 'disabled' : '' }}>
            </div>

        </div>

    </div>

</div>