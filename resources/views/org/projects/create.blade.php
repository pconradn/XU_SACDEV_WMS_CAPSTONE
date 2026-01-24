<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Add Project</h2></x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('org.projects.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Title</label>
                        <input name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded p-2" required>
                        @error('title') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-gray-800 !text-white rounded">Save</button>
                        <a href="{{ route('org.projects.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
