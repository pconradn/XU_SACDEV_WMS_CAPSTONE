<div class="border border-slate-300 border-t-0 px-4 py-4">

<label class="block text-[10px] font-medium text-blue-900 italic">
Remarks
</label>

<textarea name="remarks"
          rows="3"
          class="mt-1 w-full border border-slate-300 px-3 py-1 text-[10px]"
          {{ $isReadOnly ? 'readonly' : '' }}>{{ old('remarks', $activity->remarks ?? '') }}</textarea>

</div>