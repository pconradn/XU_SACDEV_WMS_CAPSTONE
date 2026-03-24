@php
    $photoUrl = $submission->photo_id_path 
        ? asset('storage/' . $submission->photo_id_path) 
        : null;
@endphp

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="p-5 flex items-center gap-4 border-b bg-slate-50">
        <div class="w-20 h-20 rounded-xl overflow-hidden border bg-slate-100 flex items-center justify-center">
            @if($photoUrl)
                <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
            @else
                <span class="text-xs text-slate-400">No Photo</span>
            @endif
        </div>

        <div class="flex-1 grid sm:grid-cols-2 gap-x-6 gap-y-2">
            <div>
                <div class="text-xs text-slate-400">Full Name</div>
                <div class="text-sm font-semibold text-slate-900">
                    {{ $submission->full_name ?? '—' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-slate-400">Email</div>
                <div class="text-sm">
                    @if($submission->email)
                        <a href="mailto:{{ $submission->email }}" class="text-blue-600 hover:underline">
                            {{ $submission->email }}
                        </a>
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="grid md:grid-cols-2">

        <div class="p-5 border-b md:border-b-0 md:border-r">
            <h3 class="text-xs font-semibold text-slate-500 uppercase mb-4">Personal Information</h3>

            <div class="divide-y">
                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Birthday</span>
                    <span class="text-slate-900 font-medium">
                        {{ $submission->birthday ? $submission->birthday->format('M d, Y') : '—' }}
                    </span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Age</span>
                    <span class="text-slate-900 font-medium">{{ $submission->age ?? '—' }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Sex</span>
                    <span class="text-slate-900 font-medium">{{ $submission->sex ?? '—' }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Religion</span>
                    <span class="text-slate-900 font-medium">{{ $submission->religion ?? '—' }}</span>
                </div>
            </div>
        </div>


        <div class="p-5 border-b">
            <h3 class="text-xs font-semibold text-slate-500 uppercase mb-4">Employment Information</h3>

            <div class="divide-y">
                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Designation</span>
                    <span class="text-slate-900 font-medium">{{ $submission->university_designation ?? '—' }}</span>
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Department</span>
                    <span class="text-slate-900 font-medium">{{ $submission->unit_department ?? '—' }}</span>
                </div>

                <div class="flex justify-between py-2 items-center">
                    <span class="text-slate-400">Status</span>

                    @if($submission->employment_status)
                        <span class="px-2 py-0.5 text-xs rounded-full 
                            bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ ucfirst($submission->employment_status) }}
                        </span>
                    @else
                        —
                    @endif
                </div>

                <div class="flex justify-between py-2">
                    <span class="text-slate-400">Years of Service</span>
                    <span class="text-slate-900 font-medium">{{ $submission->years_of_service ?? '—' }}</span>
                </div>
            </div>
        </div>


        <div class="p-5 md:col-span-2">
            <h3 class="text-xs font-semibold text-slate-500 uppercase mb-4">Contact Information</h3>

            <div class="grid sm:grid-cols-2 gap-x-6">

                <div class="divide-y">
                    <div class="flex justify-between py-2">
                        <span class="text-slate-400">Mobile</span>
                        <span class="text-slate-900 font-medium">{{ $submission->mobile_number ?? '—' }}</span>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="text-slate-400">Landline</span>
                        <span class="text-slate-900 font-medium">{{ $submission->landline ?? '—' }}</span>
                    </div>
                </div>

                <div class="space-y-3 mt-4 sm:mt-0">
                    <div>
                        <div class="text-xs text-slate-400">Facebook</div>
                        @if($submission->facebook_url)
                            <a href="{{ $submission->facebook_url }}" target="_blank"
                               class="text-blue-600 hover:underline text-sm break-all">
                                {{ $submission->facebook_url }}
                            </a>
                        @else
                            <div class="text-sm text-slate-900">—</div>
                        @endif
                    </div>

                    <div>
                        <div class="text-xs text-slate-400">Address</div>
                        <div class="text-sm text-slate-900 whitespace-pre-line">
                            {{ $submission->city_address ?? '—' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>