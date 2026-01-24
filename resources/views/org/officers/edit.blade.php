<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Edit Officer</h2></x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('org.officers.update', $officer) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Full Name</label>
                        <input name="full_name" value="{{ old('full_name', $officer->full_name) }}" class="mt-1 w-full border rounded p-2" required>
                        @error('full_name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Email</label>
                        <input name="email" type="email" value="{{ old('email', $officer->email) }}" class="mt-1 w-full border rounded p-2" required>
                        @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Position (optional)</label>
                        <input name="position" value="{{ old('position', $officer->position) }}" class="mt-1 w-full border rounded p-2">
                        @error('position') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-gray-800 !text-white rounded">Update</button>
                        <a href="{{ route('org.officers.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
