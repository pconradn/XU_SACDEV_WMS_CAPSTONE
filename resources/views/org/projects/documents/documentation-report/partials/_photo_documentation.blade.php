<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="h-1 bg-blue-500"></div>

    <div class="p-5 space-y-6">

        <div>
            <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
                Photo Documentation
            </h3>
            <p class="text-xs text-blue-700 mt-1">
                Upload supporting photo evidence of the project implementation.
            </p>
        </div>


        <div class="border border-slate-200 rounded-xl p-4 space-y-4">

            {{-- HELPER --}}
            <div class="text-xs text-slate-600 leading-relaxed bg-slate-50 border border-slate-200 rounded-lg p-3">
                Upload a compiled <span class="font-semibold text-slate-800">PDF file</span> containing photos of the project.
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
                    class="mt-2 w-full text-sm rounded-lg border
                        {{ $errors->has('photo_document')
                            ? 'border-rose-500 focus:ring-rose-500'
                            : 'border-slate-300 focus:ring-blue-500' }}
                        px-3 py-2 bg-white focus:ring-2 focus:outline-none
                        file:mr-3 file:px-3 file:py-1.5 file:rounded-md file:border-0
                        file:text-xs file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                
                <p class="text-[11px] text-slate-400 mt-1">
                    Only PDF files are allowed.
                </p>
            </div>


            {{-- EXISTING FILE --}}
            @if(isset($report) && $report->photo_document_path)
            <div class="flex items-center justify-between border border-emerald-200 bg-emerald-50 rounded-lg px-3 py-2">

                <div class="text-xs text-emerald-700 font-medium">
                    Uploaded File Available
                </div>

                <a href="{{ Storage::url($report->photo_document_path) }}"
                    target="_blank"
                    class="text-xs font-semibold text-emerald-700 hover:underline">
                    View Document
                </a>

            </div>
            @endif


            {{-- NOTE --}}
            <p class="text-[11px] text-slate-400">
                Ensure that the uploaded file clearly documents the project activities for validation and evaluation.
            </p>

        </div>

    </div>

</div>