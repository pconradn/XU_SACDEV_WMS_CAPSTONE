
<div
    x-data="projectsManager()"
    x-init="
        canEdit = @js($canEdit);
        status = @js($submission->status);
        editing = status === 'draft' ? true : false;
    "
    class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
>

dkauhdiouahsdojoawijdosdmaw

    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-sm font-semibold text-slate-900">Strategic Plan Projects</h2>
                <p class="text-xs text-slate-500 mt-1">ssAdd and manage projects</p>
            </div>

            <div class="flex items-center gap-2">
                @php
                    $projectCount = $submission->projects->count();
                @endphp

                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold {{ $projectCount > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $projectCount > 0 ? $projectCount . ' Project' . ($projectCount > 1 ? 's' : '') : 'No Projects Yet' }}
                </span>

                @if($canEdit && $submission->status !== 'draft')
                    <button type="button"
                            x-show="!editing"
                            @click="editing = true"
                            class="text-[11px] px-3 py-1.5 rounded-lg font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                        Enable Editing
                    </button>

                    <button type="button"
                            x-show="editing"
                            @click="editing = false; closeModal()"
                            class="text-[11px] px-3 py-1.5 rounded-lg font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        Cancel
                    </button>
                @endif

                @if($isApproved)
                    <span class="text-[10px] px-2 py-1 rounded-md bg-green-100 text-green-700 font-semibold">
                        Locked
                    </span>
                @endif
            </div>

        </div>
    </div>

    @if($canEdit && !$isApproved && $submission->status !== 'draft')
        <div x-show="editing"
             x-transition
             class="px-6 py-3 bg-amber-50 border-b border-amber-200 text-[11px] text-amber-700">
            Editing projects will reset approval and require resubmission.
        </div>
    @endif

    <div class="px-6 py-6 space-y-6">

        @foreach([
            'org_dev' => 'Organization Development',
            'student_services' => 'Student Services',
            'community_involvement' => 'Community Involvement'
        ] as $key => $label)

        <section class="rounded-xl border border-slate-200 overflow-hidden">

            <div class="flex justify-between items-center px-4 py-3 border-b bg-slate-50">
                <h3 class="text-sm font-semibold text-slate-900">{{ $label }}</h3>

                <button type="button"
                    x-show="canEdit && (editing || status === 'draft')"
                    @click="openCreate('{{ $key }}')"
                    class="bg-slate-900 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-slate-800 transition">
                    + Add Project
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <tbody>

                        @forelse($submission->projects->where('category', $key) as $project)

                        <tr class="border-t border-slate-200 align-top">
                            <td class="px-4 py-3 w-40 text-slate-600 text-xs sm:text-sm">
                                {{ $project->target_date ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $project->title }}</div>

                                <div class="mt-1 text-xs text-slate-500">
                                    ₱{{ number_format($project->budget, 2) }}
                                </div>

                                @if(!empty($project->implementing_body))
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $project->implementing_body }}
                                    </div>
                                @endif

                                @if(
                                    $project->objectives->count() ||
                                    $project->beneficiaries->count() ||
                                    $project->deliverables->count() ||
                                    $project->partners->count()
                                )
                                    <div class="mt-3 flex flex-wrap gap-2 text-[10px] font-medium">
                                        @if($project->objectives->count())
                                            <span class="px-2 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $project->objectives->count() }} Objective{{ $project->objectives->count() > 1 ? 's' : '' }}
                                            </span>
                                        @endif

                                        @if($project->beneficiaries->count())
                                            <span class="px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                {{ $project->beneficiaries->count() }} Beneficiar{{ $project->beneficiaries->count() > 1 ? 'ies' : 'y' }}
                                            </span>
                                        @endif

                                        @if($project->deliverables->count())
                                            <span class="px-2 py-1 rounded-md bg-violet-50 text-violet-700 border border-violet-100">
                                                {{ $project->deliverables->count() }} Deliverable{{ $project->deliverables->count() > 1 ? 's' : '' }}
                                            </span>
                                        @endif

                                        @if($project->partners->count())
                                            <span class="px-2 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-100">
                                                {{ $project->partners->count() }} Partner{{ $project->partners->count() > 1 ? 's' : '' }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">

                                    <button type="button"
                                        @click="openView(@js([
                                            'id' => $project->id,
                                            'category' => $project->category,
                                            'target_date' => $project->target_date,
                                            'title' => $project->title,
                                            'budget' => $project->budget,
                                            'implementing_body' => $project->implementing_body,
                                            'objectives' => $project->objectives->pluck('text')->values()->toArray(),
                                            'beneficiaries' => $project->beneficiaries->pluck('text')->values()->toArray(),
                                            'deliverables' => $project->deliverables->pluck('text')->values()->toArray(),
                                            'partners' => $project->partners->pluck('text')->values()->toArray(),
                                        ]))"
                                        class="border border-slate-300 bg-white px-3 py-1.5 text-xs rounded-lg hover:bg-slate-50 transition">
                                        View
                                    </button>

                                    <template x-if="canEdit && (editing || status === 'draft')">
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                @click="openEdit(@js([
                                                    'id' => $project->id,
                                                    'category' => $project->category,
                                                    'target_date' => $project->target_date,
                                                    'title' => $project->title,
                                                    'budget' => $project->budget,
                                                    'implementing_body' => $project->implementing_body,
                                                    'objectives' => $project->objectives->pluck('text')->values()->toArray(),
                                                    'beneficiaries' => $project->beneficiaries->pluck('text')->values()->toArray(),
                                                    'deliverables' => $project->deliverables->pluck('text')->values()->toArray(),
                                                    'partners' => $project->partners->pluck('text')->values()->toArray(),
                                                ]))"
                                                class="border border-slate-300 bg-white px-3 py-1.5 text-xs rounded-lg hover:bg-slate-50 transition">
                                                Edit
                                            </button>

                                            <form method="POST"
                                                  action="{{ route('org.rereg.b1.projects.delete', $project->id) }}"
                                                  class="inline"
                                                  onsubmit="return confirm('Delete this project?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="border border-rose-300 text-rose-600 bg-white px-3 py-1.5 text-xs rounded-lg hover:bg-rose-50 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </template>

                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-xs text-slate-400">
                                No projects yet
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </section>

        @endforeach

    </div>

    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">

        <div @click.outside="closeModal()"
             class="bg-white w-full max-w-2xl rounded-xl shadow-lg overflow-y-auto max-h-[90vh]">

            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-sm font-semibold text-slate-900"
                        x-text="viewMode ? 'View Project' : (isEdit ? 'Edit Project' : 'Add Project')"></h3>

                    <button type="button"
                            @click="closeModal"
                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Close
                    </button>
                </div>
            </div>

            <form method="POST" :action="formAction" class="p-6">
                @csrf

                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <input type="hidden" name="category" x-model="form.category">

                <div class="space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                Target Date
                            </label>
                            <input type="date"
                                   name="target_date"
                                   x-model="form.target_date"
                                   :disabled="viewMode"
                                   class="mt-1 w-full border border-slate-200 rounded-lg p-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                Budget <span class="text-rose-500">*</span>
                            </label>
                            <input type="number"
                                   name="budget"
                                   x-model="form.budget"
                                   :disabled="viewMode"
                                   placeholder="Budget"
                                   step="0.01"
                                   min="0"
                                   class="mt-1 w-full border border-slate-200 rounded-lg p-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               x-model="form.title"
                               :disabled="viewMode"
                               placeholder="Title"
                               class="mt-1 w-full border border-slate-200 rounded-lg p-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Implementing Body
                        </label>
                        <textarea name="implementing_body"
                                  x-model="form.implementing_body"
                                  :disabled="viewMode"
                                  rows="3"
                                  placeholder="Implementing Body"
                                  class="mt-1 w-full border border-slate-200 rounded-lg p-2 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4 space-y-4">

                        <div>
                            <div class="text-xs font-semibold mb-2 text-slate-700">Objectives</div>
                            <template x-for="(item, i) in form.objectives" :key="'objective-'+i">
                                <div class="flex gap-2 mb-2">
                                    <textarea
                                        :name="'objectives['+i+']'"
                                        x-model="form.objectives[i]"
                                        :disabled="viewMode"
                                        rows="1"
                                        @input="$el.style.height='auto'; $el.style.height=$el.scrollHeight+'px'"
                                        class="w-full border border-slate-200 rounded p-2 text-sm resize-none overflow-hidden focus:border-blue-500 focus:ring-blue-500">
                                    </textarea>
                                    <button type="button"
                                            x-show="!viewMode"
                                            @click="form.objectives.splice(i,1)"
                                            class="shrink-0 rounded-lg border border-slate-200 px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50 transition">
                                        ✕
                                    </button>
                                </div>
                            </template>
                            <button type="button"
                                    x-show="!viewMode"
                                    @click="form.objectives.push('')"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                + Add
                            </button>
                        </div>

                        <div>
                            <div class="text-xs font-semibold mb-2 text-slate-700">Beneficiaries</div>
                            <template x-for="(item, i) in form.beneficiaries" :key="'beneficiary-'+i">
                                <div class="flex gap-2 mb-2">
                                    <input type="text"
                                           :name="'beneficiaries['+i+']'"
                                           x-model="form.beneficiaries[i]"
                                           :disabled="viewMode"
                                           class="w-full border border-slate-200 rounded p-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button"
                                            x-show="!viewMode"
                                            @click="form.beneficiaries.splice(i,1)"
                                            class="shrink-0 rounded-lg border border-slate-200 px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50 transition">
                                        ✕
                                    </button>
                                </div>
                            </template>
                            <button type="button"
                                    x-show="!viewMode"
                                    @click="form.beneficiaries.push('')"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                + Add
                            </button>
                        </div>

                        <div>
                            <div class="text-xs font-semibold mb-2 text-slate-700">Deliverables</div>
                            <template x-for="(item, i) in form.deliverables" :key="'deliverable-'+i">
                                <div class="flex gap-2 mb-2">
                                    <input type="text"
                                           :name="'deliverables['+i+']'"
                                           x-model="form.deliverables[i]"
                                           :disabled="viewMode"
                                           class="w-full border border-slate-200 rounded p-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button"
                                            x-show="!viewMode"
                                            @click="form.deliverables.splice(i,1)"
                                            class="shrink-0 rounded-lg border border-slate-200 px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50 transition">
                                        ✕
                                    </button>
                                </div>
                            </template>
                            <button type="button"
                                    x-show="!viewMode"
                                    @click="form.deliverables.push('')"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                + Add
                            </button>
                        </div>

                        <div>
                            <div class="text-xs font-semibold mb-2 text-slate-700">Partners</div>
                            <template x-for="(item, i) in form.partners" :key="'partner-'+i">
                                <div class="flex gap-2 mb-2">
                                    <input type="text"
                                           :name="'partners['+i+']'"
                                           x-model="form.partners[i]"
                                           :disabled="viewMode"
                                           class="w-full border border-slate-200 rounded p-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button"
                                            x-show="!viewMode"
                                            @click="form.partners.splice(i,1)"
                                            class="shrink-0 rounded-lg border border-slate-200 px-2.5 py-1 text-xs text-slate-600 hover:bg-slate-50 transition">
                                        ✕
                                    </button>
                                </div>
                            </template>
                            <button type="button"
                                    x-show="!viewMode"
                                    @click="form.partners.push('')"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                + Add
                            </button>
                        </div>

                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-slate-200 pt-4">
                    <button type="button"
                            @click="closeModal"
                            class="text-xs px-3 py-2 border border-slate-200 rounded-lg bg-white text-slate-700 hover:bg-slate-50 transition">
                        <span x-text="viewMode ? 'Close' : 'Cancel'"></span>
                    </button>
                    <button type="submit"
                            x-show="!viewMode"
                            class="bg-blue-600 text-white text-xs px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Save
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
function projectsManager() {
    return {
        showModal: false,
        isEdit: false,
        viewMode: false,
        formAction: '',
        canEdit: false,
        editing: false,
        status: '',
        form: {
            id: null,
            category: '',
            target_date: '',
            title: '',
            budget: '',
            implementing_body: '',
            objectives: [],
            beneficiaries: [],
            deliverables: [],
            partners: []
        },

        blankForm(category = '') {
            return {
                id: null,
                category: category,
                target_date: '',
                title: '',
                budget: '',
                implementing_body: '',
                objectives: [],
                beneficiaries: [],
                deliverables: [],
                partners: []
            };
        },

        normalizeProject(project) {
            return {
                id: project.id ?? null,
                category: project.category ?? '',
                target_date: project.target_date ?? '',
                title: project.title ?? '',
                budget: project.budget ?? '',
                implementing_body: project.implementing_body ?? '',
                objectives: Array.isArray(project.objectives) ? [...project.objectives] : [],
                beneficiaries: Array.isArray(project.beneficiaries) ? [...project.beneficiaries] : [],
                deliverables: Array.isArray(project.deliverables) ? [...project.deliverables] : [],
                partners: Array.isArray(project.partners) ? [...project.partners] : []
            };
        },

        openCreate(category) {
            if ((!this.editing && this.status !== 'draft') || !this.canEdit) return;

            this.viewMode = false;
            this.isEdit = false;
            this.form = this.blankForm(category);
            this.formAction = "{{ route('org.rereg.b1.projects.store') }}";
            this.showModal = true;
        },

        openEdit(project) {
            if ((!this.editing && this.status !== 'draft') || !this.canEdit) return;

            this.viewMode = false;
            this.isEdit = true;
            this.form = this.normalizeProject(project);
            this.formAction = `/org/rereg/b1/projects/${project.id}`;
            this.showModal = true;
        },

        openView(project) {
            this.viewMode = true;
            this.isEdit = false;
            this.form = this.normalizeProject(project);
            this.formAction = '';
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        }
    }
}
</script>
