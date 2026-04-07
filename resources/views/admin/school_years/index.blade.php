<x-app-layout>

<div x-data="schoolYearModal()">

<div class="space-y-6">


    
    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                    School Year Management
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Manage predefined school years and control which one is currently active.
                </p>
            </div>

        </div>
    </div>



    {{-- SUCCESS MESSAGE --}}
    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif


    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse ($schoolYears as $sy)

            <div class="relative rounded-2xl border 
                {{ $sy->is_active 
                    ? 'border-blue-500/50 shadow-lg shadow-blue-500/10' 
                    : 'border-slate-200' }} 
                bg-white p-5 transition hover:shadow-md">

                
                {{-- ACTIVE BADGE --}}
                @if($sy->is_active)
                    <span class="absolute top-4 right-4 text-[10px] font-semibold px-2 py-0.5 rounded-full 
                                 bg-blue-100 text-blue-700 border border-blue-200">
                        ACTIVE
                    </span>
                @endif


                {{-- TITLE --}}
                <div class="text-base font-semibold text-slate-900"
                    contenteditable="false">
                    {{ $sy->name }}
                </div>

                {{-- DATES --}}
                <div class="mt-3 text-xs text-slate-500 space-y-1">

                    <div>
                        <span class="text-slate-400">Start:</span>
                        {{ $sy->start_date?->format('M d, Y') ?? '—' }}
                    </div>

                    <div>
                        <span class="text-slate-400">End:</span>
                        {{ $sy->end_date?->format('M d, Y') ?? '—' }}
                    </div>

                </div>


                {{-- STATUS LINE --}}
                <div class="mt-4 text-[11px] text-slate-400">
                    {{ $sy->is_active ? 'Currently active school year' : 'Inactive' }}
                </div>


                {{-- ACTIONS --}}
                <div class="mt-5 flex items-center justify-between">

                    {{-- EDIT --}}
                    <button
                        @click="openEdit({{ $sy->id }}, '{{ $sy->name }}', '{{ optional($sy->start_date)->format('Y-m-d') }}', '{{ optional($sy->end_date)->format('Y-m-d') }}')"
                        class="text-xs font-medium text-slate-600 hover:text-slate-900 transition">
                        Edit
                    </button>


                    {{-- ACTIVATE --}}
                    @if (!$sy->is_active)
                        <form method="POST" action="{{ route('admin.school-years.activate', $sy) }}">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg 
                                       bg-slate-900 text-white hover:bg-slate-800 transition">
                                Set Active
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-slate-400 font-medium">
                            Active
                        </span>
                    @endif

                </div>

            </div>

        @empty

            <div class="col-span-full text-center text-sm text-slate-500 py-10">
                No school years found.
            </div>

        @endforelse

    </div>

</div>




{{-- EDIT MODAL --}}
<div
    x-show="show"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    style="display: none;"
>

    <div @click.outside="close()" class="w-full max-w-md rounded-2xl bg-white shadow-xl">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b">
            <h2 class="text-sm font-semibold text-slate-900">
                Edit School Year
            </h2>
        </div>

        {{-- BODY --}}
        <form :action="formAction" method="POST" class="p-5 space-y-4">
            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                <label class="block text-xs font-medium text-slate-600">
                    Name
                </label>
                <input
                    type="text"
                    name="name"
                    x-model="form.name"
                    readonly
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    required
                >
            </div>

            {{-- START DATE --}}
            <div>
                <label class="block text-xs font-medium text-slate-600">
                    Start Date
                </label>
                <input
                    type="date"
                    name="start_date"
                    x-model="form.start_date"
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            </div>

            {{-- END DATE --}}
            <div>
                <label class="block text-xs font-medium text-slate-600">
                    End Date
                </label>
                <input
                    type="date"
                    name="end_date"
                    x-model="form.end_date"
                    class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 pt-2">

                <button type="button"
                    @click="close()"
                    class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-3 py-1.5 text-xs rounded-lg bg-slate-900 text-white hover:bg-slate-800">
                    Update
                </button>

            </div>

        </form>

    </div>

</div>

<script>
function schoolYearModal() {
    return {
        show: false,
        form: {
            id: null,
            name: '',
            start_date: '',
            end_date: ''
        },
        formAction: '',

        openEdit(id, name, start, end) {
            this.form.id = id;
            this.form.name = name;
            this.form.start_date = start;
            this.form.end_date = end;

            this.formAction = `/admin/school-years/${id}`;
            this.show = true;
        },

        close() {
            this.show = false;
        }
    }
}
</script>










</div>
</x-app-layout>