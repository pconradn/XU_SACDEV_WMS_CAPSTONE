@php use Illuminate\Support\Str; @endphp

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>

    <div class="p-4 space-y-5">

        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Participants
                </h3>
                <p class="text-[11px] text-slate-500">
                    Define expected participants
                </p>
            </div>
        </div>

        <div class="rounded-xl border bg-white p-4 space-y-4
            {{ $errors->has('audience_type') ? 'border-rose-500 ring-2 ring-rose-300' : 'border-slate-200' }}">
            <div class="flex items-center gap-2">
                <i data-lucide="user-check" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Target Audience
                </span>
            </div>

            <p class="text-[11px] text-blue-600">
                Select the primary group your project is intended for
            </p>

            @php 
                $aud = old('audience_type', $proposal->audience_type ?? null); 
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 space-y-3 text-xs text-slate-700">

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="audience_type" value="xu_community"
                            class="{{ $errors->has('audience_type') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                            @checked($aud === 'xu_community')>
                        XU Community
                    </label>

                    @php 
                        $xuSubs = old('xu_subtypes') ?? 
                            (isset($proposal->xu_subtypes) ? explode(', ', $proposal->xu_subtypes) : []); 
                    @endphp

                    <div class="ml-5 grid grid-cols-1 md:grid-cols-2 gap-2" id="xuSubWrap">
                        @foreach(['Officers','Org Members','Non-Org Members','Faculty/Staff'] as $s)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                    name="xu_subtypes[]"
                                    value="{{ $s }}"
                                    class="{{ $errors->has('xu_subtypes') || $errors->has('xu_subtypes.*') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                                    @checked(in_array($s, $xuSubs, true))>
                                {{ $s }}
                            </label>
                        @endforeach
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="audience_type" value="non_xu_community"
                            class="border-slate-300 focus:ring-blue-500"
                            @checked($aud === 'non_xu_community')>
                        Non-XU Community
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="audience_type" value="beneficiaries"
                            class="border-slate-300 focus:ring-blue-500"
                            @checked($aud === 'beneficiaries')>
                        Beneficiaries
                    </label>

                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-700 mb-1">
                        Specify details
                    </label>

                    <p class="text-[11px] text-blue-600 mb-2">
                        Provide additional information for non-XU or beneficiary groups
                    </p>

                    <textarea name="audience_details"
                        rows="4"
                        class="w-full rounded-lg border px-3 py-2 text-xs 
                        {{ $errors->has('audience_details') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                        focus:ring-2 focus:outline-none transition"
                        placeholder="Details...">{{ old('audience_details', $proposal->audience_details ?? '') }}</textarea>
                </div>

            </div>

        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-4">

            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-blue-600"></i>
                <span class="text-[11px] font-semibold text-slate-700 uppercase tracking-wide">
                    Expected Participants
                </span>
            </div>

            <p class="text-[11px] text-blue-600">
                Estimate the number of participants for planning and budgeting
            </p>

            <div class="flex flex-wrap gap-6">

                <div>
                    <label class="block text-[11px] font-medium text-slate-700 mb-1">
                        Expected XU Participants
                    </label>
                    <input type="number"
                        min="0"
                        name="expected_xu_participants"
                        value="{{ old('expected_xu_participants', $proposal->expected_xu_participants ?? 0) }}"
                        class="w-28 rounded-lg border px-2 py-2 text-xs 
                        {{ $errors->has('expected_xu_participants') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                        focus:ring-2 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-slate-700 mb-1">
                        Expected Non-XU Participants
                    </label>
                    <input type="number"
                        min="0"
                        name="expected_non_xu_participants"
                        value="{{ old('expected_non_xu_participants', $proposal->expected_non_xu_participants ?? 0) }}"
                        class="w-28 rounded-lg border px-2 py-2 text-xs 
                        {{ $errors->has('expected_non_xu_participants') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
                        focus:ring-2 focus:outline-none transition">
                </div>

            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    function calculateTotalBudget() {
        let total = 0;

        document.querySelectorAll('.fund-amount').forEach(input => {
            const val = parseFloat(input.value);
            if (!isNaN(val)) {
                total += val;
            }
        });

        const totalInput = document.getElementById('totalBudget');
        if (totalInput) {
            totalInput.value = total.toFixed(2);
        }
    }

    document.addEventListener('input', (e) => {
        if (e.target.classList.contains('fund-amount')) {
            calculateTotalBudget();
        }
    });

    document.querySelectorAll('.fund-source-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const targetId = cb.dataset.target;
            const input = document.getElementById(targetId);

            if (!input) return;

            if (cb.checked) {
                input.classList.remove('hidden');
            } else {
                input.classList.add('hidden');
                input.value = '';
            }

            calculateTotalBudget();
        });
    });

    calculateTotalBudget();

});
</script>