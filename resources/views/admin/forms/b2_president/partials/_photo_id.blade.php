<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between gap-4">

        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Photo Identification
            </h3>

            <p class="mt-1 text-sm text-slate-600 max-w-xl">
                Submitted photo identification of the incoming organization president.
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Accepted formats: JPG, PNG, WEBP
            </p>
        </div>


        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2.5 w-2.5 rounded-full {{ $registration->photo_id_path ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            </span>

            <span>
                {{ $registration->photo_id_path ? 'ID Uploaded' : 'No ID uploaded' }}
            </span>

        </div>

    </div>


    <div class="mt-5">

        <div class="flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 h-56 overflow-hidden">

            @if($registration->photo_id_path)

                <img
                    src="{{ asset('storage/' . $registration->photo_id_path) }}"
                    class="max-h-52 object-contain"
                    alt="President ID">

            @else

                <div class="text-center">

                    <div class="text-sm text-slate-400">
                        No ID uploaded by organization
                    </div>

                </div>

            @endif

        </div>

    </div>


    @if($registration->photo_id_path)

        <div class="mt-4 flex items-center justify-between gap-3 flex-wrap">

            <a href="{{ asset('storage/' . $registration->photo_id_path) }}"
               target="_blank"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">

                View Full Image

            </a>

            <div class="text-xs text-slate-500">
                Uploaded {{ optional($registration->updated_at)->format('M d, Y — h:i A') }}
            </div>

        </div>

    @endif

</div>