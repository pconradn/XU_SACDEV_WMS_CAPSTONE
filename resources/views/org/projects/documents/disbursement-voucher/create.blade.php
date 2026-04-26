<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

<nav class="text-xs text-slate-500">
    <ol class="flex items-center gap-1.5">

        {{-- Organization --}}
        <li>
            <a href="{{ route('org.organization-info.show') }}"
               class="font-medium text-slate-600 hover:text-slate-900 transition">
                Organization
            </a>
        </li>

        <li class="text-slate-300">/</li>

        {{-- Projects --}}
        <li>
            <a href="{{ route('org.projects.index') }}"
               class="font-medium text-slate-600 hover:text-slate-900 transition">
                Projects
            </a>
        </li>

        <li class="text-slate-300">/</li>

        {{-- Document Hub --}}
        <li>
            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="font-medium text-slate-600 hover:text-slate-900 transition">
                Document Hub
            </a>
        </li>

        <li class="text-slate-300">/</li>

        {{-- CURRENT --}}
        <li class="text-slate-400">
            Generate DV
        </li>

    </ol>
</nav>

{{-- ================= PAGE HEADER ================= --}}
<div x-data="{ openGuide: false }"
     class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div class="flex items-start gap-3">

            <div class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 shadow-sm">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>

            <div>
                <h1 class="text-lg md:text-xl font-semibold text-slate-900 tracking-tight">
                    Disbursement Voucher
                </h1>

                <p class="text-xs text-slate-500 mt-1 max-w-md">
                    Generate a printable voucher from approved budget allocations and project expenses.
                </p>
            </div>

        </div>

        <div class="flex items-center gap-2">

            <button
                @click="openGuide = true"
                type="button"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                <i data-lucide="help-circle" class="w-3.5 h-3.5"></i>
                Guide
            </button>

            <span class="inline-flex items-center px-2 py-1 text-[10px] font-semibold rounded-full border border-slate-200 bg-white text-slate-500 uppercase tracking-wide">
                Finance Module
            </span>

        </div>

    </div>

    <div
        x-show="openGuide"
        x-transition.opacity
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

        <div
            @click.outside="openGuide = false"
            x-transition
            class="w-full max-w-lg bg-white rounded-2xl shadow-lg border border-slate-200 p-5 space-y-4">

            <div class="flex items-start justify-between gap-3">

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Disbursement Voucher Guide
                    </h2>
                    <p class="text-[11px] text-slate-500">
                        Before generating your DV
                    </p>
                </div>

                <button
                    @click="openGuide = false"
                    class="text-slate-400 hover:text-slate-600 transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>

            </div>

            <div class="space-y-3 text-xs text-slate-700">

                <div class="flex items-start gap-2">
                    <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-500 mt-0.5"></i>
                    <p>
                        Generate a Disbursement Voucher using approved budget proposal data for printing purposes.
                    </p>
                </div>

                <div class="flex items-start gap-2">
                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-rose-500 mt-0.5"></i>
                    <p>
                        The system does not store any generated DV data. All outputs are temporary.
                    </p>
                </div>

                <div class="flex items-start gap-2">
                    <i data-lucide="file-check" class="w-3.5 h-3.5 text-emerald-500 mt-0.5"></i>
                    <p>
                        All vouchers must go through physical review and approval based on SACDEV procedures.
                    </p>
                </div>

                <div class="flex items-start gap-2">
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-500 mt-0.5"></i>
                    <p>
                        After generation, create a packet in your project hub under the Actions Panel and submit it to the SACDEV office.
                    </p>
                </div>

                <div class="flex items-start gap-2">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-slate-500 mt-0.5"></i>
                    <p>
                        You may also prepare your own voucher using the official SACDEV template if needed.
                    </p>
                </div>

            </div>

            <div class="flex justify-end pt-2">
                <button
                    @click="openGuide = false"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-900 text-white hover:bg-slate-700 transition">
                    Close
                </button>
            </div>

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