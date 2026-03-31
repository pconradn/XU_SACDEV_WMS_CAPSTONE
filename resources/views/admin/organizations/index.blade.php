<x-app-layout>

@php
$clusterColors = [
    1 => 'bg-blue-100 text-blue-700 border-blue-200', // Business
    2 => 'bg-green-100 text-green-700 border-green-200', // Environment
    3 => 'bg-amber-100 text-amber-700 border-amber-200', // Food
    4 => 'bg-purple-100 text-purple-700 border-purple-200', // Governance
    5 => 'bg-pink-100 text-pink-700 border-pink-200', // Media
    6 => 'bg-indigo-100 text-indigo-700 border-indigo-200', // Tech
    7 => 'bg-cyan-100 text-cyan-700 border-cyan-200', // Program
    8 => 'bg-emerald-100 text-emerald-700 border-emerald-200', // Service
    9 => 'bg-orange-100 text-orange-700 border-orange-200', // Socio
    10 => 'bg-red-100 text-red-700 border-red-200', // Sports
];
@endphp

<div x-data="organizationModal()" class="space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-6 py-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Organization Management
                </h1>
                <p class="text-sm text-slate-500">
                    Manage organizations and access their hubs.
                </p>
            </div>

            <button @click="openCreate()"
                class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                + Add Organization
            </button>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-4 py-3">

        <div class="flex items-center gap-3">

            {{-- SEARCH ICON --}}
            <div class="text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m1.85-5.65a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>
                </svg>
            </div>

            {{-- INPUT --}}
            <input
                type="text"
                x-model="search"
                placeholder="Search organizations by name or acronym..."
                class="w-full text-sm border-0 focus:ring-0 placeholder:text-slate-400"
            >

            {{-- CLEAR --}}
            <button
                x-show="search.length > 0"
                @click="search = ''"
                class="text-slate-400 hover:text-slate-600 transition"
            >
                ✕
            </button>

        </div>

    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @foreach ($organizations as $org)

        <div
            x-show="match('{{ strtolower($org->name . ' ' . ($org->acronym ?? '')) }}')"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition"
        >

            {{-- TOP --}}
            <div class="flex items-center gap-3">

                {{-- LOGO --}}
                <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden flex items-center justify-center shrink-0">

                    @if($org->logo_path)
                        <img src="{{ asset('storage/' . $org->logo_path) }}"
                            class="w-full h-full object-cover">
                    @else
                        {{-- fallback icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-slate-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                    @endif

                </div>

                {{-- NAME + ACRONYM --}}
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900 truncate">
                        {{ $org->name }}
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $org->acronym ?? '—' }}
                    </div>
                </div>

            </div>

            {{-- CLUSTER --}}
            <div class="mt-3">
                @if($org->cluster)
                    <span class="text-[10px] px-2 py-0.5 rounded-full border
                        {{ $clusterColors[$org->cluster_id] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                        {{ $org->cluster->name }}
                    </span>
                @endif
            </div>

            {{-- ACTIONS --}}
            <div class="mt-5 flex justify-between items-center">

                <div class="flex gap-3">

                    <button
                        @click='openEdit(
                            {{ $org->id }},
                            @json($org->name),
                            @json($org->acronym),
                            {{ $org->cluster_id ?? 'null' }}
                        )'
                        class="text-xs text-slate-600 hover:text-slate-900">
                        Edit
                    </button>

                    <form method="POST" action="{{ route('admin.organizations.destroy', $org) }}"
                        onsubmit="return confirm('Archive this organization?');">
                        @csrf
                        @method('DELETE')

                        <button class="text-xs text-red-600 hover:text-red-800">
                            Archive
                        </button>
                    </form>

                </div>
                @php
                    $schoolYearsData = $org->schoolYears->map(function($osy) use ($activeSY) {
                        return [
                            'id' => $osy->id,
                            'school_year_id' => $osy->school_year_id,
                            'name' => $osy->schoolYear->name ?? '—',
                            'is_active' => $activeSY && $osy->school_year_id == $activeSY->id,
                        ];
                    });
                @endphp

                <button
                    @click='openOrg(
                        {{ $org->id }},
                        @json($org->name),
                        @json($schoolYearsData)
                    )'
                    class="text-xs px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800">
                    Open
                </button>

            </div>

        </div>

        @endforeach

    </div>


    {{-- ================= CREATE / EDIT MODAL ================= --}}
    <div
        x-show="show"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        style="display: none;"
    >
        <div @click.outside="close()" class="w-full max-w-md bg-white rounded-2xl shadow-xl">

            <div class="px-5 py-4 border-b">
                <h2 class="text-sm font-semibold text-slate-900"
                    x-text="isEdit ? 'Edit Organization' : 'Add Organization'">
                </h2>
            </div>

            <form :action="formAction" method="POST" class="p-5 space-y-4">
                @csrf

                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <input type="text" name="name" x-model="form.name"
                    placeholder="Organization Name"
                    class="w-full border rounded-lg px-3 py-2 text-sm">

                <input type="text" name="acronym" x-model="form.acronym"
                    placeholder="Acronym"
                    class="w-full border rounded-lg px-3 py-2 text-sm">

                <select name="cluster_id" x-model="form.cluster_id"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Cluster</option>
                    @foreach($clusters as $cluster)
                        <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="close()" class="text-xs px-3 py-1 border rounded">
                        Cancel
                    </button>

                    <button type="submit" class="text-xs px-3 py-1 bg-slate-900 text-white rounded">
                        <span x-text="isEdit ? 'Update' : 'Create'"></span>
                    </button>
                </div>

            </form>
        </div>
    </div>


    {{-- ================= OPEN MODAL ================= --}}
    <div
        x-show="showOpen"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        style="display: none;"
    >
        <div @click.outside="closeOpen()" class="w-full max-w-md bg-white rounded-2xl shadow-xl">

            <div class="px-5 py-4 border-b">
                <h2 class="text-sm font-semibold text-slate-900">
                    Select School Year
                </h2>
                <p class="text-xs text-slate-500" x-text="selectedOrgName"></p>
            </div>

            <div class="p-4 space-y-2 max-h-[300px] overflow-y-auto">

                <template x-for="sy in orgSchoolYears" :key="sy.id">
                    <button
                        @click="submitOpen(sy.school_year_id)"
                        class="w-full flex justify-between px-3 py-2 border rounded-lg hover:bg-slate-50"
                    >
                        <span x-text="sy.name"></span>

                        <template x-if="sy.is_active">
                            <span class="text-[10px] bg-blue-100 text-blue-700 px-2 rounded">
                                Active
                            </span>
                        </template>
                    </button>
                </template>

            </div>

            <div class="px-4 py-3 border-t text-right">
                <button @click="closeOpen()" class="text-xs px-3 py-1 border rounded">
                    Cancel
                </button>
            </div>

        </div>
    </div>

</div>


<script>
function organizationModal() {
    return {

        // SEARCH
        search: '',
        match(text) {
            return text.includes(this.search.toLowerCase());
        },

        // CREATE / EDIT
        show: false,
        isEdit: false,

        form: {
            id: null,
            name: '',
            acronym: '',
            cluster_id: ''
        },

        formAction: '',

        // OPEN MODAL
        showOpen: false,
        selectedOrgId: null,
        selectedOrgName: '',
        orgSchoolYears: [],

        openCreate() {
            this.isEdit = false;
            this.form = { name: '', acronym: '', cluster_id: '' };
            this.formAction = '/admin/organizations';
            this.show = true;
        },

        openEdit(id, name, acronym, cluster_id) {
            this.isEdit = true;
            this.form = {
                id,
                name,
                acronym: acronym || '',
                cluster_id: cluster_id || ''
            };
            this.formAction = `/admin/organizations/${id}`;
            this.show = true;
        },

        openOrg(id, name, schoolYears) {
            this.selectedOrgId = id;
            this.selectedOrgName = name;
            this.orgSchoolYears = schoolYears;
            this.showOpen = true;
        },

        submitOpen(sy) {
            window.location.href = `/admin/organizations/${this.selectedOrgId}/open?school_year_id=${sy}`;
        },

        close() {
            this.show = false;
        },

        closeOpen() {
            this.showOpen = false;
        }
    }
}
</script>

</x-app-layout>