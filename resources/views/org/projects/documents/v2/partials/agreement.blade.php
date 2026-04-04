    @if(true)

    <div 
        x-data="{ 
            agreed: false, 
            countdown: 5 
        }"
        x-init="let timer = setInterval(() => { if(countdown > 0) countdown--; else clearInterval(timer) }, 1000)"
        x-show="openAgreement"
        class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60"
    >

        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6">

            {{-- HEADER --}}
            <div class="mb-4 border-b pb-3">
                <h2 class="text-xl font-bold text-slate-900">
                    STUDENT AGREEMENT FORM
                </h2>
                <p class="text-xs text-slate-500">
                    (Digital acknowledgment required before proceeding)
                </p>
            </div>

            {{-- BODY --}}
            <div class="text-sm text-slate-700 space-y-4 max-h-[400px] overflow-y-auto pr-2">

                <p><strong>1. Acknowledgment of Responsibilities</strong></p>
                <p>
                    I acknowledge that I am responsible for submitting all post-documentation requirements for the project that I head. 
                    Failure to do so may result in not being cleared by the Office of Student Affairs.
                </p>

                <p><strong>2. Understanding of Consequences</strong></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Inability to take examinations</li>
                    <li>Inability to obtain official documents</li>
                    <li>Organization restrictions on future projects</li>
                </ul>

                <p><strong>3. Commitment to Compliance</strong></p>
                <p>
                    I commit to fulfilling all requirements on time and understand that this affects my academic standing.
                </p>

                <p><strong>4. Acceptance of Terms</strong></p>
                <p>
                    By proceeding, I confirm that I have read, understood, and agreed to all terms stated above.
                </p>

                {{-- WARNING --}}
                <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs p-3 rounded-lg">
                    ⚠ Important: You must read and agree before continuing. This action is recorded in the system.
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="mt-6 space-y-3">
            @if($needsAgreement)

                {{-- CHECKBOX --}}
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" x-model="agreed" class="rounded border-slate-300">
                    I have read and agree to the terms above
                </label>

                {{-- ACTION --}}
                <form method="POST" action="{{ route('org.projects.agreement.accept', $project) }}">
                    @csrf

                    <button 
                        type="submit"
                        :disabled="!agreed || countdown > 0"
                        class="w-full px-4 py-2 rounded-lg text-white transition"
                        :class="(!agreed || countdown > 0) 
                            ? 'bg-slate-400 cursor-not-allowed' 
                            : 'bg-emerald-600 hover:bg-emerald-700'"
                    >
                        <template x-if="countdown > 0">
                            <span>Please wait <span x-text="countdown"></span>s...</span>
                        </template>

                        <template x-if="countdown === 0">
                            <span>I Agree and Continue</span>
                        </template>
                    </button>
                </form>

            @else

                {{-- VIEW ONLY MODE --}}
                <div class="flex justify-end">
                    <button 
                        @click="openAgreement = false"
                        class="px-4 py-2 text-sm bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300"
                    >
                        Close
                    </button>
                </div>

            @endif

            </div>

        </div>

        </div>

    @endif