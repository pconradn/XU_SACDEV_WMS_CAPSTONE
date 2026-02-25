<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between">

        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Contact Information
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Contact details submitted for verification and official communication.
            </p>
        </div>

        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2.5 w-2.5 rounded-full {{ $registration->email ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            </span>

            <span>
                {{ $registration->email ? 'Contact info provided' : 'Incomplete' }}
            </span>

        </div>

    </div>



    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">


        <div>

            <div class="text-xs text-slate-500">
                Mobile Number
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->mobile_number ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Email Address
            </div>

            <div class="mt-1 text-sm text-slate-900 break-all">
                {{ $registration->email ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Student ID Number
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->id_number ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                City Landline
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->city_landline ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Provincial Landline
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->provincial_landline ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Facebook Profile
            </div>

            <div class="mt-1 text-sm text-slate-900">

                @if($registration->facebook_url)
                    <a href="{{ $registration->facebook_url }}"
                       target="_blank"
                       class="text-blue-600 hover:underline break-all">
                        {{ $registration->facebook_url }}
                    </a>
                @else
                    —
                @endif

            </div>

        </div>



        <div class="sm:col-span-2">

            <div class="text-xs text-slate-500">
                Complete Home Address
            </div>

            <div class="mt-1 text-sm text-slate-900 whitespace-pre-line">
                {{ $registration->home_address ?: '—' }}
            </div>

        </div>



        <div class="sm:col-span-2">

            <div class="text-xs text-slate-500">
                Complete City Address
            </div>

            <div class="mt-1 text-sm text-slate-900 whitespace-pre-line">
                {{ $registration->city_address ?: '—' }}
            </div>

        </div>


    </div>

</div>