@if($document && $document->signatures && $document->signatures->count())

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm mt-8 overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 bg-slate-50">
        <div class="text-sm font-semibold text-slate-900">
            Approval Trail
        </div>
        <div class="text-xs text-slate-500">
            Track the approval progress of this document
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="divide-y md:divide-y-0 md:grid md:grid-cols-2 md:divide-x">

        @foreach($document->signatures->sortBy('id') as $sig)

        @php
            $status = $sig->status;

            $statusStyles = [
                'signed' => 'bg-emerald-50 text-emerald-700',
                'pending' => 'bg-amber-50 text-amber-700',
            ];

            $badge = $statusStyles[$status] ?? 'bg-slate-100 text-slate-600';
        @endphp

        <div class="px-5 py-4 flex items-center justify-between">

            {{-- LEFT --}}
            <div class="flex flex-col gap-1">
                <div class="text-sm font-semibold text-slate-900 capitalize">
                    {{ str_replace('_',' ', $sig->role) }}
                </div>

                <div class="text-xs text-slate-500">
                    {{ $sig->user?->name ?? 'Unknown User' }}
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="text-right flex flex-col items-end gap-1">

                @if($status === 'signed')
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge }}">
                        Approved
                    </span>

                    <span class="text-xs text-slate-500">
                        {{ $sig->signed_at?->format('M d, Y h:i A') }}
                    </span>

                @elseif($status === 'pending')
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge }}">
                        Pending
                    </span>
                @endif

            </div>

        </div>

        @endforeach

    </div>

</div>

@endif