@if($submission->leaderships && $submission->leaderships->count())

<div class="mt-4 rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-center gap-2">
        <i data-lucide="users" class="w-4 h-4 text-slate-400"></i>
        <h3 class="text-sm font-semibold text-slate-800">
            Leadership Involvement
        </h3>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="min-w-full text-xs">

            {{-- HEAD --}}
            <thead class="text-[11px] uppercase text-slate-500 border-b border-slate-200 bg-white">
                <tr>
                    <th class="py-2 px-4 font-medium">Organization</th>
                    <th class="py-2 px-4 font-medium">Position</th>
                    <th class="py-2 px-4 font-medium">Address</th>
                    <th class="py-2 px-4 font-medium text-right">Years</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="divide-y divide-slate-100 bg-white">

                @foreach($submission->leaderships as $row)
                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-2.5 px-4 text-slate-900 font-medium">
                            {{ $row->organization_name ?? '—' }}
                        </td>

                        <td class="py-2.5 px-4 text-slate-700">
                            {{ $row->position ?? '—' }}
                        </td>

                        <td class="py-2.5 px-4 text-slate-600">
                            {{ $row->organization_address ?? '—' }}
                        </td>

                        <td class="py-2.5 px-4 text-right text-slate-700">
                            {{ $row->inclusive_years ?? '—' }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endif