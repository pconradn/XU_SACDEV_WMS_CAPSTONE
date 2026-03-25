<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">

        @include('org.forms.b2_president.partials._header', ['submission' => $registration])


        @if($registration->status === 'submitted_to_sacdev')
            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                
                <div class="font-semibold">Need to change something?</div>

                <p class="mt-1 text-sm">
                    You can pull back the submission to edit the form, then submit again.
                </p>

                <form method="POST" action="{{ route('org.rereg.b2.president.unsubmit') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                            class="inline-flex justify-center rounded-lg border border-amber-300 bg-white px-4 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100">
                        Pull Back Submission
                    </button>
                </form>

            </div>
        @endif

        <form method="POST" action="{{ route('org.rereg.b2.president.saveDraft') }}" enctype="multipart/form-data">
            @csrf

            {{-- Default false for checkbox --}}
            <input type="hidden" name="certified" value="0">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Left column — Photo ID --}}
                <div>
                    @include('org.forms.b2_president.partials._photo_id', [
                        'isLocked' => $isLocked
                    ])
                </div>


                {{-- Right column — Personal Info --}}
                <div>
                    @include('org.forms.b2_president.partials._personal_info', [
                        'isLocked' => $isLocked
                    ])
                </div>

            </div>
            @include('org.forms.b2_president.partials._contact_info', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._family_info', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._education_info', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._leaderships', ['isLocked' => $isLocked, 'leaderships' => $registration->leaderships])
            @include('org.forms.b2_president.partials._trainings', ['isLocked' => $isLocked, 'trainings' => $registration->trainings])
            @include('org.forms.b2_president.partials._awards', ['isLocked' => $isLocked, 'awards' => $registration->awards])
            @include('org.forms.b2_president.partials._skills', ['isLocked' => $isLocked])
            @include('org.forms.b2_president.partials._certification', ['isLocked' => $isLocked])

            <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center">
                <button type="submit"
                        class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Save Draft
                </button>

                <button type="submit"
                        formaction="{{ route('org.rereg.b2.president.submit') }}"
                        class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Submit to SACDEV
                </button>

                @if($isLocked)
                    <div class="text-sm text-slate-500 sm:ml-3">
                        This form is locked because it is already submitted or approved.
                    </div>
                @endif
            </div>
        </form>
    </div>

    @include('org.forms.b2_president.partials._scripts', ['isLocked' => $isLocked])
</x-app-layout>
