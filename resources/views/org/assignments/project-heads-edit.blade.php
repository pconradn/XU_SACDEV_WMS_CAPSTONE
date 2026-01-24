<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign Head: {{ $project->title }} (Encode SY ID: {{ $syId }})
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <form method="POST" action="{{ route('org.assign-project-heads.update', $project) }}">
                    @csrf

                    <label class="block text-sm font-medium mb-1">Choose Officer (must be in officer list)</label>
                    <select name="officer_id" class="w-full border rounded p-2" required>
                        <option value="">-- Select officer --</option>
                        @foreach($officers as $o)
                            <option value="{{ $o->id }}">
                                {{ $o->full_name }} ({{ $o->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('officer_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                    <div class="mt-4 flex gap-2">
                        <button class="px-4 py-2 bg-blue-600 !text-white rounded">Save</button>
                        <a href="{{ route('org.assign-project-heads.index') }}" class="px-4 py-2 bg-gray-200 rounded">Back</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
