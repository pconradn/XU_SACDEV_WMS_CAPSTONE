<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-start justify-between gap-4">

        <div>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Contact Information
            </h3>

            <p class="mt-1 text-xs text-slate-500 max-w-md">
                Contact details submitted for verification and official communication.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[11px] font-medium">

            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2 w-2 rounded-full {{ $registration->email ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            </span>

            <span class="{{ $registration->email ? 'text-emerald-700' : 'text-slate-500' }}">
                {{ $registration->email ? 'Complete' : 'Incomplete' }}
            </span>

        </div>

    </div>


    {{-- BODY --}}
    <div class="p-5 grid sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">

        <div>
            <div class="text-slate-400">Mobile Number</div>
            <div class="mt-0.5 text-slate-900 font-medium">
                {{ $registration->mobile_number ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Email Address</div>
            <div class="mt-0.5 text-slate-900 break-all font-medium">
                {{ $registration->email ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Student ID Number</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->id_number ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">City Landline</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->city_landline ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Provincial Landline</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->provincial_landline ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Facebook Profile</div>

            @if($registration->facebook_url)
                <a href="{{ $registration->facebook_url }}"
                   target="_blank"
                   class="mt-0.5 inline-block text-blue-600 hover:underline break-all">
                    {{ $registration->facebook_url }}
                </a>
            @else
                <div class="mt-0.5 text-slate-900">—</div>
            @endif
        </div>

        <div class="sm:col-span-2">
            <div class="text-slate-400">Complete Home Address</div>
            <div class="mt-0.5 text-slate-900 whitespace-pre-line">
                {{ $registration->home_address ?: '—' }}
            </div>
        </div>

        <div class="sm:col-span-2">
            <div class="text-slate-400">Complete City Address</div>
            <div class="mt-0.5 text-slate-900 whitespace-pre-line">
                {{ $registration->city_address ?: '—' }}
            </div>
        </div>

    </div>

</div>