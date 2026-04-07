<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= SECTION HEADER ================= --}}
        <div class="flex flex-col">
            <h3 class="text-sm font-semibold text-slate-900">
                Target Benefactors
            </h3>
            <p class="text-xs text-blue-700">
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

        {{-- ================= BENEFICIARIES ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Benefactor Groups
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                @foreach([
                    'target_student_orgs' => ['label' => 'Student Organizations within XU', 'value' => $studentOrgs],
                    'target_xu_officers' => ['label' => 'University Officers', 'value' => $xuOfficers],
                    'target_private_individuals' => ['label' => 'Private Individuals / Relatives', 'value' => $privateIndividuals],
                    'target_alumni' => ['label' => 'Alumni', 'value' => $alumni],
                    'target_private_companies' => ['label' => 'Private Companies', 'value' => $companies],
                ] as $name => $item)

                <label class="flex items-center gap-3 border rounded-xl px-4 py-3 cursor-pointer transition
                    {{ $errors->has($name)
                        ? 'border-rose-500 bg-rose-50'
                        : 'border-slate-200 hover:bg-slate-50' }}">

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
                <div class="md:col-span-2 border rounded-xl px-4 py-3
                    {{ $errors->has('target_others') || $errors->has('target_others_specify')
                        ? 'border-rose-500 bg-rose-50'
                        : 'border-slate-200' }}">

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
                            placeholder="Specify other benefactors"
                            class="w-full rounded-lg px-3 py-2 text-sm
                                {{ $errors->has('target_others_specify')
                                    ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                    : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                                focus:ring-2 focus:outline-none transition"
                            {{ !$others ? 'disabled' : '' }}>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>