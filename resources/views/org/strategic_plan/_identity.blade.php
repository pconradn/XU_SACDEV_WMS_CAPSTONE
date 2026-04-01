<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
        <h2 class="text-sm font-semibold text-slate-900">
            Organization Identity
        </h2>
        <p class="text-xs text-slate-500 mt-1">
            Basic organization details for this school year
        </p>
    </div>

    <div class="px-6 py-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ================= LOGO CARD ================= --}}
            <div class="lg:col-span-1">

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-3">

                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Organization Logo
                    </div>

                    @php
                        $logoPath = $submission->logo_path ?? $organization->logo_path ?? null;
                    @endphp

                    {{-- PREVIEW --}}
                    <div class="flex items-center justify-center rounded-lg border border-slate-200 bg-white h-40">

                        @if($logoPath)
                            <img id="logoPreview"
                                 src="{{ asset('storage/'.$logoPath) }}"
                                 class="max-h-32 object-contain">
                        @else
                            <img id="logoPreview"
                                 src=""
                                 class="hidden max-h-32 object-contain">

                            <span id="logoPlaceholder"
                                  class="text-xs text-slate-400">
                                No logo uploaded
                            </span>
                        @endif

                    </div>

                    {{-- INPUT --}}
                    <input type="file"
                           name="logo"
                           accept="image/*"
                           onchange="previewLogo(event)"
                           class="block w-full text-xs text-slate-700 file:mr-2 file:py-1.5 file:px-3
                                  file:rounded-lg file:border-0 file:text-xs file:font-medium
                                  file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300
                                  border border-slate-200 rounded-lg">

                    <p class="text-[11px] text-slate-400">
                        PNG, JPG, WEBP • Max 2MB
                    </p>

                    @error('logo')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror

                </div>

            </div>

            {{-- ================= FORM ================= --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- BASIC INFO --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- ACRONYM --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Acronym
                        </label>

                        <input type="text"
                               name="org_acronym"
                               value="{{ old('org_acronym', $submission->org_acronym ?? $organization->acronym ?? '') }}"
                               class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                        <p class="text-[11px] text-slate-400 mt-1">
                            Editable if needed
                        </p>
                    </div>

                    {{-- EMPTY (spacing balance) --}}
                    <div></div>

                    {{-- NAME --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Organization Name
                        </label>

                        <input type="text"
                               name="org_name"
                               value="{{ old('org_name') !== null && old('org_name') !== '' 
                                    ? old('org_name') 
                                    : ($submission->org_name ?: $organization->name ?? '') }}"
                               class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                        @error('org_name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror

                        <p class="text-[11px] text-slate-400 mt-1">
                            Defaults to registered organization name
                        </p>
                    </div>

                </div>

                {{-- DIVIDER --}}
                <div class="border-t border-slate-200"></div>

                {{-- MISSION & VISION --}}
                <div class="space-y-4">

                    {{-- MISSION --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Mission
                        </label>

                        <textarea name="mission"
                                  rows="3"
                                  class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('mission', $submission->mission ?? $organization->mission ?? '') }}</textarea>

                        <p class="text-[11px] text-slate-400 mt-1">
                            Editable version of mission
                        </p>
                    </div>

                    {{-- VISION --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Vision
                        </label>

                        <textarea name="vision"
                                  rows="3"
                                  class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('vision', $submission->vision ?? $organization->vision ?? '') }}</textarea>

                        <p class="text-[11px] text-slate-400 mt-1">
                            Editable version of vision
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

const preview = document.getElementById('logoPreview');
const placeholder = document.getElementById('logoPlaceholder');
function previewLogo(event)
{
    const input = event.target;
    const container = input.closest('div'); // logo card

    const preview = container.querySelector('#logoPreview');
    const placeholder = container.querySelector('#logoPlaceholder');

    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(e)
    {
        preview.src = e.target.result;
        preview.classList.remove('hidden');

        if (placeholder) {
            placeholder.classList.add('hidden');
        }
    };

    reader.readAsDataURL(file);
}
</script>