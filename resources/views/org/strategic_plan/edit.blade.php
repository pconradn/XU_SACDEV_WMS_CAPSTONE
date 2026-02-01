<x-app-layout>
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="mb-1">Registration Form B-1: Strategic Plan</h2>
            <p class="text-muted mb-0">
                School Year: {{ $schoolYear?->name ?? 'N/A' }} |
                Status: <strong>{{ $submission->status }}</strong>
            </p>

            @if(session('success'))
                <div class="alert alert-success mt-3">{{ session('success') }}</div>
            @endif
        </div>
    </div>

    {{-- SAVE DRAFT --}}
    <form method="POST" action="{{ route('org.strategic_plan.draft') }}" enctype="multipart/form-data">
        @csrf

        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-3">Organization Identity</h4>

                <div class="mb-3">
                    <label class="form-label">Organization Logo</label>
                    <input type="file" name="logo" class="form-control">
                    @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Acronym</label>
                        <input type="text" name="org_acronym" class="form-control"
                               value="{{ old('org_acronym', $submission->org_acronym) }}">
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Complete Organization Name</label>
                        <input type="text" name="org_name" class="form-control"
                               value="{{ old('org_name', $submission->org_name) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mission</label>
                    <textarea name="mission" class="form-control" rows="3">{{ old('mission', $submission->mission) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Vision</label>
                    <textarea name="vision" class="form-control" rows="3">{{ old('vision', $submission->vision) }}</textarea>
                </div>
            </div>
        </div>

        {{-- TODO: Projects UI (dynamic add/remove) --}}
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-2">Projects</h4>
                <p class="text-muted mb-0">Next: we’ll build the dynamic project tables per category.</p>
            </div>
        </div>

        {{-- TODO: Fund sources UI --}}
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-2">Sources of Funds</h4>
                <p class="text-muted mb-0">Next: we’ll build the fund source rows + “other” rows.</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Save Draft</button>
        </div>
    </form>

    {{-- SUBMIT --}}
    <form method="POST" action="{{ route('org.strategic_plan.submit') }}" class="mt-3">
        @csrf
        <div class="card">
            <div class="card-body">
                <h4 class="mb-2">Submit to Moderator</h4>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="confirm" value="yes" id="confirmSubmit">
                    <label class="form-check-label" for="confirmSubmit">
                        I confirm that the Strategic Plan details are complete and ready for review.
                    </label>
                    @error('confirm') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-success" type="submit">Submit</button>
            </div>
        </div>
    </form>
</x-app-layout>
