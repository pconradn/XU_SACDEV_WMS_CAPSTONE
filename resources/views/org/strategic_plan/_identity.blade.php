<form method="POST" action="{{ route('org.rereg.b1.profile.save') }}" enctype="multipart/form-data">
@csrf

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">
                    Organization Identity
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Complete all required fields before proceeding
                </p>
            </div>

            {{-- STATUS INDICATOR --}}
            @php
                $missing = empty($submission->org_name) || empty($submission->mission) || empty($submission->vision);
            @endphp

            <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold
                {{ $missing ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ $missing ? 'Incomplete' : 'Complete' }}
            </span>
        </div>
    </div>

    <div class="px-6 py-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LOGO --}}
            <div class="lg:col-span-1">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-3">

                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Organization Logo
                    </div>

                    @php
                        $logoPath = $submission->logo_path ?? $organization->logo_path ?? null;
                    @endphp

                    <div class="flex items-center justify-center rounded-lg border border-slate-200 bg-white h-40 overflow-hidden">
                        @if($logoPath)
                            <img id="logoPreview"
                                 src="{{ asset('storage/'.$logoPath) }}"
                                 class="max-h-32 object-contain">
                        @else
                            <img id="logoPreview"
                                 class="hidden max-h-32 object-contain">

                            <span id="logoPlaceholder"
                                  class="text-xs text-slate-400">
                                No logo uploaded
                            </span>
                        @endif
                    </div>

                    <input type="file"
                           name="logo"
                           accept="image/*"
                           onchange="previewLogo(event)"
                           class="block w-full text-xs text-slate-700 file:mr-2 file:py-1.5 file:px-3
                                  file:rounded-lg file:border-0 file:text-xs file:font-medium
                                  file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300
                                  border border-slate-200 rounded-lg">

                    <p class="text-[11px] text-slate-400">
                        PNG, JPG • Max 2MB
                    </p>

                    @error('logo')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- FORM --}}
            <div class="lg:col-span-2 space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- ACRONYM --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Acronym <span class="text-rose-500">*</span>
                        </label>

                        <input type="text"
                               name="org_acronym"
                               value="{{ old('org_acronym', $submission->org_acronym ?? $organization->acronym ?? '') }}"
                               class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                        @error('org_acronym')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div></div>

                    {{-- NAME --}}
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Organization Name <span class="text-rose-500">*</span>
                        </label>

                        <input type="text"
                               name="org_name"
                               value="{{ old('org_name', $submission->org_name ?? $organization->name ?? '') }}"
                               class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                        @error('org_name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="border-t border-slate-200"></div>

                <div class="space-y-4">

                    {{-- MISSION --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Mission <span class="text-rose-500">*</span>
                        </label>

                        <textarea name="mission"
                                  rows="3"
                                  class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('mission', $submission->mission ?? '') }}</textarea>

                        @error('mission')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- VISION --}}
                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Vision <span class="text-rose-500">*</span>
                        </label>

                        <textarea name="vision"
                                  rows="3"
                                  class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('vision', $submission->vision ?? '') }}</textarea>

                        @error('vision')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ACTION BAR --}}
    <div class="sticky bottom-0 bg-white border-t border-slate-200 px-6 py-4 flex justify-end">
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg
                       bg-blue-600 text-white hover:bg-blue-700 transition">
            Save Profile
        </button>
    </div>

</div>
</form>
<script>
function previewLogo(event) {
    const file = event.target.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(e) {
        const preview = document.getElementById('logoPreview');
        const placeholder = document.getElementById('logoPlaceholder');

        preview.src = e.target.result;
        preview.classList.remove('hidden');

        if (placeholder) {
            placeholder.classList.add('hidden');
        }
    };

    reader.readAsDataURL(file);
}
</script>