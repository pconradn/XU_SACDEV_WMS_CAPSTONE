<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">

        <div>
            <h3 class="text-sm font-semibold text-slate-800">
                Activity Notices
            </h3>
            <p class="text-[11px] text-slate-500 mt-0.5">
                Postponements and cancellations for this project
            </p>
        </div>

        <div class="text-[11px] text-slate-400">
            {{ $postponements->count() + $cancellations->count() }} total
        </div>

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            {{-- HEAD --}}
            <thead class="bg-slate-50 border-y border-slate-200 text-slate-600 text-[11px] uppercase tracking-wide">

                <tr>
                    <th class="px-5 py-3 text-left font-semibold">Notice</th>
                    <th class="px-5 py-3 text-left font-semibold">Date</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-right font-semibold">Action</th>
                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                {{-- ===================== --}}
                {{-- POSTPONEMENTS --}}
                {{-- ===================== --}}
                @foreach($postponements as $doc)

                    <tr class="hover:bg-amber-50/40 transition">

                        {{-- TYPE --}}
                        <td class="px-5 py-4">

                            <div class="flex items-center gap-2">

                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>

                                <div class="font-medium text-slate-800">
                                    Postponement
                                </div>

                                <span class="text-[10px] text-slate-400">
                                    #{{ $doc['id'] }}
                                </span>

                            </div>

                        </td>


                        {{-- DATE --}}
                        <td class="px-5 py-4 text-[11px] text-slate-600">
                            {{ \Carbon\Carbon::parse($doc['created_at'] ?? now())->format('M d, Y') }}
                        </td>


                        {{-- STATUS --}}
                        <td class="px-5 py-4">

                            @php $status = $doc['status'] ?? null; @endphp

                            @if($status === 'submitted')
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">
                                    Submitted
                                </span>
                            @elseif($status === 'approved_by_sacdev')
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                                    Approved
                                </span>
                            @elseif($status === 'returned')
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700">
                                    Returned
                                </span>
                            @else
                                <span class="text-[10px] text-slate-400">—</span>
                            @endif

                        </td>


                        {{-- ACTION --}}
                        <td class="px-5 py-4 text-right">

                            <a href="{{ $doc['view_url'] }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-semibold rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition">
                                Open
                            </a>

                        </td>

                    </tr>

                @endforeach


                {{-- ===================== --}}
                {{-- CANCELLATIONS --}}
                {{-- ===================== --}}
                @foreach($cancellations as $doc)

                    <tr class="hover:bg-rose-50/40 transition">

                        {{-- TYPE --}}
                        <td class="px-5 py-4">

                            <div class="flex items-center gap-2">

                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>

                                <div class="font-medium text-slate-800">
                                    Cancellation
                                </div>

                                <span class="text-[10px] text-slate-400">
                                    #{{ $doc['id'] }}
                                </span>

                            </div>

                        </td>


                        {{-- DATE --}}
                        <td class="px-5 py-4 text-[11px] text-slate-600">
                            {{ \Carbon\Carbon::parse($doc['created_at'] ?? now())->format('M d, Y') }}
                        </td>


                        {{-- STATUS --}}
                        <td class="px-5 py-4">

                            @php $status = $doc['status'] ?? null; @endphp

                            @if($status === 'submitted')
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">
                                    Submitted
                                </span>
                            @elseif($status === 'approved_by_sacdev')
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700">
                                    Approved
                                </span>
                            @elseif($status === 'returned')
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700">
                                    Returned
                                </span>
                            @else
                                <span class="text-[10px] text-slate-400">—</span>
                            @endif

                        </td>


                        {{-- ACTION --}}
                        <td class="px-5 py-4 text-right">

                            <a href="{{ $doc['view_url'] }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-semibold rounded-lg bg-rose-500 text-white hover:bg-rose-600 transition">
                                Open
                            </a>

                        </td>

                    </tr>

                @endforeach


                {{-- EMPTY --}}
                @if($postponements->isEmpty() && $cancellations->isEmpty())

                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-sm">
                            No notices submitted.
                        </td>
                    </tr>

                @endif

            </tbody>

        </table>

    </div>

</div>