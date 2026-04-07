<x-app-layout>

@include('admin.projects.documents.partials._hub-header')

<div class="bg-slate-50 py-6">

<div class="max-w-7xl mx-auto px-4 space-y-4">

    {{-- GRID --}}
    <div class="grid grid-cols-12 gap-4">

        {{-- LEFT SIDE --}}
        <div class="col-span-12 lg:col-span-8 space-y-4">

            {{-- SNAPSHOT --}}
            @include('admin.projects.documents.partials._snapshot-card')

            @include('admin.projects.documents.partials._progress-bar') 
            

            @include('admin.projects.documents.partials._documents-table-v2') 
            

            @include('admin.projects.documents.partials._notices-table')
           

        </div>


        {{-- RIGHT SIDE --}}
        <div class="col-span-12 lg:col-span-4 space-y-4 lg:sticky lg:top-4 h-fit">

            {{--  --}}
            @include('admin.projects.documents.partials._pre-implementation-card')

            {{-- CLEARANCE --}}
            @include('admin.projects.documents.partials._clearance-panel')

            {{--  @include('admin.projects.documents.partials._submission-packets-card') --}}
           

            {{-- EXTERNAL PACKETS --}}
            @include('admin.projects.documents.partials.external-packets-card')

        </div>

    </div>


    {{-- FLOATING ACTION BAR --}}
    @include('admin.projects.documents.partials._admin-actions')

</div>

</div>

</x-app-layout>