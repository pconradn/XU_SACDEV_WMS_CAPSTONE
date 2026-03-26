<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-slate-800">
        Off-Campus Activity Guidelines
    </h2>
</x-slot>

<div class="py-8">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded">

            <div class="px-6 py-4 border-b border-slate-200">

                <div class="text-[12px] text-slate-700">
                    Before filling out the <span class="font-medium">Off-Campus Activity Form</span>,
                    please read the official SACDEV guidelines below.
                </div>

            </div>


            {{-- PDF Viewer --}}
            <div class="px-6 pt-4">

                <div class="border border-slate-300 overflow-hidden">

                    <iframe
                        src="{{ asset('guidelines/off-campus-activities.pdf') }}"
                        class="w-full"
                        style="height:600px">
                    </iframe>

                </div>

            </div>


            {{-- Acknowledgement --}}
            <div class="px-6 pt-4 pb-6">

                <form method="POST"
                      action="{{ route('org.projects.documents.off-campus.acknowledge', $project) }}">

                    @csrf

                    <div class="max-w-sm">

                        <label class="block text-[10px] font-medium text-blue-900 italic">
                            Enter your Student ID to confirm that you have read the guidelines
                        </label>

                        <input type="text"
                               name="student_id"
                               value="{{ old('student_id') }}"
                               class="mt-1 w-full border border-slate-300 bg-white px-3 py-1 text-[10px]"
                               placeholder="Student ID"
                               required>

                        @error('student_id')
                            <div class="text-red-600 text-[10px] mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="flex gap-2 mt-4">

                        <button
                            class="px-4 py-1 bg-gray-800 !text-white text-[11px] rounded">
                            I Have Read the Guidelines
                        </button>

                        <a href="{{ route('org.projects.documents.hub', $project) }}"
                           class="px-4 py-1 bg-gray-200 text-[11px] rounded">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</x-app-layout>