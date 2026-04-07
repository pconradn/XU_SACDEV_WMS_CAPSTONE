@php
    $photoUrl = $submission->photo_id_path 
        ? asset('storage/' . $submission->photo_id_path) 
        : null;
@endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- ================= HEADER ================= --}}
    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-4">

        <div class="flex items-center gap-4">

            {{-- PHOTO --}}
            <div class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
                @else
                    <span class="text-[10px] text-slate-400">No Photo</span>
                @endif
            </div>

            {{-- NAME BLOCK --}}
            <div class="space-y-1">
                <div class="text-sm font-semibold text-slate-900">
                    {{ $submission->full_name ?? '—' }}
                </div>

                <div class="text-xs text-slate-500">
                    {{ $submission->university_designation ?? '—' }}
                </div>

                @if($submission->email)
                    <a href="mailto:{{ $submission->email }}"
                       class="text-xs text-blue-600 hover:underline">
                        {{ $submission->email }}
                    </a>
                @endif
            </div>
        </div>

        {{-- STATUS --}}
        <div class="flex flex-col items-end gap-1">

            @if($submission->employment_status)
                <span class="px-3 py-1 text-[10px] rounded-full 
                    bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    {{ ucfirst($submission->employment_status) }}
                </span>
            @endif

            <span class="text-[10px] text-slate-400">
                ID Submission
            </span>
        </div>

    </div>


    {{-- ================= BODY ================= --}}
    <div class="divide-y divide-slate-100">

        {{-- ================= PERSONAL ================= --}}
        <div class="px-5 py-4">

            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                    Personal Information
                </h3>
            </div>

            <div class="grid sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Birthday</span>
                    <span class="text-slate-900 font-medium">
                        {{ $submission->birthday ? $submission->birthday->format('M d, Y') : '—' }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Age</span>
                    <span class="text-slate-900 font-medium">{{ $submission->age ?? '—' }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Sex</span>
                    <span class="text-slate-900 font-medium">{{ $submission->sex ?? '—' }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Religion</span>
                    <span class="text-slate-900 font-medium">{{ $submission->religion ?? '—' }}</span>
                </div>

            </div>

        </div>


        {{-- ================= EMPLOYMENT ================= --}}
        <div class="px-5 py-4">

            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="briefcase" class="w-4 h-4 text-slate-400"></i>
                <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                    Employment Information
                </h3>
            </div>

            <div class="grid sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Designation</span>
                    <span class="text-slate-900 font-medium">
                        {{ $submission->university_designation ?? '—' }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Department</span>
                    <span class="text-slate-900 font-medium">
                        {{ $submission->unit_department ?? '—' }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Years of Service</span>
                    <span class="text-slate-900 font-medium">
                        {{ $submission->years_of_service ?? '—' }}
                    </span>
                </div>

            </div>

        </div>


        {{-- ================= CONTACT ================= --}}
        <div class="px-5 py-4">

            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                    Contact Information
                </h3>
            </div>

            <div class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">

                {{-- LEFT --}}
                <div class="space-y-2">

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Mobile</span>
                        <span class="text-slate-900 font-medium">
                            {{ $submission->mobile_number ?? '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Landline</span>
                        <span class="text-slate-900 font-medium">
                            {{ $submission->landline ?? '—' }}
                        </span>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="space-y-3">

                    <div>
                        <div class="text-slate-400">Facebook</div>
                        @if($submission->facebook_url)
                            <a href="{{ $submission->facebook_url }}" target="_blank"
                               class="text-blue-600 hover:underline break-all">
                                {{ $submission->facebook_url }}
                            </a>
                        @else
                            <div class="text-slate-900">—</div>
                        @endif
                    </div>

                    <div>
                        <div class="text-slate-400">Address</div>
                        <div class="text-slate-900 whitespace-pre-line">
                            {{ $submission->city_address ?? '—' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>