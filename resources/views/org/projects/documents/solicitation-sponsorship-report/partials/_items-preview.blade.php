@if($document && $document->items && $document->items->count())

<div class="text-xs text-slate-600 mb-2">
    Showing {{ $document->items->count() }} entries
</div>

<div class="border border-slate-200 rounded-lg overflow-hidden">
    <table class="w-full text-xs">

        <thead class="bg-slate-50 text-slate-600">
            <tr>
                <th class="px-3 py-2 text-left">Control #</th>
                <th class="px-3 py-2 text-left">Sponsor</th>
                <th class="px-3 py-2 text-right">Amount</th>
            </tr>
        </thead>

        <tbody>
            @foreach($document->items->take(5) as $item)
            <tr class="border-t">
                <td class="px-3 py-2">{{ $item->control_number }}</td>
                <td class="px-3 py-2">{{ $item->sponsor_name }}</td>
                <td class="px-3 py-2 text-right">
                    {{ number_format($item->amount,2) }}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

@if($document->items->count() > 5)
<div class="text-xs text-slate-400 mt-2">
    + more entries inside modal
</div>
@endif

@else

<div class="text-xs text-slate-400">
    No entries added yet.
</div>

@endif