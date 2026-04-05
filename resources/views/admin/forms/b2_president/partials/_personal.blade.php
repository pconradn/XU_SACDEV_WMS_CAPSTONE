{{-- ================= PERSONAL INFO ================= --}}
<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-start justify-between gap-4">

        <div>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Personal Information
            </h3>

            <p class="mt-1 text-xs text-slate-500 max-w-md">
                Submitted personal details of the incoming organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[11px] font-medium">

            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2 w-2 rounded-full {{ $registration->full_name ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            </span>

            <span class="{{ $registration->full_name ? 'text-emerald-700' : 'text-slate-500' }}">
                {{ $registration->full_name ? 'Complete' : 'Incomplete' }}
            </span>

        </div>

    </div>


    {{-- BODY --}}
    <div class="p-5 grid sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">

        <div class="sm:col-span-2">
            <div class="text-slate-400">Full Name</div>
            <div class="mt-0.5 font-semibold text-slate-900">
                {{ $registration->full_name ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Course & Year</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->course_and_year ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Birthday</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->birthday
                    ? \Carbon\Carbon::parse($registration->birthday)->format('F d, Y')
                    : '—'
                }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Age</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->age ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Sex</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->sex ?: '—' }}
            </div>
        </div>

        <div>
            <div class="text-slate-400">Religion</div>
            <div class="mt-0.5 text-slate-900">
                {{ $registration->religion ?: '—' }}
            </div>
        </div>

    </div>

</div>