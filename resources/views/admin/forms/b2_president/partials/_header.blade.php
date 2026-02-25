<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

    <div>

        <div class="flex items-center gap-3 flex-wrap">

            <h1 class="text-xl font-semibold text-slate-900">
                B-2 President Registration
            </h1>

            @php
                $status = $registration->status ?? null;

                $dot =
                    !$status ? 'bg-slate-400' :
                    (str_contains($status,'approved') ? 'bg-emerald-500' :
                    (str_contains($status,'returned') ? 'bg-rose-500' :
                    (str_contains($status,'submitted') ? 'bg-amber-500' :
                    (str_contains($status,'forwarded') ? 'bg-blue-500' :
                    'bg-slate-400'))));

                $statusText = match($status) {

                    'draft' => 'Draft',

                    'submitted',
                    'submitted_to_sacdev' => 'Submitted to SACDEV',

                    'submitted_to_moderator' => 'Submitted to Moderator',

                    'returned',
                    'returned_by_moderator' => 'Returned',

                    'approved',
                    'approved_by_sacdev' => 'Approved',

                    'forwarded_to_sacdev' => 'Forwarded to SACDEV',

                    default => 'Not submitted'
                };
            @endphp


            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700">

                <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>

                {{ $statusText }}

            </span>

        </div>



        <div class="mt-2 text-sm text-slate-600 space-y-1">

            <div>
                Organization:
                <span class="font-semibold text-slate-900">
                    {{ $registration->organization->name ?? '—' }}
                </span>
            </div>


        </div>



        @if($registration->submitted_at || $registration->created_at)

            <div class="mt-2 text-xs text-slate-500">

                Submitted:
                {{
                    $registration->submitted_at
                        ? \Carbon\Carbon::parse($registration->submitted_at)->format('M d, Y — h:i A')
                        : \Carbon\Carbon::parse($registration->created_at)->format('M d, Y — h:i A')
                }}

            </div>

        @endif


        @if($registration->sacdev_reviewed_at)

            <div class="text-xs text-slate-500">

                SACDEV reviewed:
                {{
                    \Carbon\Carbon::parse($registration->sacdev_reviewed_at)->format('M d, Y — h:i A')
                }}

            </div>

        @endif

    </div>



    <div class="flex items-center gap-2">

        <a href="{{ route('admin.b2.president.index') }}"
           class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">

            ← Back to List

        </a>

    </div>

</div>