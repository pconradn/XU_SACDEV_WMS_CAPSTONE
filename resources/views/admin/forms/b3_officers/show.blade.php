<x-app-layout>

    <div class="mx-auto max-w-6xl px-4 py-6">

    @include('admin.forms.b3_officers.partials._header')

        {{-- Edit request pending --}}
        @if($submission->edit_requested)

            <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-900">

                <div class="font-semibold">
                    Edit Request Pending
                </div>

                <div class="mt-2 text-sm whitespace-pre-line">
                    {{ $submission->edit_request_reason }}
                </div>


                <form method="POST"
                      action="{{ route('admin.officer_submissions.allow_edit', $submission->id) }}"
                      class="mt-4">

                    @csrf

                    <label class="block text-sm font-medium text-amber-900">
                        SACDEV Note (optional)
                    </label>

                    <textarea name="sacdev_remarks"
                              rows="3"
                              class="mt-1 w-full rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm"></textarea>

                    <button type="submit"
                            class="mt-3 inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Allow Edit (Return to Org)
                    </button>

                </form>

            </div>

        @endif



        {{-- Officers table --}}
        <div class="mb-4">

            @include('admin.forms.b3_officers.partials._officers_table', [
                'items' => $submission->items,
                'conflictsByItemId' => $conflictsByItemId ?? [],
            ])

        </div>




    @include('admin.forms.b3_officers.partials._actions')

    @include('admin.forms.b3_officers.partials._scripts')

</x-app-layout>