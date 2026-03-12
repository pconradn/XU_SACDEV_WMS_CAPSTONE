<div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Certification</h3>
    <p class="mt-1 text-sm text-slate-600">
        By submitting this form, I certify that the information provided is true and correct.
    </p>

    <div class="mt-3 flex items-center gap-2">
        <input type="checkbox" name="certified" value="1"
               class="h-4 w-4 rounded border-slate-300"
               {{ old('certified', (int) ($registration->certified ?? 0)) ? 'checked' : '' }}
               {{ $isLocked ? 'disabled' : '' }}>
        <span class="text-sm text-slate-800">I certify the information above.</span>
    </div>
</div>