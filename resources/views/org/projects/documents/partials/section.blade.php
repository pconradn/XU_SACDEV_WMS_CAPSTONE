<div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

    <div class="text-base font-semibold text-slate-900 mb-4">
        {{ $title }}
    </div>


    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        @forelse($forms as $form)

            @include('org.projects.documents.partials.form-card', [
                'form' => $form
            ])

        @empty

            <div class="text-sm text-slate-500">
                No forms configured.
            </div>

        @endforelse

    </div>

</div>

