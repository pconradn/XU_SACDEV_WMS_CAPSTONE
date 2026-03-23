<div class="lg:col-span-2 bg-white border rounded-2xl p-6 shadow-sm space-y-4">

    <h2 class="text-sm font-semibold text-slate-700">
        Project Snapshot
    </h2>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">

        {{-- DATE --}}
        <div>
            <p class="text-slate-500">Date</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['date'] ?? '—' }}
            </p>
        </div>

        {{-- TIME --}}
        <div>
            <p class="text-slate-500">Time</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['time'] ?? '—' }}
            </p>
        </div>

        {{-- VENUE --}}
        <div>
            <p class="text-slate-500">Venue</p>
            <p class="font-medium text-slate-800">
                {{ $snapshot['venue'] ?? '—' }}
            </p>
        </div>

    </div>

</div>