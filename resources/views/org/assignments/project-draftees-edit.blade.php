<x-app-layout>

<div class="mx-auto max-w-5xl px-4 py-6 space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">
        <h1 class="text-sm font-semibold text-slate-900">
            Assign Draftees
        </h1>
        <p class="text-xs text-slate-500 mt-1">
            Select up to 3 users who can help draft documents.
        </p>

        <div class="mt-3 text-xs text-slate-600">
            <span class="font-semibold">Project:</span> {{ $project->title }}
        </div>
    </div>

    @php
        $selectedOfficerIds = [];
        $selectedMemberIds = [];
        $assignedUsers = [];

        foreach($currentDraftees as $uid){
            $user = \App\Models\User::find($uid);
            if($user) $assignedUsers[] = $user;

            $off = \App\Models\OfficerEntry::where('user_id', $uid)->first();
            if($off) $selectedOfficerIds[] = $off->id;

            $mem = \App\Models\OrganizationMemberRecord::where('user_id', $uid)->first();
            if($mem) $selectedMemberIds[] = $mem->id;
        }
    @endphp

    {{-- CURRENT DRAFTEES --}}
    @if(count($assignedUsers))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
        <div class="text-xs font-semibold text-emerald-800 mb-2">
            Current Draftees
        </div>

        <ul class="text-sm text-emerald-700 space-y-1">
            @foreach($assignedUsers as $u)
                <li>
                    {{ $u->name ?? $u->email }}
                </li>
            @endforeach
        </ul>
    </div>
    @endif


    {{-- FORM --}}
    <form method="POST" action="{{ route('org.projects.assign-draftees.update', $project) }}">
        @csrf

        <div class="space-y-4">

            @for($i = 0; $i < 3; $i++)
                <div class="rounded-xl border p-4 space-y-3">

                    <div class="text-xs font-semibold text-slate-500">
                        Draftee {{ $i + 1 }}
                    </div>

                    <div x-data="{ type: 'officer', selectedDept: '' }">

                        {{-- TYPE --}}
                        <div class="flex gap-2 mb-2">
                            <button type="button"
                                @click="type='officer'"
                                :class="type==='officer' ? 'bg-indigo-600 text-white' : 'border'"
                                class="px-3 py-1 text-xs rounded">
                                Officer
                            </button>

                            <button type="button"
                                @click="type='member'"
                                :class="type==='member' ? 'bg-indigo-600 text-white' : 'border'"
                                class="px-3 py-1 text-xs rounded">
                                Member
                            </button>
                        </div>

                        <input type="hidden" name="draftees[{{ $i }}][type]" :value="type">

                        {{-- OFFICER --}}
                        <div x-show="type==='officer'">
                            <select name="draftees[{{ $i }}][id]" class="w-full border rounded p-2 text-sm">

                                <option value="">Select officer...</option>

                                @foreach($officers as $o)
                                    <option value="{{ $o->id }}"
                                        {{ in_array($o->id, $selectedOfficerIds) ? 'selected' : '' }}>
                                        {{ $o->full_name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- MEMBER --}}
                        <div x-show="type==='member'" class="space-y-2">

                            <select x-model="selectedDept" class="w-full border rounded p-2 text-sm">
                                <option value="">Select department...</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>

                            <select name="draftees[{{ $i }}][id]" class="w-full border rounded p-2 text-sm">

                                <option value="">Select member...</option>

                                @foreach($members as $m)
                                    <option value="{{ $m->id }}"
                                        x-bind:hidden="selectedDept && selectedDept != '{{ $m->department_id }}'"
                                        {{ in_array($m->id, $selectedMemberIds) ? 'selected' : '' }}>
                                        {{ $m->full_name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                </div>
            @endfor

        </div>


        {{-- ACTION --}}
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="px-4 py-2 text-xs border rounded">
                Cancel
            </a>

            <button class="px-4 py-2 text-xs bg-indigo-600 text-white rounded">
                Save
            </button>
        </div>
    </form>

</div>

</x-app-layout>