@php use Illuminate\Support\Str; @endphp

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- ACCENT LINE --}}
    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div>
            <h3 class="text-sm font-semibold text-slate-900">
                Participants
            </h3>
            <p class="text-xs text-blue-700">
                Define expected participants
            </p>
        </div>

        {{-- ================= TARGET AUDIENCE ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-3">
                Target Audience
            </div>

            @php 
                $aud = old('audience_type', $proposal->audience_type ?? null); 
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- LEFT --}}
                <div class="md:col-span-2 space-y-3 text-sm text-slate-700">

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="audience_type" value="xu_community"
                            class="border {{ $errors->has('audience_type') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
                            @checked($aud === 'xu_community')>
                        XU Community
                    </label>

                    @php 
                        $xuSubs = old('xu_subtypes') ?? 
                            (isset($proposal->xu_subtypes) ? explode(', ', $proposal->xu_subtypes) : []); 
                    @endphp

                    <div class="ml-6 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm" id="xuSubWrap">
                        @foreach(['Officers','Org Members','Non-Org Members','Faculty/Staff'] as $s)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                    name="xu_subtypes[]"
                                    value="{{ $s }}"
                                    class="border {{ $errors->has('xu_subtypes') || $errors->has('xu_subtypes.*') ? 'border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:ring-blue-500' }}"
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

                {{-- RIGHT --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Specify details
                    </label>

                    <textarea name="audience_details"
                        rows="4"
class="w-full rounded-lg border px-3 py-2 text-sm 
       {{ $errors->has('audience_details') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
       focus:ring-2 focus:outline-none transition"
                        placeholder="If non-XU or beneficiaries...">{{ old('audience_details', $proposal->audience_details ?? '') }}</textarea>
                </div>

            </div>
        </div>

        {{-- ================= EXPECTED PARTICIPANTS ================= --}}
        <div class="border border-slate-200 rounded-xl p-4">

            <div class="text-xs font-semibold text-slate-900 uppercase tracking-wide mb-4">
                Expected Participants
            </div>

            <div class="flex flex-wrap gap-6">

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Expected XU Participants
                    </label>
                    <input type="number"
                        min="0"
                        name="expected_xu_participants"
                        value="{{ old('expected_xu_participants', $proposal->expected_xu_participants ?? '') }}"
class="w-28 rounded-lg border px-2 py-2 text-sm 
       {{ $errors->has('expected_xu_participants') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
       focus:ring-2 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Expected Non-XU Participants
                    </label>
                    <input type="number"
                        min="0"
                        name="expected_non_xu_participants"
                        value="{{ old('expected_non_xu_participants', $proposal->expected_non_xu_participants ?? '') }}"
class="w-28 rounded-lg border px-2 py-2 text-sm 
       {{ $errors->has('expected_non_xu_participants') ? 'border-rose-500 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-blue-500 focus:border-blue-500' }} 
       focus:ring-2 focus:outline-none transition">
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= SCRIPT (UNCHANGED LOGIC) ================= --}}
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