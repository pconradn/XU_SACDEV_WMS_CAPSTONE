@if($clearance['required'])

<div class="bg-white border rounded-2xl p-5 shadow-sm space-y-4">

    {{-- HEADER --}}
    <div class="flex items-start justify-between">

        <div>
            <p class="text-xs uppercase text-slate-500">
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
        </div>

        {{-- STATUS BADGE --}}
        <span class="text-xs px-2 py-1 rounded-full
            @if($clearance['status'] === 'required') bg-yellow-100 text-yellow-700
            @elseif($clearance['status'] === 'uploaded') bg-blue-100 text-blue-700
            @elseif($clearance['status'] === 'verified') bg-emerald-100 text-emerald-700
            @elseif($clearance['status'] === 'rejected') bg-rose-100 text-rose-700
            @endif
        ">
            @switch($clearance['status'])
                @case('required') Clearance Required @break
                @case('uploaded') Uploaded @break
                @case('verified') Verified @break
                @case('rejected') Returned @break
            @endswitch
        </span>

    </div>


    {{-- ACTIONS --}}
    @if($clearance['is_project_head'])

        <div class="space-y-3">

            {{-- GENERATE --}}
            <a href="{{ $clearance['print_url'] }}"
               target="_blank"
               class="block w-full text-center px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Generate Clearance Form
            </a>


            {{-- UPLOAD --}}
            @if(in_array($clearance['status'], ['required','uploaded','rejected']))

                <form method="POST"
                      action="{{ $clearance['upload_url'] }}"
                      enctype="multipart/form-data"
                      class="space-y-2">

                    @csrf

                    <input type="file"
                           name="clearance_file"
                           accept="application/pdf"
                           required
                           class="text-xs w-full">

                    <button type="submit"
                            class="w-full px-4 py-2 text-sm font-medium bg-slate-900 text-white rounded-lg hover:bg-slate-800">

                        @if($clearance['status'] === 'uploaded')
                            Replace Uploaded Clearance
                        @else
                            Upload Signed Clearance
                        @endif

                    </button>

                </form>

            @endif


            {{-- STATUS NOTES --}}
            @if($clearance['status'] === 'uploaded')
                <p class="text-xs text-slate-500 italic">
                    Uploaded. You may replace until verified.
                </p>
            @endif

            @if($clearance['status'] === 'verified')
                <p class="text-xs text-emerald-600 font-medium">
                    Verified by SACDEV. Locked.
                </p>
            @endif

        </div>

    @endif

</div>

@endif