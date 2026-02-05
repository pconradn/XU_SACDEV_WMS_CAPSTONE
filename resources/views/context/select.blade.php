<x-plain-layout>
    <div class="w-full max-w-lg px-4">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h1 class="text-xl font-semibold text-slate-900">
                    Select Organization & School Year
                </h1>
                <p class="mt-1 text-sm text-slate-600">
                    Choose the organization and school year you want to work on before continuing.
                </p>
            </div>

            <div class="px-6 py-6">
                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
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

                    {{-- Organization --}}
                    <div>
                        <label for="active_org_id" class="block text-sm font-medium text-slate-700">
                            Organization
                        </label>
                        <p class="mt-1 text-xs text-slate-500">
                           
                        </p>

                        <div class="mt-2">
                            <select
                                id="active_org_id"
                                name="active_org_id"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm
                                       shadow-sm outline-none transition
                                       focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10"
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
                    </div>

                    {{-- School Year --}}
                    <div>
                        <label for="encode_sy_id" class="block text-sm font-medium text-slate-700">
                            School Year
                        </label>
                        <p class="mt-1 text-xs text-slate-500">
                           
                        </p>

                        <div class="mt-2">
                            <select
                                id="encode_sy_id"
                                name="encode_sy_id"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm
                                       shadow-sm outline-none transition
                                       focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10"
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
                    </div>

                    <div class="pt-2 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5
                                   text-sm font-semibold text-white shadow-sm hover:bg-slate-800
                                   focus:outline-none focus:ring-4 focus:ring-slate-900/20 sm:w-auto"
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

        <div class="mt-4 text-center text-xs text-slate-400">
            Tip: If you don’t see the expected organization or school year, you may not be assigned to it yet.
        </div>
    </div>
</x-plain-layout>
