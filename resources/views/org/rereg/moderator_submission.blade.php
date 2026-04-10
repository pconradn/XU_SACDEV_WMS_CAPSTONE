<x-app-layout>

@php
    $isAdminView = $isAdminView ?? false;

    
@endphp

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-5">

        {{-- HEADER --}}
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                Moderator Submission
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                @if($isAdminView)
                    Admin viewing mode. This page is read-only.
                @else
                    Organization members can view. Only the assigned moderator can edit.
                @endif
            </p>
        </div>


        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 text-sm">
                {{ session('success') }}
            </div>
        @endif


        {{-- PROFILE INCOMPLETE WARNING --}}
        @if(!$isProfileComplete && $isModerator)
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-4 text-rose-900">
                <div class="text-sm font-semibold">Profile Incomplete</div>

                <div class="mt-2 text-xs">
                    Please complete the following fields before submitting:
                </div>

                <ul class="mt-2 list-disc ml-5 text-xs space-y-1">
                    @foreach($missingFields as $field)
                        <li>{{ $field }}</li>
                    @endforeach
                </ul>

                @if($isAdminView)
                    <a href="{{ route('admin.profile.view', ['user' => $profile->user_id]) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                        View Moderator Profile
                    </a>
                @elseif($isModerator)
                    <a href="{{ route('org.profile.edit') }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                        Open Profile
                    </a>
                @else
                    <a href="{{ route('a.profile.edit', ['user' => $profile->user_id]) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                        Open Moderator Profile
                    </a>
                @endif
            </div>
        @endif


        {{-- PROFILE SUMMARY --}}
        <div class="card p-4">
            <div class="card-header">Profile Summary</div>

            <div class="mt-3 flex items-center gap-4">

                <div class="w-16 h-16 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 flex items-center justify-center">
                    @if($profile->photo_id_path)
                        <img src="{{ asset('storage/'.$profile->photo_id_path) }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-[10px] text-slate-400">No Photo</span>
                    @endif
                </div>

                <div class="flex-1">
                    <div class="text-sm font-medium text-slate-900">
                        {{ $profile->full_name ?? '—' }}
                    </div>
                    <div class="text-[11px] text-slate-500">
                        {{ $profile->email ?? '—' }}
                    </div>
                </div>

            </div>

            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">

                <div>
                    <div class="text-[11px] text-slate-500">Name</div>
                    <div class="font-medium">{{ $profile->full_name ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Email</div>
                    <div class="font-medium">{{ $profile->email ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Mobile</div>
                    <div class="font-medium">{{ $profile->mobile_number ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">City Address</div>
                    <div class="font-medium">{{ $profile->city_address ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Designation</div>
                    <div class="font-medium">{{ $profile->university_designation ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Department</div>
                    <div class="font-medium">{{ $profile->unit_department ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Employment</div>
                    <div class="font-medium">{{ $profile->employment_status ?? '—' }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-slate-500">Years of Service</div>
                    <div class="font-medium">{{ $profile->years_of_service ?? '—' }}</div>
                </div>

            </div>
        </div>

        <div class="mt-4 flex justify-end">

            @if($isModerator)
                <a href="{{ route('org.profile.edit') }}"
                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                    Open Profile
                </a>

            @elseif($isAdminView)
                <a href="{{ route('admin.profile.view', ['user' => $profile->user_id]) }}"
                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                    View Moderator Profile
                </a>
            @else
                <a href="{{ route('org.profile.edit', ['user' => $profile->user_id]) }}"
                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                    Open Moderator Profile
                </a>
            @endif

        </div>


        {{-- FORM --}}
        <form method="POST" action="{{ route('org.rereg.moderator.update') }}">
            @csrf

            <div class="card p-4 space-y-4">

                <div class="card-header">Moderator Details</div>

                {{-- WAS MODERATOR BEFORE --}}
                <div>
                    <label class="text-xs text-slate-600">Have you been a moderator before?</label>

                    <select name="was_moderator_before"
                            class="mt-1 w-full rounded-lg border border-slate-200 text-sm"
                            @disabled($isAdminView || !$isModerator || !$isProfileComplete)>
                        <option value="">Select</option>
                        <option value="1" @selected(old('was_moderator_before', $submission?->was_moderator_before) == 1)>Yes</option>
                        <option value="0" @selected(old('was_moderator_before', $submission?->was_moderator_before) == 0)>No</option>
                    </select>
                </div>


                {{-- ORG NAME --}}
                <div>
                    <label class="text-xs text-slate-600">If yes, which organization?</label>

                    <input type="text"
                           name="moderated_org_name"
                           value="{{ old('moderated_org_name', $submission?->moderated_org_name) }}"
                           class="mt-1 w-full rounded-lg border border-slate-200 text-sm"
                           @disabled($isAdminView || !$isModerator || !$isProfileComplete)>
                </div>


                {{-- SERVED BEFORE --}}
                <div>
                    <label class="text-xs text-slate-600">Served this nominating organization before?</label>

                    <select name="served_nominating_org_before"
                            class="mt-1 w-full rounded-lg border border-slate-200 text-sm"
                            @disabled($isAdminView || !$isModerator || !$isProfileComplete)>
                        <option value="">Select</option>
                        <option value="1" @selected(old('served_nominating_org_before', $submission?->served_nominating_org_before) == 1)>Yes</option>
                        <option value="0" @selected(old('served_nominating_org_before', $submission?->served_nominating_org_before) == 0)>No</option>
                    </select>
                </div>


                {{-- YEARS --}}
                <div>
                    <label class="text-xs text-slate-600">If yes, how many years?</label>

                    <input type="number"
                           name="served_nominating_org_years"
                           value="{{ old('served_nominating_org_years', $submission?->served_nominating_org_years) }}"
                           class="mt-1 w-full rounded-lg border border-slate-200 text-sm"
                           @disabled($isAdminView || !$isModerator || !$isProfileComplete)>
                </div>

            </div>


            {{-- ACTION --}}
            @if($isModerator && !$isAdminView)
                <div class="flex justify-end mt-4">
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm hover:bg-slate-800"
                            @disabled(!$isProfileComplete)>
                        Save Submission
                    </button>
                </div>
            @endif

        </form>

    </div>

</x-app-layout>