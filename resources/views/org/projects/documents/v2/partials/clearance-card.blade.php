@if($clearance['required'])

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="flex items-start justify-between px-5 py-4 border-b border-slate-100">

        <div>
            <p class="text-[10px] uppercase tracking-wide text-slate-400">
                Off-Campus Clearance
            </p>

            @if($clearance['reference'])
                <p class="mt-1 text-sm font-semibold text-slate-800">
                    Ref:
                    <span class="font-mono text-blue-700">
                        {{ $clearance['reference'] }}
                    </span>
                </p>
            @endif

            @if(!empty($clearance['issued_at']))
                <p class="text-xs text-slate-500 mt-1">
                    Issued:
                    {{ \Carbon\Carbon::parse($clearance['issued_at'])->format('M d, Y h:i A') }}
                </p>
            @endif
        </div>

        {{-- STATUS --}}
        <div class="text-[10px] px-2 py-1 rounded-lg
            @if($clearance['status'] === 'required') bg-amber-50 text-amber-700
            @elseif($clearance['status'] === 'uploaded') bg-blue-50 text-blue-700
            @elseif($clearance['status'] === 'verified') bg-emerald-50 text-emerald-700
            @elseif($clearance['status'] === 'rejected') bg-rose-50 text-rose-700
            @elseif($clearance['status'] === 'replaced') bg-slate-100 text-slate-600
            @endif">

            @switch($clearance['status'])
                @case('required') Required @break
                @case('uploaded') Uploaded @break
                @case('verified') Verified @break
                @case('rejected') Returned @break
                @case('replaced') Replaced @break
            @endswitch
        </div>

    </div>


    <div class="p-5 space-y-4">

        {{-- OUTDATED WARNING --}}
        @if(!empty($clearance['is_outdated']))
        <div class="text-xs border border-amber-200 bg-amber-50 text-amber-700 rounded-xl px-3 py-2">
            This clearance is based on outdated project data. Reissue to reflect updates.
        </div>
        @endif


        {{-- SNAPSHOT SUMMARY --}}
        @if(!empty($clearance['snapshot']))
        <div class="border border-slate-100 bg-slate-50 rounded-xl p-4 text-xs">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <div class="text-slate-500">Activity Dates</div>
                    <div class="font-medium text-slate-800">
                        {{ $clearance['snapshot']['start_date']
                            ? \Carbon\Carbon::parse($clearance['snapshot']['start_date'])->format('M d, Y')
                            : '—' }}
                        —
                        {{ $clearance['snapshot']['end_date']
                            ? \Carbon\Carbon::parse($clearance['snapshot']['end_date'])->format('M d, Y')
                            : '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500">Venue</div>
                    <div class="font-medium text-slate-800">
                        {{ $clearance['snapshot']['off_campus_venue'] ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500">Total Budget</div>
                    <div class="font-medium text-slate-800">
                        ₱{{ number_format($clearance['snapshot']['total_budget'] ?? 0, 2) }}
                    </div>
                </div>

                <div>
                    <div class="text-slate-500">Participants</div>
                    <div class="font-medium text-slate-800">
                        {{ $clearance['participants_count'] ?? 0 }}
                    </div>
                </div>

            </div>

        </div>
        @endif


        {{-- ACTIONS --}}
        @if($clearance['is_project_head'])

        <div class="space-y-3">

            <div class="flex flex-wrap gap-2">

                {{-- PRINT --}}
                <a href="{{ $clearance['print_url'] }}"
                   target="_blank"
                   class="flex-1 text-center px-3 py-2 text-xs font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                    Print
                </a>

                {{-- REISSUE --}}
                @if(!empty($clearance['is_outdated']))
                <form method="POST" action="{{ $clearance['reissue_url'] }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full px-3 py-2 text-xs font-medium bg-rose-600 text-white rounded-lg hover:bg-rose-700">
                        Reissue
                    </button>
                </form>
                @endif

            </div>


            {{-- UPLOAD --}}
            @if(in_array($clearance['status'], ['required','uploaded','rejected','issued']))

            <form method="POST"
                  action="{{ $clearance['upload_url'] }}"
                  enctype="multipart/form-data"
                  class="space-y-2">

                @csrf

                <input type="file"
                       name="clearance_file"
                       accept="application/pdf"
                       required
                       class="text-xs w-full border border-slate-200 rounded-lg px-2 py-1">

                <button type="submit"
                        class="w-full px-4 py-2 text-xs font-medium border border-slate-300 rounded-lg hover:bg-slate-50">

                    @if($clearance['status'] === 'uploaded')
                        Replace Uploaded Clearance
                    @else
                        Upload Signed Clearance
                    @endif

                </button>

            </form>

            @endif


            {{-- STATUS NOTES --}}
            <div class="text-xs space-y-1">

                @if($clearance['status'] === 'uploaded')
                    <p class="text-slate-500 italic">
                        Uploaded. You may replace until verified.
                    </p>
                @endif

                @if($clearance['status'] === 'verified')
                    <p class="text-emerald-600 font-medium">
                        Verified by SACDEV. This clearance is now locked.
                    </p>
                @endif

                @if($clearance['status'] === 'replaced')
                    <p class="text-slate-500 italic">
                        This clearance has been replaced by a newer version.
                    </p>
                @endif

            </div>

        </div>

        @endif

    </div>

</div>

@endif