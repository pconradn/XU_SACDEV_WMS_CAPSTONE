<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

@foreach($forms as $key => $form)

@include('admin.rereg.partials._form_card', [
    'key' => $key,
    'form' => $form
])

@endforeach

{{-- MEMBERS LIST CARD --}}
<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

    @php
        $status = 'Directory Available';
        $dot = 'bg-emerald-500';
    @endphp

    {{-- Header --}}
    <div class="flex items-start justify-between gap-3">

        <div>

            {{-- Label --}}
            <div class="font-semibold text-slate-900">
                Members List
            </div>

            {{-- Status --}}
            <div class="mt-1 flex items-center gap-2 text-sm text-slate-700">

                <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>

                <span>
                    {{ $status }}
                </span>

            </div>

            {{-- Description --}}
            <div class="text-xs text-slate-500 mt-1">
                View all registered organization members for this school year
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex flex-col items-end gap-2">

            <a href="{{ route('sacdev.members.index', [
                    'organization_id' => $organization->id,
                    'school_year_id' => $encodeSyId
                ]) }}"
               class="inline-flex items-center rounded-md border border-slate-200
                      px-2.5 py-1 text-xs font-semibold text-slate-700
                      hover:bg-slate-50">

                View

            </a>

        </div>

    </div>

</div>

</div>