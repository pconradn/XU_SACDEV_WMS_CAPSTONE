<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
     x-data="{
        wasModeratorBefore: {{ old('was_moderator_before', $submission->was_moderator_before) ? 'true' : 'false' }},
        servedNominatingOrg: {{ old('served_nominating_org_before', $submission->served_nominating_org_before) ? 'true' : 'false' }}
     }">

    <h3 class="text-base font-semibold text-slate-900">
        Moderator Background
    </h3>

    <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-5">


        {{-- Question 1 --}}
        <div class="rounded-lg border border-slate-200 p-4">

            <label class="flex items-start gap-3 cursor-pointer">

                <input type="checkbox"
                       name="was_moderator_before"
                       value="1"
                       x-model="wasModeratorBefore"
                       class="mt-0.5 h-4 w-4 rounded border-slate-300"
                       {{ $isLocked ? 'disabled' : '' }}>

                <span class="text-sm text-slate-800">
                    Have you been moderator of a student organization before?
                </span>

            </label>


            <div class="mt-3">

                <label class="block text-sm font-medium text-slate-700">
                    Organization Name
                </label>

                <input type="text"
                       name="moderated_org_name"
                       value="{{ old('moderated_org_name', $submission->moderated_org_name) }}"
                       x-bind:required="wasModeratorBefore"
                       x-bind:disabled="!wasModeratorBefore"
                       x-bind:class="!wasModeratorBefore ? 'bg-slate-100 cursor-not-allowed' : ''"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       {{ $isLocked ? 'disabled' : '' }}>

                <p class="text-xs text-slate-500 mt-1"
                   x-show="wasModeratorBefore">
                    Required if checkbox is selected
                </p>

            </div>

        </div>



        {{-- Question 2 --}}
        <div class="rounded-lg border border-slate-200 p-4">

            <label class="flex items-start gap-3 cursor-pointer">

                <input type="checkbox"
                       name="served_nominating_org_before"
                       value="1"
                       x-model="servedNominatingOrg"
                       class="mt-0.5 h-4 w-4 rounded border-slate-300"
                       {{ $isLocked ? 'disabled' : '' }}>

                <span class="text-sm text-slate-800">
                    Have you served as moderator of the nominating organization?
                </span>

            </label>


            <div class="mt-3">

                <label class="block text-sm font-medium text-slate-700">
                    Number of Years
                </label>

                <input type="number"
                       min="0"
                       max="80"
                       name="served_nominating_org_years"
                       value="{{ old('served_nominating_org_years', $submission->served_nominating_org_years) }}"
                       x-bind:required="servedNominatingOrg"
                       x-bind:disabled="!servedNominatingOrg"
                       x-bind:class="!servedNominatingOrg ? 'bg-slate-100 cursor-not-allowed' : ''"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       {{ $isLocked ? 'disabled' : '' }}>

                <p class="text-xs text-slate-500 mt-1"
                   x-show="servedNominatingOrg">
                    Required if checkbox is selected
                </p>

            </div>

        </div>


    </div>

</div>