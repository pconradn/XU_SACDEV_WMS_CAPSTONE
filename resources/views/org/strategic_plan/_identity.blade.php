<div
    x-data="{ editing: false, canEdit: false, status: '' }"
    x-init="
        canEdit = @js($canEdit);
        status = @js($submission->status);
        editing = canEdit && status === 'draft';
    "
>

<form method="POST" action="{{ route('org.rereg.b1.profile.save') }}" enctype="multipart/form-data">
@csrf

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
        <div class="flex items-start justify-between gap-4">

            <div class="space-y-2">
                <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    Organization Identity
                </div>
                <p class="text-xs text-slate-500">
                    Complete all required fields before proceeding
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">

                @php
                    $missing = empty($submission->org_name) || empty($submission->mission) || empty($submission->vision);
                @endphp

                <span class="inline-flex items-center gap-1 text-[10px] px-2.5 py-1 rounded-md font-semibold
                    {{ $missing ? 'bg-amber-100 text-amber-700 ring-1 ring-amber-200' : 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' }}">
                    <i data-lucide="{{ $missing ? 'alert-triangle' : 'check-circle' }}" class="w-3 h-3"></i>
                    {{ $missing ? 'Incomplete' : 'Complete' }}
                </span>

                @if($canEdit && $submission->status !== 'draft')

                    <button type="button"
                            x-show="!editing"
                            @click="editing = true"
                            class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                                   bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                        <i data-lucide="pencil" class="w-3 h-3"></i>
                        Edit
                    </button>

                    <button type="button"
                            x-show="canEdit && (editing || status === 'draft')"
                            @click="editing = false"
                            class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                                   bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        <i data-lucide="x" class="w-3 h-3"></i>
                        Cancel
                    </button>

                @endif

                @if($isApproved)
                    <span class="inline-flex items-center gap-1 text-[10px] px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 font-semibold ring-1 ring-emerald-200">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        Locked
                    </span>
                @endif

            </div>

        </div>
    </div>

    {{-- WARNING --}}
    @if($canEdit && !$isApproved && $submission->status !== 'draft')
        <div x-show="canEdit && (editing || status === 'draft')"
             x-transition
             class="px-5 py-3 bg-amber-50 border-b border-amber-200 text-[11px] text-amber-700 flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
            Editing this section will reset approval and require resubmission.
        </div>
    @endif

    <div class="px-5 py-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LOGO --}}
            <div class="lg:col-span-1">
                <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4 space-y-4">

                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        <i data-lucide="image" class="w-3.5 h-3.5"></i>
                        Organization Logo
                    </div>

                    @php
                        $logoPath = $submission->logo_path ?? $organization->logo_path ?? null;
                    @endphp

                    <div class="flex items-center justify-center rounded-xl border border-slate-200 bg-white h-40 overflow-hidden shadow-sm">
                        @if($logoPath)
                            <img id="logoPreview"
                                 src="{{ asset('storage/'.$logoPath) }}"
                                 class="max-h-32 object-contain transition">
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
                           :disabled="!canEdit || (!editing && status !== 'draft')"
                           class="block w-full text-xs text-slate-700 file:mr-2 file:py-1.5 file:px-3
                                  file:rounded-lg file:border-0 file:text-xs file:font-medium
                                  file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200
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
            <div class="lg:col-span-2 space-y-6">

                {{-- BASIC INFO --}}
                <div class="rounded-2xl border border-slate-200 p-4 bg-white space-y-4 shadow-sm">

                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Basic Information
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-xs font-medium text-slate-600">
                                Acronym <span class="text-rose-500">*</span>
                            </label>

                            <input type="text"
                                   name="org_acronym"
                                   value="{{ old('org_acronym', $submission->org_acronym ?? $organization->acronym ?? '') }}"
                                   :disabled="!canEdit || (!editing && status !== 'draft')"
                                   class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            @error('org_acronym')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div></div>

                        <div class="md:col-span-2">
                            <label class="text-xs font-medium text-slate-600">
                                Organization Name <span class="text-rose-500">*</span>
                            </label>
                        
                            <input type="text"
                                   name="org_name"
                                   value="{{ old('org_name', $submission->org_name ?: $organization->name) }}"
                                   :disabled="!canEdit || (!editing && status !== 'draft')"
                                   class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">

                            @error('org_name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>

                {{-- MISSION & VISION --}}
                <div class="rounded-2xl border border-slate-200 p-4 bg-white space-y-4 shadow-sm">

                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Organizational Direction
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-600">
                            Mission <span class="text-rose-500">*</span>
                        </label>

                        <textarea name="mission"
                                rows="3"
                                :disabled="!canEdit || (!editing && status !== 'draft')"
                                class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('mission', $submission->mission ?: ($organization->mission ?? '')) }}</textarea>
                        @error('mission')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-600">
                            Vision <span class="text-rose-500">*</span>
                        </label>

                        <textarea name="vision"
                                rows="3"
                                :disabled="!canEdit || (!editing && status !== 'draft')"
                                class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('vision', $submission->vision ?: ($organization->vision ?? '')) }}</textarea>
                                
                        @error('vision')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ACTION BAR --}}
    <div class="sticky bottom-0 bg-gradient-to-r from-white to-slate-50 border-t border-slate-200 px-5 py-4 flex justify-between items-center">

        <div class="text-[11px] text-slate-400">
            Changes are saved only when submitted
        </div>

        <button type="submit"
                x-show="canEdit && (editing || status === 'draft')"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg
                    bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
            <i data-lucide="save" class="w-3.5 h-3.5"></i>
            Save Profile
        </button>

    </div>

</div>
</form>

</div>

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