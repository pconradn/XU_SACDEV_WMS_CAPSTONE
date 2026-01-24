<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projects (Encoding SY ID: {{ $syId }})
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="mb-4">
                <a href="{{ route('org.projects.create') }}"
                   class="px-4 py-2 bg-gray-800 !text-white rounded">
                    + Add Project
                </a>
            </div>

            <div class="bg-white shadow rounded p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Title</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($projects as $p)
                        <tr class="border-b">
                            <td class="py-2">{{ $p->title }}</td>
                            <td class="py-2">
                                <div class="flex gap-2 flex-wrap">
                                    <a href="{{ route('org.projects.edit', $p) }}"
                                       class="px-3 py-1 rounded bg-yellow-500 !text-white font-semibold">Edit</a>

                                    <form method="POST" action="{{ route('org.projects.destroy', $p) }}"
                                          onsubmit="return confirm('Delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 rounded bg-red-600 !text-white font-semibold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="py-4 text-center text-gray-500">No projects yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
