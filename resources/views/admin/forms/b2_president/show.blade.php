<x-app-layout>

<div class="mx-auto max-w-7xl px-4 py-6">

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">
            @include('admin.forms.b2_president.partials._header', [
                'registration' => $registration
            ])
        </div>

        {{-- STATUS + ALERTS --}}
        <div class="space-y-3">
            @include('admin.forms.b2_president.partials._status', [
                'registration' => $registration
            ])

            @include('admin.forms.b2_president.partials._alerts')
        </div>


        {{-- MAIN GRID --}}
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- PHOTO + PERSONAL --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    @include('admin.forms.b2_president.partials._photo_id', [
                        'registration' => $registration
                    ])

                    @include('admin.forms.b2_president.partials._personal', [
                        'registration' => $registration
                    ])
                </div>

                {{-- CONTACT --}}
                @include('admin.forms.b2_president.partials._contact', [
                    'registration' => $registration
                ])

                {{-- FAMILY --}}
                @include('admin.forms.b2_president.partials._family', [
                    'registration' => $registration
                ])

                {{-- EDUCATION --}}
                @include('admin.forms.b2_president.partials._education', [
                    'registration' => $registration
                ])

                {{-- SECONDARY --}}
                <div class="grid xl:grid-cols-2 gap-4">

                    @include('admin.forms.b2_president.partials._leaderships', [
                        'leaderships' => $registration->leaderships
                    ])

                    @include('admin.forms.b2_president.partials._trainings', [
                        'trainings' => $registration->trainings
                    ])

                    @include('admin.forms.b2_president.partials._awards', [
                        'awards' => $registration->awards
                    ])

                    @include('admin.forms.b2_president.partials._skills', [
                        'registration' => $registration
                    ])

                </div>

            </div>


            {{-- RIGHT ACTIONS (DESKTOP) --}}
            <div class="hidden lg:block">

                <div class="sticky top-6">

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                        <div class="px-4 py-3 border-b border-slate-200 flex items-center gap-2">
                            <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Actions
                            </span>
                        </div>

                        <div class="p-4">
                            @include('admin.forms.b2_president.partials._actions', [
                                'registration' => $registration
                            ])
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- MOBILE ACTIONS --}}
        <div class="lg:hidden">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                <div class="px-4 py-3 border-b border-slate-200 flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        Actions
                    </span>
                </div>

                <div class="p-4">
                    @include('admin.forms.b2_president.partials._actions', [
                        'registration' => $registration
                    ])
                </div>

            </div>

        </div>

    </div>

</div>

</x-app-layout>