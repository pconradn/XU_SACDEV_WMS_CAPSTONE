<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Org Submissions
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <div class="text-sm text-gray-600 mb-4">
                    Select an organization and a school year to view submitted officers, projects, and assignments.
                </div>

                <form method="GET" action="{{ route('admin.review.show') }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Organization</label>
                        <select name="organization_id" class="w-full border rounded p-2" required>
                            <option value="">-- Select organization --</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                        @error('organization_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">School Year</label>
                        <select name="school_year_id" class="w-full border rounded p-2" required>
                            <option value="">-- Select school year --</option>
                            @foreach($schoolYears as $sy)
                                <option value="{{ $sy->id }}">
                                    {{ $sy->name }} {{ $activeSy && $activeSy->id === $sy->id ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_year_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <button class="px-4 py-2 bg-blue-600 !text-white rounded">
                        View Submissions
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
