<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- ACCENT --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Target Benefactors
                </h3>
                <p class="text-xs text-blue-700 mt-1">
                    Select the groups or entities you intend to solicit support from.
                </p>
            </div>
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

        {{-- GROUP --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4">

            <div class="flex items-center gap-2">
                <i data-lucide="target" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Benefactor Groups
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                @foreach([
                    'target_student_orgs' => ['label' => 'Student Organizations within XU', 'value' => $studentOrgs],
                    'target_xu_officers' => ['label' => 'University Officers', 'value' => $xuOfficers],
                    'target_private_individuals' => ['label' => 'Private Individuals / Relatives', 'value' => $privateIndividuals],
                    'target_alumni' => ['label' => 'Alumni', 'value' => $alumni],
                    'target_private_companies' => ['label' => 'Private Companies', 'value' => $companies],
                ] as $name => $item)

                <label class="flex items-center gap-3 rounded-xl px-3 py-2 cursor-pointer transition border
                    {{ $errors->has($name)
                        ? 'border-rose-500 bg-rose-50'
                        : 'border-slate-200 hover:bg-slate-50' }}">

                    <input type="checkbox"
                        name="{{ $name }}"
                        value="1"
                        class="h-3.5 w-3.5 text-blue-600 border-slate-300 rounded"
                        {{ $item['value'] ? 'checked' : '' }}>

                    <span class="text-xs text-slate-800">
                        {{ $item['label'] }}
                    </span>

                </label>

                @endforeach

                {{-- OTHERS --}}
                <div class="md:col-span-2 rounded-xl px-3 py-3 border
                    {{ $errors->has('target_others') || $errors->has('target_others_specify')
                        ? 'border-rose-500 bg-rose-50'
                        : 'border-slate-200 bg-white' }}">

                    <label class="flex items-center gap-3">

                        <input type="checkbox"
                            id="othersCheckbox"
                            name="target_others"
                            value="1"
                            class="h-3.5 w-3.5 text-blue-600 border-slate-300 rounded"
                            {{ $others ? 'checked' : '' }}>

                        <span class="text-xs text-slate-800 font-medium">
                            Others
                        </span>

                    </label>

                    <div class="mt-3">
                        <input
                            type="text"
                            id="othersInput"
                            name="target_others_specify"
                            value="{{ $othersText }}"
                            placeholder="Specify other benefactors"
                            class="w-full rounded-lg px-3 py-2 text-xs
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