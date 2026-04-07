{{-- ================= PHOTO ID ================= --}}
<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-start justify-between gap-4">

        <div>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Photo Identification
            </h3>

            <p class="mt-1 text-xs text-slate-500 max-w-md">
                Submitted photo identification of the incoming organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[11px] font-medium">

            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2 w-2 rounded-full {{ $registration->photo_id_path ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            </span>

            <span class="{{ $registration->photo_id_path ? 'text-emerald-700' : 'text-slate-500' }}">
                {{ $registration->photo_id_path ? 'Uploaded' : 'No ID' }}
            </span>

        </div>

    </div>


    {{-- BODY --}}
    <div class="p-5 space-y-4">

        <div class="flex items-center justify-center rounded-xl border border-slate-200 bg-white h-52 overflow-hidden">

            @if($registration->photo_id_path)

                <img
                    src="{{ asset('storage/' . $registration->photo_id_path) }}"
                    class="max-h-48 object-contain"
                    alt="President ID">

            @else

                <div class="text-xs text-slate-400">
                    No ID uploaded by organization
                </div>

            @endif

        </div>


        @if($registration->photo_id_path)

            <div class="flex items-center justify-between gap-3 flex-wrap text-xs">

                <a href="{{ asset('storage/' . $registration->photo_id_path) }}"
                   target="_blank"
                   class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50 transition">
                    View Full Image
                </a>

                <div class="text-slate-400">
                    Uploaded {{ optional($registration->updated_at)->format('M d, Y — h:i A') }}
                </div>

            </div>

        @endif

    </div>

</div>