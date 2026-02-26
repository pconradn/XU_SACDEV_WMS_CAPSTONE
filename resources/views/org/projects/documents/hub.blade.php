<x-app-layout>

<div class="mx-auto max-w-6xl px-4 py-6">

    {{-- Header --}}
    @include('org.projects.documents.partials.header', [
        'project' => $project
    ])


    {{-- Status messages --}}
    @if(session('error'))
        <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('status'))
        <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            {{ session('status') }}
        </div>
    @endif


    {{-- Pre Implementation --}}
    @include('org.projects.documents.partials.section', [
        'title' => 'Pre-Implementation Documents',
        'forms' => $preForms,
        'phase' => 'pre'
    ])


    {{-- Post Implementation --}}
    @include('org.projects.documents.partials.section', [
        'title' => 'Post-Implementation Documents',
        'forms' => $postForms,
        'phase' => 'post'
    ])


</div>

</x-app-layout>