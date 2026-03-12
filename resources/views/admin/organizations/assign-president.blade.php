<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign Next School Year President
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded p-6">

                <form method="POST" action="{{ route('admin.organizations.assign-president.store') }}">
                    @csrf

                    {{-- Organization --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">
                            Organization
                        </label>

                        <select name="organization_id"
                                class="mt-1 w-full border rounded p-2 focus:ring focus:ring-blue-200"
                                required>

                            <option value="">-- Select Organization --</option>

                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}"
                                    @selected(old('organization_id') == $org->id)>
                                    {{ $org->name }}
                                    {{ $org->acronym ? "({$org->acronym})" : '' }}
                                </option>
                            @endforeach

                        </select>

                        @error('organization_id')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- School Year --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">
                            Target School Year
                        </label>

                        <select name="school_year_id"
                                class="mt-1 w-full border rounded p-2 focus:ring focus:ring-blue-200"
                                required>

                            <option value="">-- Select School Year --</option>

                            @foreach ($schoolYears as $sy)
                                <option value="{{ $sy->id }}"
                                    @selected(old('school_year_id') == $sy->id)>
                                    {{ $sy->name }}
                                    {{ $sy->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach

                        </select>

                        @error('school_year_id')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Full Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">
                            President Full Name
                        </label>

                        <input type="text"
                               name="president_name"
                               value="{{ old('president_name') }}"
                               placeholder="Juan Dela Cruz"
                               class="mt-1 w-full border rounded p-2 focus:ring focus:ring-blue-200"
                               required>

                        @error('president_name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Student ID --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">
                            Student ID Number
                        </label>

                        <input type="text"
                               name="student_id_number"
                               id="studentIdInput"
                               value="{{ old('student_id_number') }}"
                               placeholder="2018xxxxxxx"
                               class="mt-1 w-full border rounded p-2 focus:ring focus:ring-blue-200"
                               required>

                        @error('student_id_number')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror

                        <div class="mt-2 text-sm text-slate-500">
                            Email will be automatically generated:
                            <span id="emailPreview"
                                  class="font-semibold text-blue-600">
                                studentID@my.xu.edu.ph
                            </span>
                        </div>
                    </div>


                    {{-- Buttons --}}
                    <div class="flex gap-2 mt-6">

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">

                            Assign President

                        </button>

                        <a href="{{ route('admin.organizations.index') }}"
                           class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">

                            Back

                        </a>

                    </div>


                    <div class="mt-4 text-sm text-slate-500">
                        System will create OfficerEntry, User account, and Membership automatically.
                    </div>

                </form>

            </div>
        </div>
    </div>


    {{-- Email preview script --}}
    <script>
        const studentIdInput = document.getElementById('studentIdInput');
        const emailPreview = document.getElementById('emailPreview');

        function updateEmailPreview()
        {
            const id = studentIdInput.value.trim();

            emailPreview.textContent =
                id ? `${id}@my.xu.edu.ph`
                   : 'studentID@my.xu.edu.ph';
        }

        studentIdInput.addEventListener('input', updateEmailPreview);

        updateEmailPreview();
    </script>

</x-app-layout>