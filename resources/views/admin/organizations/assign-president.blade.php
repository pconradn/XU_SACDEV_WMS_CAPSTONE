<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign / Provision President
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <form method="POST" action="{{ route('admin.organizations.assign-president.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Organization</label>
                        <select name="organization_id" class="mt-1 w-full border rounded p-2" required>
                            <option value="">-- Select Organization --</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}" @selected(old('organization_id') == $org->id)>
                                    {{ $org->name }} {{ $org->acronym ? "({$org->acronym})" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('organization_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">School Year</label>
                        <select name="school_year_id" class="mt-1 w-full border rounded p-2" required>
                            <option value="">-- Select School Year --</option>
                            @foreach ($schoolYears as $sy)
                                <option value="{{ $sy->id }}" @selected(old('school_year_id') == $sy->id)>
                                    {{ $sy->name }} {{ $sy->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_year_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">President Name</label>
                        <input name="president_name" value="{{ old('president_name') }}" class="mt-1 w-full border rounded p-2" required>
                        @error('president_name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">President Email</label>
                        <input type="email" name="president_email" value="{{ old('president_email') }}" class="mt-1 w-full border rounded p-2" required>
                        @error('president_email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-blue-600 !text-white rounded">Provision & Assign</button>
                        <a href="{{ route('admin.organizations.index') }}" class="px-4 py-2 bg-gray-200 rounded">Back</a>
                    </div>

                    <p class="mt-4 text-sm text-gray-600">
                        Note: This will generate a temporary password and require the president to change it upon first login.
                        Email sending will work via your mail configuration (use <code>MAIL_MAILER=log</code> for testing).
                    </p>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
