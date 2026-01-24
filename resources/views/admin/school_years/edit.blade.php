<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit School Year
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('admin.school-years.update', $schoolYear) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Name</label>
                        <input name="name" value="{{ old('name', $schoolYear->name) }}" class="mt-1 w-full border rounded p-2" required>
                        @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Start Date</label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', optional($schoolYear->start_date)->format('Y-m-d')) }}"
                               class="mt-1 w-full border rounded p-2">
                        @error('start_date') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">End Date</label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', optional($schoolYear->end_date)->format('Y-m-d')) }}"
                               class="mt-1 w-full border rounded p-2">
                        @error('end_date') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-gray-800 text-white rounded">Update</button>
                        <a href="{{ route('admin.school-years.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
