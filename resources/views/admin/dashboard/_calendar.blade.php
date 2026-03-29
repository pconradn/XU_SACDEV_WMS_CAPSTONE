<div class="hidden">
    bg-blue-50 text-blue-700 border-blue-200
    bg-emerald-50 text-emerald-700 border-emerald-200
    bg-amber-50 text-amber-700 border-amber-200
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-900">
            Project Timeline
        </h3>

        <span class="text-xs text-slate-400">
            {{ $activeSy?->name ?? '' }}
        </span>
    </div>

    @php
        $start = \Carbon\Carbon::parse($activeSy->start_date ?? now())->startOfMonth();
        $end = \Carbon\Carbon::parse($activeSy->end_date ?? now())->endOfMonth();

        $months = [];
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $months[] = $cursor->copy();
            $cursor->addMonth();
        }
    @endphp

    <div class="flex gap-2 overflow-x-auto pb-1">

        @foreach($months as $month)

            @php
                $count = collect($calendarProjects)
                    ->filter(fn($e) =>
                        \Carbon\Carbon::parse($e['start'])->format('Y-m') === $month->format('Y-m')
                    )->count();
            @endphp

            <button
                onclick="openCalendarModal('{{ $month->format('Y-m-d') }}')"
                class="min-w-[90px] px-3 py-2 rounded-xl border transition text-left
                       {{ $count > 0
                            ? 'bg-blue-50 border-blue-200 hover:bg-blue-100'
                            : 'bg-slate-50 border-slate-200 hover:bg-slate-100'
                       }}"
            >
                <div class="text-[10px] font-semibold text-slate-500 uppercase">
                    {{ $month->format('M') }}
                </div>

                <div class="text-sm font-bold text-slate-900">
                    {{ $month->format('Y') }}
                </div>

                <div class="text-[10px] mt-1 {{ $count > 0 ? 'text-blue-700' : 'text-slate-400' }}">
                    {{ $count }} project{{ $count !== 1 ? 's' : '' }}
                </div>
            </button>

        @endforeach

    </div>

</div>


<div id="calendarModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden">

        <div class="px-5 py-4 border-b flex justify-between items-center bg-blue-50 border-blue-200">
            <h3 class="text-sm font-semibold text-blue-800">
                Monthly Calendar
            </h3>

            <button onclick="closeCalendarModal()" class="text-sm text-blue-600">
                Close
            </button>
        </div>

        <div class="p-4">
            <div id="modalCalendar"></div>
        </div>

    </div>

</div>



<script>
let modalCalendar;

function openCalendarModal(date)
{
    const modal = document.getElementById('calendarModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const calendarEl = document.getElementById('modalCalendar');

    if (modalCalendar) {
        modalCalendar.destroy();
    }

    const events = @json($calendarProjects);

    modalCalendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: date,
        height: 500,

        events: events,

        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
                info.jsEvent.preventDefault();
            }
        },

        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'today'
        }
    });

    modalCalendar.render();
}

function closeCalendarModal()
{
    const modal = document.getElementById('calendarModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>