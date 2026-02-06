<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            SacDev Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow rounded p-6">
                    <div class="text-sm text-gray-500">Active School Year</div>
                    <div class="text-lg font-semibold">
                        {{ $activeSy?->name ?? 'None' }}
                    </div>
                </div>

                <div class="bg-white shadow rounded p-6">
                    <div class="text-sm text-gray-500">Organizations</div>
                    <div class="text-lg font-semibold">{{ $orgCount }}</div>
                </div>

                <div class="bg-white shadow rounded p-6">
                    <div class="text-sm text-gray-500">School Years</div>
                    <div class="text-lg font-semibold">{{ $syCount }}</div>
                </div>
                <a href="{{ route('admin.rereg.index') }}" class="block hover:bg-slate-50 rounded">
                    <div class="bg-white shadow rounded p-6">
                        <div class="text-sm text-gray-500">Re-Registration Pending</div>

                        <div class="flex items-center gap-2">
                            <div class="text-lg font-semibold text-red-600">
                                {{ $pendingCaseCount }}
                            </div>

                            @if($pendingCaseCount > 0)
                                <span class="text-xs rounded-full bg-red-100 text-red-700 px-2 py-0.5 font-semibold">
                                    Needs review
                                </span>

                            @endif
                        </div>
                    </div>

                </a>
            </div>

            <div class="mt-6 bg-white shadow rounded p-6">
                <h3 class="font-semibold text-lg mb-3">Quick Links</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.school-years.index') }}"
                       class="px-3 py-2 bg-gray-800 !text-white rounded text-sm">
                        Manage School Years
                    </a>

                    <a href="{{ route('admin.organizations.index') }}"
                       class="px-3 py-2 bg-gray-800 !text-white rounded text-sm">
                        Manage Organizations
                    </a>

                    <a href="{{ route('admin.review.index') }}"
                       class="px-3 py-2 bg-blue-600 !text-white rounded text-sm">
                        Review Org Submissions
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
