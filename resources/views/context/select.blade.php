<x-plain-layout>

<div class="grid grid-cols-2 bg-white shadow-xl rounded-xl overflow-hidden max-w-4xl mx-auto">

    <!-- LEFT PANEL -->
    <div class="bg-blue-900 text-white p-10 flex flex-col justify-between">

        <!-- Header -->
        <div class="text-center">

            <div class="flex justify-center mb-5">
                <div class="w-24 h-24 bg-white rounded-full shadow-md ring-4 ring-blue-200 overflow-hidden">
                    <img src="/images/sacdev-logo.jpg"
                         alt="SACDEV Logo"
                         class="w-full h-full object-cover">
                </div>
            </div>

            <h1 class="text-2xl font-semibold tracking-tight">
                SACDEV
            </h1>

            <h2 class="text-base font-medium mt-1 text-blue-100">
                Project Documentation and Approval System
            </h2>

            <p class="mt-2 text-sm text-blue-200">
                Xavier University – Ateneo de Cagayan
            </p>

        </div>


        <!-- Context Explanation -->
        <div class="mt-10 text-sm text-blue-200 leading-relaxed text-center max-w-xs mx-auto">

            Before continuing, choose the organization and
            school year you want to work on.

            <br><br>

            The selected context determines which project
            documents, proposals, and submissions you will
            be able to view and manage.

        </div>

    </div>



    <!-- RIGHT PANEL -->
    <div class="p-10 flex flex-col justify-center">

        <h1 class="text-xl font-semibold text-slate-900 mb-1">
            Select Organization & School Year
        </h1>

        <p class="text-sm text-slate-600 mb-6">
            Choose the organization and school year you want to work on.
        </p>


        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                <div class="mb-2 text-sm font-semibold">Please fix the following:</div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('context.update') }}" class="space-y-5">
            @csrf


            <!-- Organization -->
            <div>
                <label for="active_org_id" class="block text-sm font-medium text-slate-700">
                    Organization
                </label>

                <select
                    id="active_org_id"
                    name="active_org_id"
                    required
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm
                           shadow-sm transition
                           focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10"
                >
                    <option value="">— Select organization —</option>

                    @foreach ($orgs as $org)
                        <option
                            value="{{ $org->id }}"
                            {{ (int) session('active_org_id') === (int) $org->id ? 'selected' : '' }}
                        >
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- School Year -->
            <div>
                <label for="encode_sy_id" class="block text-sm font-medium text-slate-700">
                    School Year
                </label>

                <select
                    id="encode_sy_id"
                    name="encode_sy_id"
                    required
                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm
                           shadow-sm transition
                           focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10"
                >
                    <option value="">— Select school year —</option>

                    @foreach ($schoolYears as $sy)
                        <option
                            value="{{ $sy->id }}"
                            {{ (int) session('encode_sy_id') === (int) $sy->id ? 'selected' : '' }}
                        >
                            {{ $sy->label ?? ('SY ' . $sy->name) }}
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- Actions -->
            <div class="pt-4 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-blue-900 px-4 py-2.5
                           text-sm font-semibold text-white shadow-sm hover:bg-blue-800
                           focus:outline-none focus:ring-4 focus:ring-blue-900/20 sm:w-auto"
                >
                    Continue
                </button>

                <button
                    type="button"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-sm font-medium text-slate-500 hover:text-slate-700"
                >
                    Logout
                </button>

            </div>

        </form>


        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

    </div>

</div>

</x-plain-layout>