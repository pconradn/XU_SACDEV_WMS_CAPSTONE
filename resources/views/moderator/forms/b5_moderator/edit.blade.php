<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4">

            @include('moderator.forms.b5_moderator.partials._header', ['submission' => $submission])
          
            @if(!$isLocked && ($canUsePrevious ?? false))
                <div class="mt-3 rounded-xl border border-slate-200 bg-white p-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">
                                Use previous year’s data
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                                Copies your last school year B-5 details into the current draft (including leadership rows).
                                Review and update before submitting.
                                @if(!empty($previousSyId))
                                    <span class="text-slate-400">Previous SY ID: {{ $previousSyId }}</span>
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('moderator.b5.moderator.usePrevious') }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-900 hover:bg-indigo-100">
                                Use Previous Data
                            </button>
                        </form>
                    </div>
                </div>
            @endif


        </div>

        <form method="POST" action="{{ route('org.moderator.rereg.b5.saveDraft') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

                {{-- Left: Photo ID --}}
                <div class="h-full">
                    @include('moderator.forms.b5_moderator.partials._photo_id', [
                        'submission' => $submission,
                        'isLocked' => $isLocked
                    ])
                </div>


                {{-- Right: Personal Info --}}
                <div class="h-full">
                    @include('moderator.forms.b5_moderator.partials._personal_info', [
                        'submission' => $submission,
                        'isLocked' => $isLocked
                    ])
                </div>

            </div>
            
            @include('moderator.forms.b5_moderator.partials._employment_info', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._contact_info', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._leaderships', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._questions', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._skills', ['submission' => $submission, 'isLocked' => $isLocked])

            <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:flex-wrap">
                <button type="submit"
                        class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Save Draft
                </button>

                <button type="submit"
                        formaction="{{ route('org.moderator.rereg.b5.submit') }}"
                        class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Submit to SACDEV
                </button>

                {{-- Use Previous (near action buttons too - optional) --}}
                @if(!$isLocked && ($canUsePrevious ?? false))
                    <button type="submit"
                            formaction="{{ route('moderator.b5.moderator.usePrevious') }}"
                            class="inline-flex justify-center rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-900 hover:bg-indigo-100">
                        Use Previous Data
                    </button>
                @endif

                @if($submission->status === 'submitted_to_sacdev')
                    <button type="submit"
                            formaction="{{ route('org.moderator.rereg.b5.unsubmit') }}"
                            class="inline-flex justify-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                        Unsubmit
                    </button>
                @endif

                {{-- Request Edit (only when locked) --}}
                @if(in_array($submission->status, ['submitted_to_sacdev','approved_by_sacdev'], true))
                    @if($submission->edit_requested)
                        <div class="inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-900">
                            Edit request pending
                        </div>
                    @else

                    @endif
                @endif

                @if($isLocked)
                    <div class="text-sm text-slate-500 sm:ml-3">
                        This form is locked because it is already submitted or approved.
                    </div>
                @endif
            </div>

            {{-- Request Edit Modal --}}
            @include('moderator.forms.b5_moderator.partials._request_edit_modal', ['submission' => $submission])

    </div>

    @include('moderator.forms.b5_moderator.partials._scripts', ['isLocked' => $isLocked])
</x-app-layout>
