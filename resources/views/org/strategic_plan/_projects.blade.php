<div x-data="projectsManager()" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">Strategic Plan Projects</h2>
            <p class="text-xs text-slate-500 mt-1">Add and manage projects</p>
        </div>
    </div>

    <div class="px-6 py-6 space-y-6">

        @foreach([
            'org_dev' => 'Organization Development',
            'student_services' => 'Student Services',
            'community_involvement' => 'Community Involvement'
        ] as $key => $label)

        <section class="rounded-xl border border-slate-200">

            <div class="flex justify-between items-center px-4 py-3 border-b bg-slate-50">
                <h3 class="text-sm font-semibold">{{ $label }}</h3>

                <button type="button"
                    @click="openCreate('{{ $key }}')"
                    class="bg-slate-900 text-white text-xs px-3 py-1.5 rounded-lg">
                    + Add Project
                </button>
            </div>

            <table class="w-full text-sm">
                <tbody>

                    @forelse($submission->projects->where('category', $key) as $project)

                    <tr class="border-t">
                        <td class="px-4 py-3 w-40 text-slate-600">
                            {{ $project->target_date ?? '—' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $project->title }}</div>
                            <div class="text-xs text-slate-500">
                                ₱{{ number_format($project->budget, 2) }}
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center space-x-2">

                            <button type="button"
                                @click="openEdit(@js([
                                    'id' => $project->id,
                                    'category' => $project->category,
                                    'target_date' => $project->target_date,
                                    'title' => $project->title,
                                    'budget' => $project->budget,
                                    'implementing_body' => $project->implementing_body,
                                    'objectives' => $project->objectives->pluck('text'),
                                    'beneficiaries' => $project->beneficiaries->pluck('text'),
                                    'deliverables' => $project->deliverables->pluck('text'),
                                    'partners' => $project->partners->pluck('text'),
                                ]))"
                                class="border px-3 py-1 text-xs rounded-lg">
                                Edit
                            </button>

                            <form method="POST"
                                  action="{{ route('org.rereg.b1.projects.delete', $project->id) }}"
                                  class="inline">
                                @csrf
                                @method('DELETE')

                                <button class="border border-rose-300 text-rose-600 px-3 py-1 text-xs rounded-lg">
                                    Delete
                                </button>
                            </form>

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

        </section>

        @endforeach

    </div>

    {{-- MODAL --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 overflow-y-auto max-h-[90vh]">

            <h3 class="text-sm font-semibold mb-4" x-text="isEdit ? 'Edit Project' : 'Add Project'"></h3>

            <form method="POST" :action="formAction">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <input type="hidden" name="category" x-model="form.category">

                <div class="space-y-4">

                    <input type="date" name="target_date" x-model="form.target_date" class="w-full border rounded-lg p-2 text-sm">

                    <input type="text" name="title" x-model="form.title" placeholder="Title" class="w-full border rounded-lg p-2 text-sm">

                    <input type="number" name="budget" x-model="form.budget" placeholder="Budget" class="w-full border rounded-lg p-2 text-sm">

                    <textarea name="implementing_body" x-model="form.implementing_body" placeholder="Implementing Body" class="w-full border rounded-lg p-2 text-sm"></textarea>

                    <!-- OBJECTIVES -->
                    <div>
                        <div class="text-xs font-semibold mb-1">Objectives</div>
                        <template x-for="(item, i) in form.objectives">
                            <div class="flex gap-2 mb-1">
                                <input type="text" :name="'objectives['+i+']'" x-model="form.objectives[i]" class="w-full border rounded p-1 text-sm">
                                <button type="button" @click="form.objectives.splice(i,1)">✕</button>
                            </div>
                        </template>
                        <button type="button" @click="form.objectives.push('')" class="text-xs text-blue-600">+ Add</button>
                    </div>

                    <!-- BENEFICIARIES -->
                    <div>
                        <div class="text-xs font-semibold mb-1">Beneficiaries</div>
                        <template x-for="(item, i) in form.beneficiaries">
                            <div class="flex gap-2 mb-1">
                                <input type="text" :name="'beneficiaries['+i+']'" x-model="form.beneficiaries[i]" class="w-full border rounded p-1 text-sm">
                                <button type="button" @click="form.beneficiaries.splice(i,1)">✕</button>
                            </div>
                        </template>
                        <button type="button" @click="form.beneficiaries.push('')" class="text-xs text-blue-600">+ Add</button>
                    </div>

                    <!-- DELIVERABLES -->
                    <div>
                        <div class="text-xs font-semibold mb-1">Deliverables</div>
                        <template x-for="(item, i) in form.deliverables">
                            <div class="flex gap-2 mb-1">
                                <input type="text" :name="'deliverables['+i+']'" x-model="form.deliverables[i]" class="w-full border rounded p-1 text-sm">
                                <button type="button" @click="form.deliverables.splice(i,1)">✕</button>
                            </div>
                        </template>
                        <button type="button" @click="form.deliverables.push('')" class="text-xs text-blue-600">+ Add</button>
                    </div>

                    <!-- PARTNERS -->
                    <div>
                        <div class="text-xs font-semibold mb-1">Partners</div>
                        <template x-for="(item, i) in form.partners">
                            <div class="flex gap-2 mb-1">
                                <input type="text" :name="'partners['+i+']'" x-model="form.partners[i]" class="w-full border rounded p-1 text-sm">
                                <button type="button" @click="form.partners.splice(i,1)">✕</button>
                            </div>
                        </template>
                        <button type="button" @click="form.partners.push('')" class="text-xs text-blue-600">+ Add</button>
                    </div>

                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" @click="closeModal" class="text-xs px-3 py-1 border rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white text-xs px-4 py-1 rounded-lg">Save</button>
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
        formAction: '',
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

        openCreate(category) {
            this.isEdit = false;
            this.form = {
                category,
                target_date:'',
                title:'',
                budget:'',
                implementing_body:'',
                objectives:[],
                beneficiaries:[],
                deliverables:[],
                partners:[]
            };
            this.formAction = "{{ route('org.rereg.b1.projects.store') }}";
            this.showModal = true;
        },

        openEdit(project) {
            this.isEdit = true;
            this.form = project;
            this.formAction = `/org/rereg/b1/projects/${project.id}`;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        }
    }
}
</script>