<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Organizations
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.organizations.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 !text-white rounded">
                    + Add Organization
                </a>

                <a href="{{ route('admin.organizations.assign-president') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 !text-white rounded">
                    Assign / Provision President
                </a>
            </div>

            <div class="bg-white shadow rounded p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Name</th>
                            <th class="py-2">Acronym</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($organizations as $org)
                        <tr class="border-b">
                            <td class="py-2">{{ $org->name }}</td>
                            <td class="py-2">{{ $org->acronym ?? '-' }}</td>
                            <td class="py-2">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.organizations.edit', $org) }}"
                                       class="px-3 py-1 rounded bg-yellow-500 !text-white font-semibold">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.organizations.destroy', $org) }}"
                                          onsubmit="return confirm('Delete this organization?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 rounded bg-red-600 !text-white font-semibold">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">
                                No organizations found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
