<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">
                Off-Campus Clearance
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Generate, verify, and manage your clearance document for this project
            </p>
        </div>

        <div class="text-xs px-2 py-1 rounded-lg
            @if($project->clearance_status === 'issued') bg-emerald-50 text-emerald-700
            @elseif($project->clearance_status === 'uploaded') bg-blue-50 text-blue-700
            @elseif($project->clearance_status === 'replaced') bg-amber-50 text-amber-700
            @elseif($project->clearance_status === 'revoked') bg-rose-50 text-rose-700
            @else bg-slate-100 text-slate-600
            @endif">
            {{ strtoupper($project->clearance_status ?? 'draft') }}
        </div>
    </div>

    <div class="p-5 space-y-5">

        {{-- WARNING --}}
        @if(!empty($isOutdated))
        <div class="text-xs border border-amber-200 bg-amber-50 text-amber-700 rounded-xl px-3 py-2">
            This clearance is based on outdated project data. Reissue to reflect updated details.
        </div>
        @endif

        {{-- META --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">

            <div class="space-y-1">
                <div class="text-slate-500">Reference</div>
                <div class="font-semibold text-slate-800">
                    {{ $project->clearance_reference ?? '—' }}
                </div>
            </div>

            <div class="space-y-1">
                <div class="text-slate-500">Issued At</div>
                <div class="font-semibold text-slate-800">
                    {{ $project->clearance_issued_at 
                        ? \Carbon\Carbon::parse($project->clearance_issued_at)->format('M d, Y h:i A') 
                        : '—' }}
                </div>
            </div>

        </div>

        {{-- SNAPSHOT SUMMARY --}}
        @if(!empty($snapshot))
        <div class="border border-slate-100 rounded-xl p-4 bg-slate-50 text-xs space-y-2">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <span class="text-slate-500">Activity Dates</span><br>
                    <span class="font-medium text-slate-800">
                        {{ $snapshot['start_date'] 
                            ? \Carbon\Carbon::parse($snapshot['start_date'])->format('M d, Y') 
                            : '—' }}
                        —
                        {{ $snapshot['end_date'] 
                            ? \Carbon\Carbon::parse($snapshot['end_date'])->format('M d, Y') 
                            : '—' }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-500">Venue</span><br>
                    <span class="font-medium text-slate-800">
                        {{ $snapshot['off_campus_venue'] ?? '—' }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-500">Total Budget</span><br>
                    <span class="font-medium text-slate-800">
                        ₱{{ number_format($snapshot['total_budget'] ?? 0, 2) }}
                    </span>
                </div>

                <div>
                    <span class="text-slate-500">Participants</span><br>
                    <span class="font-medium text-slate-800">
                        {{ $participants->count() ?? 0 }}
                    </span>
                </div>

            </div>

        </div>
        @endif

        {{-- ACTIONS --}}
        <div class="flex flex-wrap items-center gap-2 pt-2">

            {{-- PRINT --}}
            <a href="{{ route('org.projects.clearance.print', $project) }}"
               class="px-3 py-2 text-xs rounded-lg bg-slate-900 text-white hover:bg-slate-800">
                Print Clearance
            </a>

            {{-- REISSUE --}}
            @if(!empty($isOutdated))
            <form method="POST" action="{{ route('org.projects.clearance.reissue', $project) }}">
                @csrf
                <button type="submit"
                        class="px-3 py-2 text-xs rounded-lg bg-rose-600 text-white hover:bg-rose-700">
                    Reissue
                </button>
            </form>
            @endif

            {{-- UPLOAD --}}
            <form method="POST"
                  action="{{ route('org.projects.clearance.upload', $project) }}"
                  enctype="multipart/form-data"
                  class="flex items-center gap-2">
                @csrf

                <input type="file" name="clearance_file"
                       class="text-xs border border-slate-200 rounded-lg px-2 py-1"
                       required>

                <button type="submit"
                        class="px-3 py-2 text-xs rounded-lg border border-slate-300 hover:bg-slate-50">
                    Upload Signed Copy
                </button>
            </form>

        </div>

    </div>

</div>