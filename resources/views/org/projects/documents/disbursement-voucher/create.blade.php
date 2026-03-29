<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

{{-- ================= PAGE HEADER ================= --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-6 py-5">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

        <div>
            <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight">
                Disbursement Voucher
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Generate a disbursement voucher based on approved budget allocations and project expenses.
            </p>
        </div>

        <div class="text-xs text-slate-400 uppercase tracking-wide">
            Finance Module
        </div>

    </div>

</div>



{{-- ================= FORM ================= --}}
<form method="POST"
      action="{{ route('org.projects.documents.disbursement-voucher.generate', $project) }}"
      class="space-y-6">

@csrf


{{-- ================= VOUCHER INFO ================= --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
    @include('org.projects.documents.disbursement-voucher.partials._voucher_info')
</div>


{{-- ================= BUDGET ITEMS ================= --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
    @include('org.projects.documents.disbursement-voucher.partials._budget_items')
</div>


{{-- ================= ACTIONS ================= --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
    @include('org.projects.documents.disbursement-voucher.partials._actions')
</div>


</form>

</div>


{{-- ================= SCRIPTS ================= --}}
@include('org.projects.documents.disbursement-voucher.partials._script')

</x-app-layout>