<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <h3 class="text-base font-semibold text-slate-900">Certification</h3>

    <div class="mt-3 flex items-start gap-3">
        <input id="certified" type="checkbox" name="certified" value="1"
               class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-300"
               {{ old('certified', $registration->certified) ? 'checked' : '' }}
               {{ $isLocked ? 'disabled' : '' }}>

        <label for="certified" class="text-sm text-slate-700">
            By submitting this form to the Office of Student Affairs – Student Activities and Leadership Development (OSA-SACDEV),
            I hereby certify that the information contained herein is true and correct to the best of my knowledge.
        </label>
    </div>

    @error('certified')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
