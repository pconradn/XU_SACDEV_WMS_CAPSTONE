<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
            Select School Year
        </h2>

        <p class="mb-4 text-sm text-gray-600">
            You may select any school year where you have an organization role.
        </p>

        @if (session('status'))
            <div class="mb-4 rounded bg-yellow-100 p-3 text-yellow-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-6">
            @if($allowedSchoolYears->count() === 0)
                <div class="text-gray-600">
                    You currently have no organization memberships in any school year.
                </div>
            @else
                <form method="POST" action="{{ route('org.encode-sy.update') }}">
                    @csrf

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        School Year
                    </label>

                    <select name="encode_sy_id"
                            class="w-full rounded border border-gray-300 p-2 mb-4"
                            required>
                        @foreach($allowedSchoolYears as $sy)
                            <option value="{{ $sy->id }}"
                                @selected((int)$sy->id === (int)$selectedEncodeSyId)>
                                {{ $sy->name ?? 'SY ' . $sy->id }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        class="rounded bg-gray-900 px-4 py-2 text-white text-sm hover:bg-gray-800">
                        Use Selected School Year
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
