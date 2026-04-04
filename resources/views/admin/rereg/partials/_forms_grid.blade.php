<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

@foreach($forms as $key => $form)

    <div class="transition hover:scale-[1.01]">
        @include('admin.rereg.partials._form_card', [
            'key' => $key,
            'form' => $form
        ])
    </div>

@endforeach


{{-- ================= MEMBERS LIST CARD ================= --}}
<div class="rounded-2xl border border-slate-200 border-l-4 border-l-emerald-400 bg-gradient-to-b from-white to-slate-50 shadow-sm p-5 transition hover:shadow-md hover:scale-[1.01]">

    @php
        $status = 'Directory Available';
        $dot = 'bg-emerald-500';
    @endphp

    <div class="flex items-start justify-between gap-3">

        {{-- LEFT CONTENT --}}
        <div class="space-y-1">

            {{-- TITLE --}}
            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                <i data-lucide="users" class="w-4 h-4 text-slate-400"></i>
                Members List
            </div>

            {{-- STATUS --}}
            <div class="flex items-center gap-2 text-xs text-slate-700">

                <span class="h-2 w-2 rounded-full {{ $dot }}"></span>

                <span class="font-medium">
                    {{ $status }}
                </span>

            </div>

            {{-- DESCRIPTION --}}
            <div class="text-[11px] text-slate-500">
                View all registered organization members for this school year
            </div>

        </div>

        {{-- ACTION --}}
        <div class="flex flex-col items-end gap-2">

            <a href="{{ route('sacdev.members.index', [
                    'organization_id' => $organization->id,
                    'school_year_id' => $encodeSyId
                ]) }}"
               class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white
                      px-3 py-1.5 text-xs font-semibold text-slate-700
                      hover:bg-slate-50 hover:border-slate-300 transition">

                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                View

            </a>

        </div>

    </div>

</div>

</div>