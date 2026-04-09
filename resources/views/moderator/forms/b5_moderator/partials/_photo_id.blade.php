<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>

            <h3 class="text-base font-semibold text-slate-900">
                Moderator Identification
            </h3>

            <p class="mt-1 text-sm text-slate-600 max-w-xl">
                Upload a clear photo.
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Accepted formats: JPG, PNG, WEBP • Maximum size: 4MB
            </p>

        </div>


        {{-- Status indicator --}}
        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">

                <span class="h-2.5 w-2.5 rounded-full
                    {{ $submission->photo_id_path ? 'bg-emerald-500' : 'bg-slate-400' }}">
                </span>

            </span>


        </div>

    </div>



    {{-- Preview Area --}}
    <div class="mt-5">

        <div class="flex items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 h-44 overflow-hidden">

            @if($submission->photo_id_path)

                <img id="moderatorPhotoPreview"
                     src="{{ asset('storage/'.$submission->photo_id_path) }}"
                     class="max-h-40 object-contain"
                     alt="Moderator ID Preview">

            @else

                <img id="moderatorPhotoPreview"
                     class="hidden max-h-40 object-contain"
                     alt="Preview">

                <span id="moderatorPhotoPlaceholder"
                      class="text-sm text-slate-400">

                    No ID uploaded yet

                </span>

            @endif

        </div>

    </div>



    {{-- Actions --}}
    <div class="mt-4 flex items-center justify-between flex-wrap gap-3">


        {{-- View Full --}}
        @if($submission->photo_id_path)

            <a href="{{ asset('storage/'.$submission->photo_id_path) }}"
               target="_blank"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">

                View Full Image

            </a>

        @endif



        {{-- Upload Button --}}
        <div class="flex items-center gap-3">

            <label class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 cursor-pointer
                {{ $isLocked ? 'opacity-50 cursor-not-allowed' : '' }}">

                {{ $submission->photo_id_path ? 'Replace ID' : 'Upload ID' }}

                <input type="file"
                       name="photo_id"
                       accept=".jpg,.jpeg,.png,.webp"
                       onchange="previewModeratorPhoto(event)"
                       class="hidden"
                       {{ $isLocked ? 'disabled' : '' }}>

            </label>


            @if($submission->photo_id_path)

                <span class="text-xs text-slate-500">
                    Uploading a new file will replace the existing one
                </span>

            @endif

        </div>


    </div>



    {{-- Error --}}
    @error('photo_id')

        <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
            {{ $message }}
        </div>

    @enderror



    {{-- Locked Notice --}}
    @if($isLocked)

        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">

            This submission has already been finalized and is locked.

        </div>

    @endif


</div>
<script>
function previewModeratorPhoto(event) {
    const input = event.target;
    const file = input.files[0];

    if (!file) return;

    const preview = document.getElementById('moderatorPhotoPreview');
    const placeholder = document.getElementById('moderatorPhotoPlaceholder');

    const reader = new FileReader();

    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden');

        if (placeholder) {
            placeholder.classList.add('hidden');
        }
    };

    reader.readAsDataURL(file);
}
</script>