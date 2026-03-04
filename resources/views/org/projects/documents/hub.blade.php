<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6">

    @include('org.projects.documents.partials.header', [
        'project' => $project
    ])


    @if(session('error'))
        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('status'))
        <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            {{ session('status') }}
        </div>
    @endif


    @include('org.projects.documents.partials.section', [
        'title' => 'Pre-Implementation Documents',
        'forms' => $preForms,
        'phase' => 'pre'
    ])


    @if($budgetDocument)
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-start justify-between gap-4">

                <div>
                    <div class="text-base font-semibold text-slate-900">
                        Disbursement Voucher
                    </div>

                    <div class="mt-2 text-sm text-slate-700">
                        Generate a printable DV for submission to the Finance Office.
                    </div>

                    <div class="mt-1 text-xs text-slate-500">
                        Available once a Budget Proposal exists (draft/submitted/returned/approved).
                    </div>
                </div>

                <div>
                    <a href="{{ route('org.projects.disbursement-voucher.create', $project) }}"
                       class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Generate DV
                    </a>
                </div>

            </div>

        </div>
    @endif


    @include('org.projects.documents.partials.section', [
        'title' => 'Post-Implementation Documents',
        'forms' => $postForms,
        'phase' => 'post'
    ])

</div>

</x-app-layout>