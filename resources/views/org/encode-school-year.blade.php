<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Select School Year to Encode
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">
                <div class="mb-4 text-sm text-gray-600">
                    Active SY is <span class="font-semibold">{{ $activeSy->name }}</span>.
                    As President, you may encode for the <span class="font-semibold">Active</span> SY or the <span class="font-semibold">Next</span> SY (if available).
                </div>

                <form method="POST" action="{{ route('org.encode-sy.update') }}">
                    @csrf

                    <label class="block text-sm font-medium mb-1">Encoding School Year</label>
                    <select name="encode_sy_id" class="w-full border rounded p-2">
                        @foreach($allowedSchoolYears as $sy)
                            <option value="{{ $sy->id }}" @selected($selectedEncodeSyId == $sy->id)>
                                {{ $sy->name }} {{ $sy->id === $activeSy->id ? '(Active)' : '(Next)' }}
                            </option>
                        @endforeach
                    </select>

                    @error('encode_sy_id')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <div class="mt-4 flex gap-2">
                        <button class="px-4 py-2 bg-blue-600 !text-white rounded">
                            Save
                        </button>
                        <a href="{{ route('org.home') }}" class="px-4 py-2 bg-gray-200 rounded">
                            Back
                        </a>
                    </div>
                </form>

                @if(!$nextSy)
                    <div class="mt-4 text-sm text-gray-500">
                        No “Next” school year found yet. Ask SacDev admin to add the next school year.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
