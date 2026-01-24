<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Officers (Encoding SY ID: {{ $syId }})
        </h2>
    </x-slot>


    @if (session('warning') && session('resend_invite_officer_id'))
        <div class="mb-4 p-4 rounded bg-yellow-100 text-yellow-900 border border-yellow-200">
            <div class="font-semibold">Action needed</div>
            <div class="text-sm mt-1">{{ session('warning') }}</div>

            <form method="POST" action="{{ route('org.officers.resend-invite', session('resend_invite_officer_id')) }}">
                @csrf
                <input type="hidden" name="old_user_id" value="{{ session('resend_invite_old_user_id') }}">

                <button type="submit" class="px-4 py-2 bg-yellow-600 !text-white rounded text-sm font-semibold">
                    Resend Invite to Correct Email
                </button>
            </form>

        </div>
    @endif

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="mb-4">
                <a href="{{ route('org.officers.create') }}"
                   class="px-4 py-2 bg-gray-800 !text-white rounded">
                    + Add Officer
                </a>
            </div>

            <div class="bg-white shadow rounded p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Name</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Position</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($officers as $o)
                        <tr class="border-b">
                            <td class="py-2">{{ $o->full_name }}</td>
                            <td class="py-2">{{ $o->email }}</td>
                            <td class="py-2">{{ $o->position ?? '-' }}</td>
                            <td class="py-2">
                                <div class="flex gap-2 flex-wrap">
                                    <a href="{{ route('org.officers.edit', $o) }}"
                                       class="px-3 py-1 rounded bg-yellow-500 !text-white font-semibold">Edit</a>

                                    <form method="POST" action="{{ route('org.officers.destroy', $o) }}"
                                          onsubmit="return confirm('Delete this officer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 rounded bg-red-600 !text-white font-semibold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-500">No officers yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
