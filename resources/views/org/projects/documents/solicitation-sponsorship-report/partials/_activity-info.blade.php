<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-4 space-y-6">

        {{-- HEADER --}}
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                    Solicitation Activity Information
                </h3>
                <p class="text-xs text-blue-700 mt-1">
                    Provide details about the activity where solicitation was conducted, including purpose, duration, and financial summary.
                </p>
            </div>
        </div>

        @php
            $application = $project->documents?->where('form_type_id', 4)->first()->solicitationData;
            $activityName = old('activity_name', $data->activity_name ?? $project->title);
            $purpose = old('purpose', $data->purpose ?? $application->purpose ?? '');
            $from = old('solicitation_from', $data->solicitation_from ?? $application->duration_from ?? '');
            $to = old('solicitation_to', $data->solicitation_to ?? $application->duration_to ??  '');
            $letters = old('approved_letters_distributed', $data->approved_letters_distributed ?? '');
        @endphp

        {{-- CONTENT --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-5 hover:bg-slate-50 transition">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- NAME --}}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Name of Activity
                    </label>

                    <input
                        type="text"
                        name="activity_name"
                        value="{{ $activityName }}"
                        placeholder="Enter activity name"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('activity_name')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                {{-- PURPOSE --}}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Purpose of Solicitation
                    </label>

                    <textarea
                        name="purpose"
                        rows="3"
                        placeholder="Explain purpose (e.g., fundraising, support for project expenses)"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('purpose')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">{{ $purpose }}</textarea>
                </div>

                {{-- DATES --}}
                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Solicitation From
                    </label>

                    <input
                        type="date"
                        name="solicitation_from"
                        value="{{ $from }}"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('solicitation_from')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Solicitation To
                    </label>

                    <input
                        type="date"
                        name="solicitation_to"
                        value="{{ $to }}"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('solicitation_to')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                {{-- LETTERS --}}
                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Approved Letters Distributed
                    </label>

                    <input
                        type="number"
                        name="approved_letters_distributed"
                        value="{{ $letters }}"
                        placeholder="Enter number"
                        class="w-full rounded-lg px-3 py-2 text-xs
                            {{ $errors->has('approved_letters_distributed')
                                ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500'
                                : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }}
                            focus:ring-2 focus:outline-none transition">
                </div>

                {{-- TOTAL --}}
                <div>
                    <label class="block text-[11px] font-medium text-slate-600 mb-1">
                        Total Amount Raised (₱)
                    </label>

                    <input
                        type="text"
                        id="totalAmountRaised"
                        readonly
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-right">
                </div>

            </div>

        </div>

    </div>

</div>

<script>
function parseCurrency(value) {
    if (!value) return 0;
    return parseFloat(value.toString().replace(/,/g, '')) || 0;
}

function formatCurrency(value) {
    if (!value || isNaN(value)) return '0.00';
    return Number(value).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function updateTotalAmountRaised() {

    let total = 0;

    document.querySelectorAll('[name*="[amount]"]').forEach(field => {
        total += parseCurrency(field.value);
    });

    const totalField = document.getElementById('totalAmountRaised');

    if (totalField) {
        totalField.value = formatCurrency(total);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    updateTotalAmountRaised();
});
</script>