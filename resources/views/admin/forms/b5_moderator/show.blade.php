<x-app-layout>

<div class="mx-auto max-w-7xl px-4 py-6">

    <div 
        x-data="{ openReturn: false, openAllowEdit: false, openRevertApproval: false }"
        class="space-y-6"
    >

        {{-- ================= HEADER ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">
            @include('admin.forms.b5_moderator.partials._header', ['submission' => $submission])
        </div>


        {{-- ================= MAIN GRID ================= --}}
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- ================= LEFT: CONTENT ================= --}}
            <div class="lg:col-span-2 space-y-4">

                @include('admin.forms.b5_moderator.partials._information', ['submission' => $submission])
                @include('admin.forms.b5_moderator.partials._leadership', ['submission' => $submission])
                @include('admin.forms.b5_moderator.partials._background', ['submission' => $submission])

            </div>


            {{-- ================= RIGHT: ACTIONS (DESKTOP ONLY) ================= --}}
            <div class="hidden lg:block">

                <div class="sticky top-6">

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                        {{-- HEADER --}}
                        <div class="px-4 py-3 border-b border-slate-200 flex items-center gap-2">
                            <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Actions
                            </span>
                        </div>

                        {{-- CONTENT --}}
                        <div class="p-4">
                            @include('admin.forms.b5_moderator.partials._actions', ['submission' => $submission])
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================= MOBILE ACTIONS (BOTTOM) ================= --}}
        <div class="lg:hidden">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-4 py-3 border-b border-slate-200 flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        Actions
                    </span>
                </div>

                <div class="p-4">
                    @include('admin.forms.b5_moderator.partials._actions', ['submission' => $submission])
                </div>

            </div>

        </div>


        {{-- ================= MODALS ================= --}}
        @include('admin.forms.b5_moderator.partials._allow_edit_modal', ['submission' => $submission])
        @include('admin.forms.b5_moderator.partials._return_modal', ['submission' => $submission])

    </div>

</div>

@include('admin.forms.b5_moderator.partials._scripts')

</x-app-layout>