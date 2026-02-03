<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-slate-900">Photo Identification</h3>
            <p class="mt-1 text-sm text-slate-600">
                Upload a clear photo/scan of a valid ID of the incoming president.
                Accepted: JPG/PNG/WEBP (max 4MB).
            </p>
        </div>

        @if($registration->photo_id_path)
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-inset ring-emerald-200">
                Uploaded
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 ring-1 ring-inset ring-amber-200">
                Not yet uploaded
            </span>
        @endif
    </div>

    @if($registration->photo_id_path)
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <a href="{{ asset('storage/' . $registration->photo_id_path) }}"
               target="_blank"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                View Uploaded ID
            </a>

            <div class="text-xs text-slate-500">
                Uploading a new file will replace the existing one.
            </div>
        </div>
    @endif

    <div class="mt-4">
        <input type="file"
               name="photo_id"
               accept=".jpg,.jpeg,.png,.webp"
               class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800
                      @error('photo_id') border-red-300 ring-1 ring-red-200 @enderror"
               {{ $isLocked ? 'disabled' : '' }}>

        @error('photo_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if($isLocked)
            <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                This form is already submitted/approved. Upload is locked.
            </div>
        @endif
    </div>
</div>
