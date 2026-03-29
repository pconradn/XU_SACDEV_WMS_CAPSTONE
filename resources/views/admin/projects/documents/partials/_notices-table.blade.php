<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">

        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Activity Notices
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Postponements and cancellations submitted for this project
            </p>
        </div>

        <div class="text-xs text-slate-400">
            {{ $postponements->count() + $cancellations->count() }} total
        </div>

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 border-y border-slate-200 text-slate-700">

                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Type</th>
                    <th class="px-6 py-3 text-left font-semibold">Created</th>
                    <th class="px-6 py-3 text-left font-semibold">Status</th>
                    <th class="px-6 py-3 text-right font-semibold">Action</th>
                </tr>

            </thead>

            <tbody class="divide-y divide-slate-200">

                {{-- ===================== --}}
                {{-- POSTPONEMENTS --}}
                {{-- ===================== --}}
                @foreach($postponements as $doc)

                    <tr class="hover:bg-slate-50/50 transition">

                        {{-- TYPE --}}
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">
                                Postponement Notice
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ $doc['id'] }}
                            </div>
                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4 text-slate-600 text-sm">
                            {{ \Carbon\Carbon::parse($doc['created_at'] ?? now())->format('M d, Y') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">

                            @php $status = $doc['status'] ?? null; @endphp

                            @if($status === 'submitted')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                    Submitted
                                </span>
                            @elseif($status === 'approved_by_sacdev')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-700">
                                    Approved
                                </span>
                            @elseif($status === 'returned')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-rose-100 text-rose-700">
                                    Returned
                                </span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif

                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">

                            <a href="{{ $doc['view_url'] }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                Open
                            </a>

                        </td>

                    </tr>

                @endforeach


                {{-- ===================== --}}
                {{-- CANCELLATIONS --}}
                {{-- ===================== --}}
                @foreach($cancellations as $doc)

                    <tr class="hover:bg-slate-50/50 transition">

                        {{-- TYPE --}}
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">
                                Cancellation Notice
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ $doc['id'] }}
                            </div>
                        </td>

                        {{-- DATE --}}
                        <td class="px-6 py-4 text-slate-600 text-sm">
                            {{ \Carbon\Carbon::parse($doc['created_at'] ?? now())->format('M d, Y') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">

                            @php $status = $doc['status'] ?? null; @endphp

                            @if($status === 'submitted')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                    Submitted
                                </span>
                            @elseif($status === 'approved_by_sacdev')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-700">
                                    Approved
                                </span>
                            @elseif($status === 'returned')
                                <span class="px-2 py-1 rounded text-xs font-medium bg-rose-100 text-rose-700">
                                    Returned
                                </span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif

                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-4 text-right">

                            <a href="{{ $doc['view_url'] }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                Open
                            </a>

                        </td>

                    </tr>

                @endforeach


                {{-- EMPTY --}}
                @if($postponements->isEmpty() && $cancellations->isEmpty())

                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">
                            No notices submitted.
                        </td>
                    </tr>

                @endif

            </tbody>

        </table>

    </div>

</div>