<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Activation Status (Encode SY ID: {{ $syId }})
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6 mb-6">
                <div class="text-sm text-gray-600">
                    Activated = user has logged in and changed their temporary password.
                </div>
            </div>

            {{-- Org roles --}}
            <div class="bg-white shadow rounded p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Treasurer & Moderator</h3>
                </div>

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
                                <td class="py-2">{{ $r['label'] }}</td>
                                <td class="py-2">{{ $r['name'] }}</td>
                                <td class="py-2">{{ $r['email'] }}</td>
                                <td class="py-2">
                                    @if($r['activated'])
                                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">
                                            Yes
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold">
                                            No
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">
                                    No treasurer/moderator assigned yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Project heads --}}
            <div class="bg-white shadow rounded p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Project Heads</h3>
                </div>

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
                        @forelse($projectRows as $p)
                            <tr class="border-b">
                                <td class="py-2">{{ $p['label'] }}</td>
                                <td class="py-2">{{ $p['name'] }}</td>
                                <td class="py-2">{{ $p['email'] }}</td>
                                <td class="py-2">
                                    @if($p['activated'])
                                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">
                                            Yes
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold">
                                            No
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">
                                    No project heads assigned yet.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
