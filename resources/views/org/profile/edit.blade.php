<x-app-layout>

    @php
        $isOwner = auth()->id() === $user->id;
    @endphp

    <style>
        .page-container {
            max-width: 1200px;
        }
    </style>

    {{-- GLOBAL ALPINE WRAPPER --}}
    <div 
        x-data="{
            editingAll: false,
            dirtySections: {},

            markDirty(section) {
                this.dirtySections[section] = true;
            },

            hasDirty() {
                return Object.values(this.dirtySections).some(v => v);
            }
        }"
        @mark-dirty.window="markDirty($event.detail)"
    >

        {{-- HEADER --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-4 flex items-center justify-between">

            <div class="flex items-start gap-3">
                <div class="mt-1 flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        {{ $isOwner ? 'My Profile' : 'Profile' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $isOwner 
                            ? 'Manage your personal and organization-related information.' 
                            : 'Viewing user profile information.' 
                        }}
                    </p>
                </div>
            </div>

            @if($isOwner)
            <button 
                type="button"
                @click="editingAll = !editingAll"
                class="text-xs font-semibold px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition"
            >
                <span x-show="!editingAll">Edit Profile</span>
                <span x-show="editingAll">Cancel</span>
            </button>
            @endif

        </div>


        <div class="py-8">
            <div class="page-container mx-auto px-4 lg:px-6 space-y-8">



                @if($isOwner)
                <form method="POST" 
                      enctype="multipart/form-data" 
                      action="{{ route('org.profile.update') }}"
                      @submit="dirtySections = {}">
                    @csrf
                @endif


                {{-- MAIN GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- LEFT COLUMN --}}
                    <div class="space-y-6">

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.personal', ['isOwner' => $isOwner])
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.contact', ['isOwner' => $isOwner])
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.address', ['isOwner' => $isOwner])
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.skills', ['isOwner' => $isOwner])
                        </div>

                        @if($user->isModerator())
                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.moderator', ['isOwner' => $isOwner])
                        </div>
                        @endif

                    </div>


                    {{-- RIGHT COLUMN --}}
                    <div class="space-y-6">

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.leaderships', ['isOwner' => $isOwner])
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.trainings', ['isOwner' => $isOwner])
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4">
                            @include('org.profile.partials.awards', ['isOwner' => $isOwner])
                        </div>

                    </div>

                </div>


                @if($isOwner)
                {{-- FLOATING SAVE BAR --}}
                <div class="fixed bottom-4 right-4 z-50"
                     x-show="hasDirty()"
                     x-transition>

                    <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-lg">

                        <div class="text-[11px] text-slate-500 space-y-1">

                            <div class="font-medium text-slate-600">
                                <span x-text="Object.values(dirtySections).filter(v => v).length"></span>
                                section(s) modified
                            </div>

                            <div class="flex flex-wrap gap-1">
                                <template x-for="(value, key) in dirtySections" :key="key">
                                    <span x-show="value"
                                          class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200 text-[10px] capitalize">
                                        <span x-text="key.replace('_', ' ')"></span>
                                    </span>
                                </template>
                            </div>

                        </div>

                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition">
                            Save Changes
                        </button>

                    </div>

                </div>

                </form>
                @endif

            </div>
        </div>

    </div>

</x-app-layout>