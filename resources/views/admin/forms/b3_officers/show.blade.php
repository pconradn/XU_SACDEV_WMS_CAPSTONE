<x-app-layout>

    <div class="mx-auto max-w-6xl px-4 py-6">

    @include('admin.forms.b3_officers.partials._header')

        {{-- Edit request pending --}}
        @if($submission->edit_requested)

        <div class="mt-5 mb-4 rounded-2xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white p-3 shadow-sm">

            <div class="flex items-start gap-2.5">

                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-amber-200 bg-white text-amber-600">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>

                <div class="flex-1 space-y-2">

                    <div>
                        <div class="text-xs font-semibold text-amber-900">
                            Edit Request Pending
                        </div>

                        <div class="text-[11px] text-amber-800 mt-1 whitespace-pre-line leading-relaxed">
                            {{ $submission->edit_request_reason }}
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('admin.officer_submissions.allow_edit', $submission->id) }}"
                        class="space-y-2">

                        @csrf

                        <div>
                            <label class="block text-[11px] font-semibold text-amber-900">
                                SACDEV Note (optional)
                            </label>

                            <textarea name="sacdev_remarks"
                                    rows="2"
                                    class="mt-1 w-full rounded-lg border border-amber-300 bg-white px-2.5 py-1.5 text-[11px] text-slate-700
                                            focus:border-amber-400 focus:ring-1 focus:ring-amber-300 transition"></textarea>
                        </div>

                        <div class="flex justify-end">

                            <button type="submit"
                                    class="inline-flex items-center gap-1 rounded-md bg-amber-600 px-3 py-1.5 text-[11px] font-semibold text-white
                                        hover:bg-amber-700 transition shadow-sm">

                                <i data-lucide="corner-up-left" class="w-3 h-3"></i>
                                Allow Edit

                            </button>

                        </div>

                    </form>

                </div>

            </div>

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