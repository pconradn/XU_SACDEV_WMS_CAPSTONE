<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign Project Heads (Encode SY ID: {{ $syId }})
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow rounded p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Project</th>
                            <th class="py-2">Head Assigned?</th>
                            <th class="py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($projects as $p)
                        <tr class="border-b">
                            <td class="py-2">{{ $p->title }}</td>
                            <td class="py-2">
                                @if(isset($heads[$p->id]))
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-800">Yes</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-gray-100 text-gray-700">No</span>
                                @endif
                            </td>
                            <td class="py-2">
                                <a href="{{ route('org.assign-project-heads.edit', $p) }}"
                                   class="px-3 py-1 rounded bg-blue-600 !text-white font-semibold">
                                    Assign / Change
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-500">No projects found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
