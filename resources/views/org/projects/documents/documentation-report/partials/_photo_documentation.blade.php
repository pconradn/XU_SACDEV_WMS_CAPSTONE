<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Photo Documentation
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Upload supporting photo evidence of the project implementation.
        </p>
    </div>


    <div class="border border-slate-200 rounded-xl bg-white p-4 space-y-4">

        {{-- HELPER TEXT --}}
        <div class="text-xs text-slate-500 leading-relaxed">
            Upload a compiled <span class="font-semibold text-slate-700">PDF file</span> containing photos of the project.
            Each photo should include a short caption or description explaining the activity shown.
        </div>


        {{-- FILE INPUT --}}
        <div>
            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Upload Photo Documentation (PDF)
            </label>

            <input type="file"
                name="photo_document"
                accept=".pdf"
                class="mt-2 w-full text-sm border border-slate-200 rounded-md px-3 py-2 bg-white file:mr-3 file:px-3 file:py-1 file:rounded file:border-0 file:text-xs file:bg-blue-600 file:text-white hover:file:bg-blue-700">
        </div>


        {{-- EXISTING FILE --}}
        @if(isset($report) && $report->photo_document_path)

        <div class="border border-slate-200 rounded-lg bg-slate-50 px-3 py-2 text-sm flex items-center justify-between">

            <div class="text-slate-600">
                📄 Uploaded File Available
            </div>

            <a href="{{ Storage::url($report->photo_document_path) }}"
                target="_blank"
                class="text-blue-600 text-xs font-medium hover:underline">
                View Document
            </a>

        </div>

        @endif


        {{-- EXTRA NOTE --}}
        <p class="text-[11px] text-slate-400">
            Ensure that the uploaded file clearly documents the project activities for validation and evaluation.
        </p>

    </div>

</div>