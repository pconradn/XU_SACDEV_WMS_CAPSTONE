@php
    $status = $doc->status ?? 'not_created';
@endphp

<tr>

<td class="px-6 py-5">

    <div class="font-semibold text-slate-900">
        {{ $label }}
    </div>

    @if(!$doc)
        <div class="text-xs text-slate-500 mt-1">
            Not created yet
        </div>
    @endif

</td>


<td class="px-6 py-5">

    @include('admin.projects.documents.partials._status-badge', [
        'status' => $status
    ])

</td>


<td class="px-6 py-5 text-right">

    @if($doc)

        <div class="flex justify-end gap-2">

            {{-- OPEN --}}
            <a href="{{ route('admin.projects.documents.open', [
                'project' => $project,
                'formType' => $code
            ]) }}"
               class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Review
            </a>

            {{-- PRINT --}}
            @if($status === 'approved_by_sacdev')

                <a href="{{ route('admin.projects.documents.print', [
                    'project' => $project,
                    'form' => $code,
                    'document' => $doc->id
                ]) }}"
                   target="_blank"
                   class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Print
                </a>

            @endif

        </div>

    @endif

</td>

</tr>