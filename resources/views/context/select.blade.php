<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-8">

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-900">
                Select Organization & School Year
            </h1>
            <p class="mt-1 text-sm text-slate-600">
                Please choose the organization and school year you want to work on.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('context.update') }}">
            @csrf

            {{-- Organization --}}
            <div class="mb-5">
                <label for="active_org_id" class="block text-sm font-medium text-slate-700 mb-1">
                    Organization
                </label>
                <select
                    id="active_org_id"
                    name="active_org_id"
                    required
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-slate-900"
                >
                    <option value="">— Select organization —</option>
                    @foreach ($orgs as $org)
                        <option
                            value="{{ $org->id }}"
                            {{ session('active_org_id') == $org->id ? 'selected' : '' }}
                        >
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- School Year --}}
            <div class="mb-6">
                <label for="encode_sy_id" class="block text-sm font-medium text-slate-700 mb-1">
                    School Year
                </label>
                <select
                    id="encode_sy_id"
                    name="encode_sy_id"
                    required
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-slate-900"
                >
                    <option value="">— Select school year —</option>
                    @foreach ($schoolYears as $sy)
                        <option
                            value="{{ $sy->id }}"
                            {{ session('encode_sy_id') == $sy->name ? 'selected' : '' }}
                        >
                            {{ $sy->label ?? ('SY ' . $sy->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Continue
                </button>

                <a
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-sm text-slate-500 hover:text-slate-700"
                >
                    Logout
                </a>
            </div>
        </form>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</x-app-layout>
