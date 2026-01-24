<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            School Years
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('admin.school-years.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded">
                    + Add School Year
                </a>
            </div>

            <div class="bg-white shadow rounded p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Name</th>
                            <th class="py-2">Start</th>
                            <th class="py-2">End</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($schoolYears as $sy)
                        <tr class="border-b">
                            <td class="py-2">{{ $sy->name }}</td>
                            <td class="py-2">{{ $sy->start_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="py-2">{{ $sy->end_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="py-2">
                                @if ($sy->is_active)
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">Inactive</span>
                                @endif
                            </td>
                            <td class="py-2">
                            <div class="flex flex-wrap gap-2 items-center">

                                @if (!$sy->is_active)
                                    <form method="POST" action="{{ route('admin.school-years.activate', $sy) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1 rounded bg-blue-600 !text-white font-semibold hover:bg-blue-700">
                                            Activate
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.school-years.edit', $sy) }}"
                                class="px-3 py-1 rounded bg-yellow-500 !text-white font-semibold hover:bg-yellow-600">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.school-years.destroy', $sy) }}"
                                    onsubmit="return confirm('Delete this school year?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 rounded bg-red-600 !text-white font-semibold hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">
                                No school years found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
