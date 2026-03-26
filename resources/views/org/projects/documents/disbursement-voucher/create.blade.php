<x-layouts.form-only
    title="Disbursement Voucher — {{ $project->title }}"
    :backRoute="route('org.projects.documents.hub', $project)"
>

<div class="mx-auto max-w-5xl">

@include('org.projects.documents.disbursement-voucher.partials._header')

<form method="POST"
      action="{{ route('org.projects.documents.disbursement-voucher.generate', $project) }}">

@csrf

@include('org.projects.documents.disbursement-voucher.partials._voucher_info')

@include('org.projects.documents.disbursement-voucher.partials._budget_items')


@include('org.projects.documents.disbursement-voucher.partials._actions')

</form>

</div>

@include('org.projects.documents.disbursement-voucher.partials._script')

</x-layouts.form-only>