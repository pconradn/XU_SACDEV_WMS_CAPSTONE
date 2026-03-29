<x-app-layout>

<div class="py-8">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    @if (session('status'))
        <div class="p-3 rounded bg-green-100 text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @include('admin.dashboard._kpis')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            @include('admin.dashboard._pending-cases')
            @include('admin.dashboard._project-approvals')
        </div>

        <div class="space-y-6">
            @include('admin.dashboard._activation')
            @include('admin.dashboard._quick-links')
        </div>

    </div>

    @include('admin.dashboard._calendar')

</div>
</div>

</x-app-layout>