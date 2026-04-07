<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">

        @include('org.forms.b3_officers.partials._header', [
            'targetSyId' => $targetSyId,
            'schoolYear' => $schoolYear ?? null,
            'submission' => $registration,
        ])

        @include('org.forms.b3_officers.partials._flash')

        @include('org.forms.b3_officers.partials._unsubmit', [
            'registration' => $registration,
        ])

  

        <form method="POST" action="{{ route('org.rereg.b3.officers-list.saveDraft') }}">
            @csrf
            <input type="hidden" name="certified" value="0">

            @include('org.forms.b3_officers.partials._major_officers', [
                'registration' => $registration,
                'isLocked' => $isLocked,
            ])

            @include('org.forms.b3_officers.partials._table', [
                'registration' => $registration,
                'isLocked' => $isLocked,
            ])

            @include('org.forms.b3_officers.partials._certification', [
                'registration' => $registration,
                'isLocked' => $isLocked,
            ])

            @include('org.forms.b3_officers.partials._actions', [
                'isLocked' => $isLocked,
            ])
        </form>

        @include('org.forms.b3_officers.partials._edit_request', [
            'registration' => $registration,
        ])  
    </div>

    @include('org.forms.b3_officers.partials._scripts', [
        'isLocked' => $isLocked,
    ])
</x-app-layout>