<div class="border border-slate-300 bg-white mb-6">

    <div class="bg-slate-50 border-b border-slate-300 px-4 py-2">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            Cancellation Details
        </div>
    </div>

    <div class="px-4 py-4">

        <label class="block text-[10px] font-medium text-blue-900 italic">
            Reason for Cancellation
        </label>

        <textarea
        name="reason"
        rows="5"
        class="mt-1 w-full border border-slate-300 px-3 py-2 text-[12px]">{{ old('reason', $data->reason ?? '') }}</textarea>

    </div>

</div>