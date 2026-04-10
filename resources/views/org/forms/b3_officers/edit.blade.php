<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6 space-y-4">

        {{-- HEADER --}}
        @include('org.forms.b3_officers.partials._header', [
            'targetSyId' => $targetSyId,
            'schoolYear' => $schoolYear ?? null,
            'submission' => $registration,
            'isPresident' => $isPresident,
            'canEdit' => $canEdit,
        ])

        {{-- UNSUBMIT --}}
        @if($registration && $registration->status !== 'draft')
            @include('org.forms.b3_officers.partials._unsubmit', [
                'registration' => $registration,
                'canEdit' => $canEdit,
            ])
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('org.rereg.b3.officers-list.saveDraft') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="certified" value="0">

            {{-- MAJOR OFFICERS --}}
            @include('org.forms.b3_officers.partials._major_officers', [
                'registration' => $registration,
                'isLocked' => $isLocked,
                'isPresident' => $isPresident,
                'canEdit' => $canEdit,
            ])

            {{-- OTHER OFFICERS TABLE --}}
            @include('org.forms.b3_officers.partials._table', [
                'registration' => $registration,
                'isLocked' => $isLocked,
                'isPresident' => $isPresident,
                'canEdit' => $canEdit,
            ])

            {{-- CERTIFICATION (ONLY PRESIDENT) --}}
            @if($isPresident)
                @include('org.forms.b3_officers.partials._certification', [
                    'registration' => $registration,
                    'isLocked' => $isLocked,
                    'canEdit' => $canEdit,
                ])
            @endif

            {{-- ACTIONS (ONLY PRESIDENT) --}}
            @if($isPresident)
                <div class="sticky bottom-4 z-10">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-md p-3 flex items-center justify-between">
                        <div class="text-[11px] text-slate-500">
                            Make sure all required officer details are complete before submission
                        </div>

                        @include('org.forms.b3_officers.partials._actions', [
                            'registration' => $registration,
                            'isLocked' => $isLocked,
                            'isPresident' => $isPresident,
                            'canEdit' => $canEdit,
                        ])
                    </div>
                </div>
            @endif

        </form>

        {{-- EDIT REQUEST --}}
        @if($registration && $registration->status === 'returned')
            <div class="rounded-2xl border border-rose-200 bg-gradient-to-b from-rose-50 to-white shadow-sm p-4">
                @include('org.forms.b3_officers.partials._edit_request', [
                    'registration' => $registration,
                    'canEdit' => $canEdit,
                ])
            </div>
        @endif

    </div>

    {{-- SCRIPTS --}}
    @include('org.forms.b3_officers.partials._scripts', [
        'isLocked' => $isLocked,
        'isPresident' => $isPresident,
        'canEdit' => $canEdit,
    ])
</x-app-layout>