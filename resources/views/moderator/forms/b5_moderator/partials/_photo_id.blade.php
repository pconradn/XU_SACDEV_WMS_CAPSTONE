<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-base font-semibold text-slate-900">Photo ID Upload</h3>
    <p class="mt-1 text-sm text-slate-600">Upload a clear photo of your valid ID.</p>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Photo ID</label>
            <input type="file" name="photo_id" accept=".jpg,.jpeg,.png,.webp"
                   class="mt-1 block w-full text-sm"
                   {{ $isLocked ? 'disabled' : '' }}>
            <p class="mt-1 text-xs text-slate-500">Accepted: JPG/PNG/WEBP • Max 4MB</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Current Upload</label>
            @if($submission->photo_id_path)
                <div class="mt-1 text-sm text-slate-800 break-all">
                    {{ $submission->photo_id_path }}
                </div>
                <p class="mt-1 text-xs text-slate-500">
                  
                </p>
            @else
                <div class="mt-1 text-sm text-slate-500">No file uploaded yet.</div>
            @endif
        </div>
    </div>
</div>
