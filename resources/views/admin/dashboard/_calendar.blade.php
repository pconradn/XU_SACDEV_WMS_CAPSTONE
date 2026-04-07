<style>


    .fc .fc-daygrid-event {
        border: none !important;
        background: transparent !important;
        padding: 0 !important;
    }

    .fc-custom-event {
        background: rgb(239 246 255); /* blue-50 */
        border: 1px solid rgb(191 219 254); /* blue-200 */
        border-radius: 8px;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: 600;
        color: rgb(29 78 216); /* blue-700 */

        overflow: hidden;
        white-space: nowrap;
    }

    .fc-title {
        display: inline-block;
        padding-left: 100%;
        animation: scrollText 6s linear infinite;
    }

    /* scrolling effect */
    @keyframes scrollText {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }

    /* optional hover pause */
    .fc-custom-event:hover .fc-title {
        animation-play-state: paused;
    }


    .fc {
        font-family: inherit;
    }

    /* ===== HEADER (MONTH + BUTTONS) ===== */

    .fc .fc-toolbar {
        margin-bottom: 10px;
    }

    .fc .fc-toolbar-title {
        font-size: 18px;
        font-weight: 600;
        color: rgb(15 23 42); /* slate-900 */
    }

    /* ===== BUTTONS ===== */

    .fc .fc-button {
        background: rgb(248 250 252) !important; /* slate-50 */
        border: 1px solid rgb(226 232 240) !important; /* slate-200 */
        color: rgb(51 65 85) !important; /* slate-700 */

        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;

        box-shadow: none !important;
        transition: all 0.15s ease;
    }

    .fc .fc-button:hover {
        background: rgb(241 245 249) !important; /* slate-100 */
    }

    .fc .fc-button:active {
        background: rgb(226 232 240) !important;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: rgb(226 232 240) !important;
    }

    /* remove ugly focus outline */
    .fc .fc-button:focus {
        box-shadow: none !important;
    }

    /* ===== CALENDAR GRID ===== */

    .fc .fc-daygrid-day {
        background: white;
        border: 1px solid rgb(241 245 249); /* very light */
    }

    /* today highlight */
    .fc .fc-day-today {
        background: rgb(248 250 252) !important; /* subtle */
    }

    /* day number */
    .fc .fc-daygrid-day-number {
        font-size: 11px;
        color: rgb(100 116 139); /* slate-500 */
        padding: 4px;
    }

    /* ===== WEEK HEADER (Sun Mon Tue...) ===== */

    .fc .fc-col-header-cell {
        background: rgb(248 250 252); /* slate-50 */
        border: 1px solid rgb(241 245 249);
    }

    .fc .fc-col-header-cell-cushion {
        font-size: 11px;
        font-weight: 600;
        color: rgb(71 85 105); /* slate-600 */
    }

    /* ===== EVENTS (you already styled base) ===== */

    .fc-custom-event {
        background: rgb(239 246 255);
        border: 1px solid rgb(191 219 254);
        border-radius: 6px;
        padding: 2px 6px;

        font-size: 10px;
        font-weight: 600;
        color: rgb(29 78 216);

        overflow: hidden;
        white-space: nowrap;

        transition: all 0.15s ease;
    }

    .fc-custom-event:hover {
        background: rgb(219 234 254); /* hover effect */
    }

    /* scrolling text */
    .fc-title {
        display: inline-block;
        padding-left: 100%;
        animation: scrollText 6s linear infinite;
    }

    @keyframes scrollText {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }

    .fc-custom-event:hover .fc-title {
        animation-play-state: paused;
    }

    /* ===== CELL HEIGHT CLEANUP ===== */

    .fc .fc-daygrid-day-frame {
        padding: 2px;
    }

    /* ===== REMOVE OVER-HEAVY BORDERS ===== */

    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: rgb(241 245 249);
    }

</style>




<div class="hidden">
    bg-blue-50 text-blue-700 border-blue-200
    bg-emerald-50 text-emerald-700 border-emerald-200
    bg-amber-50 text-amber-700 border-amber-200
</div>

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-3">

        <div class="flex items-center gap-2">
            {{-- Lucide: calendar --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>

            <div>
                <div class="text-xs font-semibold text-slate-900">
                    Project Timeline
                </div>
                <div class="text-[10px] text-slate-500">
                    Monthly project distribution
                </div>
            </div>
        </div>

        <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold">
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

    {{-- MONTH STRIP --}}
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
                class="group min-w-[85px] px-3 py-2 rounded-xl border transition text-left
                       {{ $count > 0
                            ? 'bg-blue-50 border-blue-200 hover:bg-blue-100'
                            : 'bg-slate-50 border-slate-200 hover:bg-slate-100'
                       }}"
            >
                <div class="text-[9px] font-semibold text-slate-500 uppercase">
                    {{ $month->format('M') }}
                </div>

                <div class="text-sm font-bold text-slate-900 leading-tight">
                    {{ $month->format('Y') }}
                </div>

                <div class="text-[10px] mt-1 font-medium
                    {{ $count > 0 ? 'text-blue-700' : 'text-slate-400' }}">
                    {{ $count }}
                    <span class="opacity-70">
                        {{ $count === 1 ? 'proj' : 'proj' }}
                    </span>
                </div>

                {{-- subtle hover indicator --}}
                <div class="h-[2px] mt-1 rounded-full
                    {{ $count > 0 ? 'bg-blue-300 group-hover:bg-blue-500' : 'bg-transparent' }}">
                </div>

            </button>

        @endforeach

    </div>

</div>


<div id="calendarModal"
     class="fixed inset-0 bg-black/30 hidden items-center justify-center z-50 backdrop-blur-sm">

    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden">

        {{-- HEADER --}}
        <div class="px-4 py-3 border-b flex items-center justify-between bg-slate-50">

            <div class="flex items-center gap-2">
                {{-- icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                </svg>

                <div class="text-xs font-semibold text-slate-900">
                    Monthly Calendar
                </div>
            </div>

            <button onclick="closeCalendarModal()"
                    class="text-[10px] font-semibold text-slate-500 hover:text-slate-700">
                Close
            </button>

        </div>

        {{-- BODY --}}
        <div class="p-3">
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

    // destroy previous instance
    if (modalCalendar) {
        modalCalendar.destroy();
        modalCalendar = null;
    }

    const events = @json($calendarProjects);

    modalCalendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        initialDate: date,
        height: 500,

        events: events,

        // 🔥 remove "8a"
        displayEventTime: false,

        // 🔥 CUSTOM EVENT UI
        eventContent: function(arg) {

            const title = arg.event.title;

            return {
                html: `
                    <div class="fc-custom-event">
                        <div class="fc-title">${title}</div>
                    </div>
                `
            };
        },

        // 🔥 CLICK NAVIGATION
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

    // optional cleanup (recommended)
    if (modalCalendar) {
        modalCalendar.destroy();
        modalCalendar = null;
    }
}
</script>