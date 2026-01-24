<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign Treasurer & Moderator (Encode SY ID: {{ $syId }})
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <form method="POST" action="{{ route('org.assign-roles.update') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Treasurer (exactly 1)</label>
                        <select name="treasurer_officer_id" class="w-full border rounded p-2" required>
                            <option value="">-- Select officer --</option>
                            @foreach($officers as $o)
                                <option value="{{ $o->id }}" @selected(old('treasurer_officer_id') == $o->id)>
                                    {{ $o->full_name }} ({{ $o->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('treasurer_officer_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Moderator (exactly 1)</label>
                        <select name="moderator_officer_id" class="w-full border rounded p-2" required>
                            <option value="">-- Select officer --</option>
                            @foreach($officers as $o)
                                <option value="{{ $o->id }}" @selected(old('moderator_officer_id') == $o->id)>
                                    {{ $o->full_name }} ({{ $o->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('moderator_officer_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <button class="px-4 py-2 bg-blue-600 !text-white rounded">
                        Save Assignments
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
