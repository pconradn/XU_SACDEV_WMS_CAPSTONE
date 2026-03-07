@if($project->clearance_status === 'uploaded')

<div class="border border-slate-300 bg-white sticky bottom-0 z-50 shadow-md">

    <div class="px-4 py-3 flex items-center justify-end gap-3">

        <button
            type="button"
            onclick="openClearanceViewer()"
            class="border border-slate-400 px-4 py-2 text-[12px] hover:bg-slate-100">
            View Clearance
        </button>

    </div>

</div>

@endif



<div id="clearanceViewer"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-[90%] max-w-5xl rounded-lg shadow-lg">

        <div class="border-b px-4 py-3 flex items-center justify-between">

            <div class="font-semibold text-sm">
                Uploaded Clearance
            </div>

            <button
                onclick="closeClearanceViewer()"
                class="text-slate-500 hover:text-slate-800 text-lg">
                ×
            </button>

        </div>


        <div class="p-4">

            <iframe
                src="{{ asset('storage/'.$project->clearance_file_path) }}"
                class="w-full h-[600px] border">
            </iframe>

        </div>


        <div class="border-t px-4 py-3 flex items-center justify-between">

            <a href="{{ asset('storage/'.$project->clearance_file_path) }}"
               download
               class="border border-slate-400 px-4 py-2 text-[12px] hover:bg-slate-100">
                Download Clearance
            </a>


            <div class="flex gap-3">

                <form method="POST"
                      action="{{ route('admin.projects.clearance.verify', $project) }}">
                    @csrf

                    <button
                        class="bg-emerald-600 px-4 py-2 text-white text-[12px] hover:bg-emerald-700">
                        Approve Clearance
                    </button>

                </form>


                {{-- RETURN --}}
                <button
                    type="button"
                    onclick="openReturnModal()"
                    class="bg-rose-600 px-4 py-2 text-white text-[12px] hover:bg-rose-700">
                    Require New Upload
                </button>

            </div>

        </div>

    </div>

</div>



{{-- RETURN MODAL --}}
<div id="returnModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">

        <div class="border-b px-4 py-3 font-semibold text-sm">
            Return Clearance
        </div>

        <form method="POST"
              action="{{ route('admin.projects.clearance.return', $project) }}">

            @csrf

            <div class="p-4">

                <label class="block text-[12px] font-medium mb-2">
                    Remarks (required)
                </label>

                <textarea
                    name="remarks"
                    required
                    rows="4"
                    class="w-full border border-slate-300 px-3 py-2 text-[12px]">
                </textarea>

            </div>

            <div class="border-t px-4 py-3 flex justify-end gap-2">

                <button
                    type="button"
                    onclick="closeReturnModal()"
                    class="border border-slate-300 px-4 py-2 text-[12px] hover:bg-slate-100">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="bg-rose-600 text-white px-4 py-2 text-[12px] hover:bg-rose-500">
                    Return Clearance
                </button>

            </div>

        </form>

    </div>

</div>



<script>

function openClearanceViewer()
{
    const modal = document.getElementById('clearanceViewer');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeClearanceViewer()
{
    const modal = document.getElementById('clearanceViewer');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function openReturnModal()
{
    const modal = document.getElementById('returnModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReturnModal()
{
    const modal = document.getElementById('returnModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>