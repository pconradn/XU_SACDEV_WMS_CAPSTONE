<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $org->name }} — {{ $sy->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <a href="{{ route('admin.review.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded">
                    Back to Filter
                </a>
            </div>

            <div class="bg-white shadow rounded p-6 mb-6">
                <h3 class="font-semibold text-lg mb-3">Assignments Summary</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Role</th>
                                <th class="py-2">Name</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Activated</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($roleRows as $r)
                            <tr class="border-b">
                                <td class="py-2">{{ $r['role'] }}</td>
                                <td class="py-2">{{ $r['name'] }}</td>
                                <td class="py-2">{{ $r['email'] }}</td>
                                <td class="py-2">
                                    @if($r['activated'])
                                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Yes</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold">No</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-gray-500">No org roles assigned.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold mb-2">Project Heads</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2">Project</th>
                                    <th class="py-2">Name</th>
                                    <th class="py-2">Email</th>
                                    <th class="py-2">Activated</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($headRows as $h)
                                <tr class="border-b">
                                    <td class="py-2">{{ $h['project'] }}</td>
                                    <td class="py-2">{{ $h['name'] }}</td>
                                    <td class="py-2">{{ $h['email'] }}</td>
                                    <td class="py-2">
                                        @if($h['activated'])
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Yes</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-gray-500">No project heads assigned.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    
            <div class="bg-white shadow rounded p-6 mb-6">
                <h3 class="font-semibold text-lg mb-3">Officers Submitted</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Name</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Position</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($officers as $o)
                            <tr class="border-b">
                                <td class="py-2">{{ $o->full_name }}</td>
                                <td class="py-2">{{ $o->email }}</td>
                                <td class="py-2">{{ $o->position ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">No officers submitted.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

    
            <div class="bg-white shadow rounded p-6">
                <h3 class="font-semibold text-lg mb-3">Projects Submitted</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Title</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($projects as $p)
                            <tr class="border-b">
                                <td class="py-2">{{ $p->title }}</td>
                            </tr>
                        @empty
                            <tr><td class="py-4 text-center text-gray-500">No projects submitted.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
