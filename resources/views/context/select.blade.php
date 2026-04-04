<x-plain-layout>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-slate-100 to-white px-4 py-6">

    <div class="w-full max-w-3xl space-y-5">

        {{-- ================= TOP INFO CARD ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-blue-50 to-white shadow-sm px-5 py-4">

            <div class="flex items-center gap-3">

                {{-- LOGO --}}
                <div class="w-10 h-10 rounded-lg overflow-hidden bg-white border border-slate-200 flex items-center justify-center">
                    <img src="/images/sacdev-logo.jpg"
                         class="w-full h-full object-cover">
                </div>

                <div>
                    <div class="text-sm font-semibold text-slate-900">
                        SACDEV System
                    </div>

                    <div class="text-xs text-slate-500">
                        Select your working context to continue
                    </div>
                </div>

            </div>

        </div>


        {{-- ================= MAIN CARD ================= --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">

            {{-- HEADER --}}
            <div class="mb-4">

                <div class="flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4 text-slate-400"></i>

                    <h2 class="text-sm font-semibold text-slate-900">
                        Select Organization & School Year
                    </h2>
                </div>

                <p class="text-xs text-slate-500 mt-1">
                    Choose where you want to work. This affects visible projects and documents.
                </p>

            </div>


            {{-- ERROR --}}
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                    <div class="font-semibold mb-1">Please fix the following:</div>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('context.update') }}">
                @csrf

                {{-- HIDDEN INPUTS --}}
                <input type="hidden" name="active_org_id">
                <input type="hidden" name="encode_sy_id">


                {{-- CONTEXT LIST --}}
                <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1">

                    @foreach($contexts as $context)

                        <div class="rounded-xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-4">

                            {{-- ORG HEADER --}}
                            <div class="flex items-center gap-2 mb-3">
                                <i data-lucide="building" class="w-4 h-4 text-slate-400"></i>

                                <div class="text-sm font-semibold text-slate-900">
                                    {{ $context['organization']->name }}
                                </div>
                            </div>


                            {{-- SCHOOL YEARS --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">

                                @foreach($context['school_years'] as $sy)

                                    <button type="button"
                                        onclick="
                                            const form = this.closest('form');
                                            form.active_org_id.value='{{ $context['organization']->id }}';
                                            form.encode_sy_id.value='{{ $sy['id'] }}';
                                            form.submit();
                                        "
                                        class="group w-full text-left rounded-lg border px-3 py-2 text-xs transition
                                        cursor-pointer active:scale-[0.98]

                                        {{ $sy['is_active']
                                            ? 'border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100'
                                            : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                        }}

                                        {{ ($activeOrgId == $context['organization']->id && $activeSyId == $sy['id'])
                                            ? 'ring-2 ring-blue-500'
                                            : ''
                                        }}
                                    ">

                                        <div class="flex items-center justify-between">

                                            <span class="font-medium">
                                                {{ $sy['label'] }}
                                            </span>

                                            @if($sy['is_active'])
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500"></i>
                                            @endif

                                        </div>

                                        @if($sy['is_active'])
                                            <div class="text-[10px] mt-1 text-emerald-600">
                                                Active School Year
                                            </div>
                                        @endif

                                    </button>

                                @endforeach

                            </div>

                        </div>

                    @endforeach

                </div>


                {{-- FOOTER --}}
                <div class="mt-5 flex justify-end">

                    <button type="button"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="text-xs text-slate-500 hover:text-slate-700">
                        Logout
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

</x-plain-layout>