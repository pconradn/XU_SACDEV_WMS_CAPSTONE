@if(($registration->status ?? null) === 'submitted_to_sacdev')
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
        <div class="font-semibold">Need to change something?</div>
        <p class="mt-1 text-sm">Pull back the submission to edit and resubmit.</p>

        <form method="POST" action="{{ route('org.rereg.b3.officers-list.unsubmit') }}" class="mt-3">
            @csrf
            <button type="submit"
                    class="inline-flex justify-center rounded-lg border border-amber-300 bg-white px-4 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100">
                Pull Back Submission
            </button>
        </form>
    </div>
@endif