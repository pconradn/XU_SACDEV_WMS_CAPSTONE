<x-app-layout>

  
    <div class="px-3 sm:px-4 lg:px-5 pt-4">

        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-4 flex items-center justify-between">

            {{-- LEFT --}}
            <div class="flex items-start gap-3">

                {{-- ICON --}}
                <div class="mt-1 flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>

                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Admin Dashboard
                    </h2>

                    <p class="text-xs text-slate-500 mt-0.5">
                        Monitor approvals, organization activity, and system workflows.
                    </p>
                </div>

            </div>


            
            <div class="hidden sm:flex items-center gap-2 text-[11px] text-slate-500">

               
                <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 font-medium">
                    SACDEV Panel
                </span>

  

            </div>

        </div>

    </div>

    {{-- OVERRIDE layout gray --}}
    <style>
        .content-frame.soft {
            background: #ffffff !important;
        }
    </style>

    {{-- FULL WIDTH INSIDE FRAME --}}
    <div class="px-3 sm:px-4 lg:px-5 py-3 space-y-3">

        {{-- STATUS --}}
        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- KPI --}}
        @include('admin.dashboard._kpis')

        {{-- MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

            {{-- LEFT --}}
            <div class="lg:col-span-2 space-y-3">
                @include('admin.dashboard._pending-cases')
                @include('admin.dashboard._project-approvals')
            </div>

            {{-- RIGHT --}}
            <div class="space-y-3">
                @include('admin.dashboard._activation')
                @include('admin.dashboard._quick-links')
            </div>

        </div>

        {{-- CALENDAR --}}
        @include('admin.dashboard._calendar')

    </div>

</x-app-layout>