@if(true)

<div 
    x-data="{ 
        agreed: false, 
        countdown: 5 
    }"
    x-init="let timer = setInterval(() => { if(countdown > 0) countdown--; else clearInterval(timer) }, 1000)"
    x-show="openAgreement"
    class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm"
>

    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 space-y-5">

        {{-- HEADER --}}
        <div class="flex items-start justify-between border-b pb-3">

            <div class="flex items-start gap-2">
                <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600 mt-0.5"></i>

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Student Agreement
                    </h2>
                    <p class="text-[11px] text-slate-500">
                        Digital acknowledgment required before proceeding
                    </p>
                </div>
            </div>

            <button 
                @click="openAgreement = false"
                class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

        </div>


        {{-- BODY --}}
        <div class="text-[11px] text-slate-700 space-y-4 max-h-[400px] overflow-y-auto pr-2">

            <div class="space-y-1">
                <div class="font-semibold text-slate-800">
                    1. Acknowledgment of Responsibilities
                </div>
                <p>
                    I am responsible for submitting all post-documentation requirements for the project I lead.
                </p>
            </div>

            <div class="space-y-1">
                <div class="font-semibold text-slate-800">
                    2. Understanding of Consequences
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Inability to take examinations</li>
                    <li>Inability to obtain official documents</li>
                    <li>Organization restrictions on future projects</li>
                </ul>
            </div>

            <div class="space-y-1">
                <div class="font-semibold text-slate-800">
                    3. Commitment to Compliance
                </div>
                <p>
                    I commit to completing all requirements on time.
                </p>
            </div>

            <div class="space-y-1">
                <div class="font-semibold text-slate-800">
                    4. Acceptance of Terms
                </div>
                <p>
                    By proceeding, I confirm that I have read and understood the terms above.
                </p>
            </div>

            {{-- WARNING --}}
            <div class="flex items-start gap-2 bg-amber-50 border border-amber-200 text-amber-800 text-[11px] p-3 rounded-lg">

                <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5 text-amber-600"></i>

                <div>
                    <div class="font-semibold">Important</div>
                    <div>
                        You must read and agree before continuing. This action is recorded in the system.
                    </div>
                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="space-y-3 pt-2">

        @if($needsAgreement)

            {{-- CHECKBOX --}}
            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                <input type="checkbox" x-model="agreed" class="rounded border-slate-300">
                I have read and agree to the terms above
            </label>

            {{-- ACTION --}}
            <form method="POST" action="{{ route('org.projects.agreement.accept', $project) }}">
                @csrf

                <button 
                    type="submit"
                    :disabled="!agreed || countdown > 0"
                    class="w-full px-4 py-2 rounded-lg text-white text-xs font-semibold transition shadow-sm"
                    :class="(!agreed || countdown > 0) 
                        ? 'bg-slate-400 cursor-not-allowed' 
                        : 'bg-emerald-600 hover:bg-emerald-700'"
                >

                    <template x-if="countdown > 0">
                        <span>Please wait <span x-text="countdown"></span>s...</span>
                    </template>

                    <template x-if="countdown === 0">
                        <span class="flex items-center justify-center gap-1">
                            I Agree and Continue
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </span>
                    </template>

                </button>
            </form>

        @else

            {{-- VIEW MODE --}}
            <div class="flex justify-end">
                <button 
                    @click="openAgreement = false"
                    class="px-4 py-2 text-xs bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition">
                    Close
                </button>
            </div>

        @endif

        </div>

    </div>

</div>

@endif