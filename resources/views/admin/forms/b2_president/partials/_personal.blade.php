<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between">

        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Personal Information
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Submitted personal details of the incoming organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2.5 w-2.5 rounded-full {{ $registration->full_name ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            </span>

            <span>
                {{ $registration->full_name ? 'Information provided' : 'Incomplete' }}
            </span>

        </div>

    </div>


    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">


        <div class="sm:col-span-2">

            <div class="text-xs text-slate-500">
                Full Name
            </div>

            <div class="mt-1 text-sm font-semibold text-slate-900">
                {{ $registration->full_name ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Course & Year
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->course_and_year ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Birthday
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->birthday
                    ? \Carbon\Carbon::parse($registration->birthday)->format('F d, Y')
                    : '—'
                }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Age
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->age ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Sex
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->sex ?: '—' }}
            </div>

        </div>



        <div>

            <div class="text-xs text-slate-500">
                Religion
            </div>

            <div class="mt-1 text-sm text-slate-900">
                {{ $registration->religion ?: '—' }}
            </div>

        </div>


    </div>

</div>