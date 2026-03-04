@if($isProjectHead)

<div class="border border-slate-300 bg-white sticky bottom-0 shadow-md">

<div class="px-4 py-3 flex justify-end gap-3">

<button type="submit"
        name="action"
        value="draft"
        class="border border-slate-400 px-4 py-2 text-[12px] hover:bg-slate-100">

Save Draft

</button>

<button type="submit"
        name="action"
        value="submit"
        class="bg-blue-900 px-4 py-2 text-white text-[12px] hover:bg-blue-800">

Submit Budget

</button>

</div>

</div>

@endif
