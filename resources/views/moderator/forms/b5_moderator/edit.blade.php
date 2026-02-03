<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">
                B-5 Moderator Form
                <span class="text-slate-500 font-normal">
                    ({{ $term->organization->name ?? 'Organization' }} • Target SY: {{ $term->schoolYear->label ?? $term->school_year_id }})
                </span>
            </h2>
        </div>

        @include('moderator.forms.b5_moderator.partials._status_banner', ['submission' => $submission])

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                <div class="font-semibold">Success</div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Error</div>
                <div class="text-sm mt-1">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-900">
                <div class="font-semibold">Please fix the errors below.</div>
                <ul class="mt-2 list-disc pl-5 text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('moderator.b5.moderator.saveDraft') }}" enctype="multipart/form-data">
            @csrf

            @include('moderator.forms.b5_moderator.partials._photo_id', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._personal_info', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._employment_info', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._contact_info', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._leaderships', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._questions', ['submission' => $submission, 'isLocked' => $isLocked])
            @include('moderator.forms.b5_moderator.partials._skills', ['submission' => $submission, 'isLocked' => $isLocked])

            <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:items-center">
                <button type="submit"
                        class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Save Draft
                </button>

                <button type="submit"
                        formaction="{{ route('moderator.b5.moderator.submit') }}"
                        class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 disabled:opacity-50"
                        {{ $isLocked ? 'disabled' : '' }}>
                    Submit to SACDEV
                </button>

                @if($submission->status === 'submitted_to_sacdev')
                    <button type="submit"
                            formaction="{{ route('moderator.b5.moderator.unsubmit') }}"
                            class="inline-flex justify-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                        Unsubmit
                    </button>
                @endif

                @if($isLocked)
                    <div class="text-sm text-slate-500 sm:ml-3">
                        This form is locked because it is already submitted or approved.
                    </div>
                @endif
            </div>
        </form>
    </div>

    @include('moderator.forms.b5_moderator.partials._scripts', ['isLocked' => $isLocked])
</x-app-layout>
