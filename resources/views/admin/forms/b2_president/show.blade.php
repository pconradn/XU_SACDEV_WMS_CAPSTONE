<x-app-layout>
<div class="mx-auto max-w-6xl px-4 py-6">

    {{-- Header --}}
    @include('admin.forms.b2_president.partials._header', [
        'registration' => $registration
    ])

    {{-- Status banner --}}
    @include('admin.forms.b2_president.partials._status', [
        'registration' => $registration
    ])

    {{-- Alerts --}}
    @include('admin.forms.b2_president.partials._alerts')


    {{-- Main form content (READ-ONLY) --}}
    <div class="mt-6 space-y-6">

        {{-- Photo + Personal --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div>
                @include('admin.forms.b2_president.partials._photo_id', [
                    'registration' => $registration
                ])
            </div>

            <div>
                @include('admin.forms.b2_president.partials._personal', [
                    'registration' => $registration
                ])
            </div>

        </div>


        {{-- Contact --}}
        @include('admin.forms.b2_president.partials._contact', [
            'registration' => $registration
        ])


        {{-- Family --}}
        @include('admin.forms.b2_president.partials._family', [
            'registration' => $registration
        ])


        {{-- Education --}}
        @include('admin.forms.b2_president.partials._education', [
            'registration' => $registration
        ])


        {{-- Secondary sections --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

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


 
    <div class="mt-8 pt-6 border-t border-slate-200">
        @include('admin.forms.b2_president.partials._actions', [
            'registration' => $registration
        ])
    </div>


</div>
</x-app-layout>