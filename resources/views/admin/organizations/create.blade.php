<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Organization
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('admin.organizations.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Name</label>
                        <input name="name" value="{{ old('name') }}" class="mt-1 w-full border rounded p-2" required>
                        @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Acronym (optional)</label>
                        <input name="acronym" value="{{ old('acronym') }}" class="mt-1 w-full border rounded p-2">
                        @error('acronym') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-gray-800 !text-white rounded">Save</button>
                        <a href="{{ route('admin.organizations.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
