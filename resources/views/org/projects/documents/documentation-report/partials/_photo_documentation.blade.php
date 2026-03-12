<div class="border border-slate-300">

    <div class="flex justify-start px-4 pt-1">
        <div class="text-[12px] font-medium text-slate-700">
            Photo Documentation
        </div>
    </div>

    <div class="px-4 py-3 space-y-3">

        <div class="text-[10px] italic text-blue-900">
            Upload a compiled photo documentation file (PDF).  
            Include captions and brief descriptions for each photo.
        </div>

        <input type="file"
               name="photo_document"
               accept=".pdf"
               class="w-full border border-slate-300 px-3 py-1 text-[12px]">

        @if(isset($report) && $report->photo_document_path)

        <div class="text-[11px] text-slate-700">

            Current file:

            <a href="{{ Storage::url($report->photo_document_path) }}"
               target="_blank"
               class="text-blue-700 underline">
               View Uploaded Document
            </a>

        </div>

        @endif

    </div>

</div>