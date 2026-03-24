<div class="flex items-center gap-2">
    @if($isPresident)
        <button
            type="button"
            @click="openCreateModal = true"
            class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800 transition"
        >
            + Add Project
        </button>
    @endif
</div>