<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Audit Logs
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Event</label>
                        <select name="event" class="w-full border rounded p-2">
                            <option value="">All</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev }}" @selected(request('event')===$ev)>{{ $ev }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Organization</label>
                        <select name="organization_id" class="w-full border rounded p-2">
                            <option value="">All</option>
                            @foreach($organizations as $o)
                                <option value="{{ $o->id }}" @selected((string)request('organization_id')===(string)$o->id)>{{ $o->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">School Year</label>
                        <select name="school_year_id" class="w-full border rounded p-2">
                            <option value="">All</option>
                            @foreach($schoolYears as $sy)
                                <option value="{{ $sy->id }}" @selected((string)request('school_year_id')===(string)$sy->id)>{{ $sy->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button class="px-4 py-2 bg-blue-600 !text-white rounded">Filter</button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 bg-gray-200 rounded">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow rounded p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">Date</th>
                            <th class="py-2">Event</th>
                            <th class="py-2">Actor</th>
                            <th class="py-2">Org</th>
                            <th class="py-2">SY</th>
                            <th class="py-2">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b align-top">
                            <td class="py-2 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-2 font-semibold">{{ $log->event }}</td>
                            <td class="py-2">
                                {{ $log->actor?->name ?? '-' }}<br>
                                <span class="text-xs text-gray-500">{{ $log->actor?->email ?? '' }}</span>
                            </td>
                            <td class="py-2">{{ $log->organization?->name ?? '-' }}</td>
                            <td class="py-2">{{ $log->schoolYear?->name ?? '-' }}</td>
                            <td class="py-2">
                                {{ $log->message ?? '-' }}
                                @if($log->meta)
                                    <pre class="mt-2 text-xs bg-gray-50 border rounded p-2 overflow-x-auto">{{ json_encode($log->meta, JSON_PRETTY_PRINT) }}</pre>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-4 text-center text-gray-500">No logs yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
