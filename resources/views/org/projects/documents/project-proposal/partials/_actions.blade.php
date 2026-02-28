<div class="border border-slate-300">

    <div class="px-4 py-3 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        
        <div class="text-[11px] text-slate-600">
            Saving will store this proposal as a draft. 
            Submitting will forward this document for approval and lock editing.
        </div>

       
        <div class="flex items-center gap-3">

           
            <a href="{{ route('org.projects.documents.hub', $project) }}"
               class="border border-slate-400 px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-100">
                Cancel
            </a>

            
            <button type="submit"
                    name="action"
                    value="draft"
                    class="border border-slate-500 px-4 py-2 text-[12px] text-slate-800 hover:bg-slate-100">
                Save as Draft
            </button>

          
            <button type="submit"
                    name="action"
                    value="submit"
                    class="bg-blue-900 px-4 py-2 text-[12px] text-white hover:bg-blue-800">
                Submit for Approval
            </button>

        </div>

    </div>

</div>