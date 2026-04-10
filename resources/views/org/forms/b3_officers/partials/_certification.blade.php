<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5">

    <div class="flex items-start gap-3">
        <div class="mt-0.5">
            <i data-lucide="shield-check" class="w-4 h-4 text-slate-500"></i>
        </div>

        <div class="space-y-1">
            <div class="text-xs font-semibold text-slate-900">
                Certification
            </div>
            <div class="text-[11px] text-slate-500 leading-relaxed">
                By submitting this form, you confirm that all officer information provided is accurate and complete.
            </div>
        </div>
    </div>

    <div class="mt-4 flex items-center gap-2">

        <input type="checkbox"
               name="certified"
               value="1"
               class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
               {{ old('certified', (int) ($registration->certified ?? 0)) ? 'checked' : '' }}
               {{ !$canEdit ? 'disabled' : '' }}>

        <span class="text-xs text-slate-800">
            I certify that the information above is true and correct
        </span>

    </div>

</div>

@if(!$canEdit)
<div class="mt-2 text-[10px] text-slate-500">
    Certification is locked because editing is disabled
</div>
@endif