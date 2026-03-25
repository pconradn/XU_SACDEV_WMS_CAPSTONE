<div x-show="openAssignHeadModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50"
         @click="openAssignHeadModal = false"></div>

    <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">

        <h3 class="mb-4 text-lg font-semibold text-slate-900">
            Assign Project Head
        </h3>

        <form method="POST"
              :action="selectedProject ? `/org/assign-project-heads/${selectedProject.id}` : '#'">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="text-xs uppercase text-slate-500">Select Officer</label>

                    <select name="officer_id"
                            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            required>
                        <option value="">-- Select officer --</option>

                        @foreach($officers as $o)
                            <option value="{{ $o->id }}">
                                {{ $o->full_name }} ({{ $o->email }})
                            </option>
                        @endforeach
                    </select>

                    @error('officer_id')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button"
                        @click="openAssignHeadModal = false"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                        class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Update Project Head
                </button>
            </div>
        </form>

    </div>
</div>