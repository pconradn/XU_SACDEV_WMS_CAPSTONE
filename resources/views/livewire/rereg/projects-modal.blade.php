<div>
    @if($show)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">

            <div class="w-full max-w-2xl rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 shadow-sm max-h-[90vh] overflow-y-auto">

                {{-- HEADER --}}
                <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-start justify-between">

                    <div class="space-y-1">
                        <div class="text-[11px] uppercase tracking-wide text-slate-500 font-semibold">
                            Project Entry
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900">
                            {{ $viewMode ? 'View Project' : ($isEdit ? 'Edit Project' : 'Add Project') }}
                        </h3>
                        <p class="text-[11px] text-slate-500">
                            Define the project details, expected outcomes, and stakeholders.
                        </p>
                    </div>

                    <button type="button"
                            wire:click="$set('show', false)"
                            class="text-xs text-slate-500 hover:text-slate-700 transition">
                        Close
                    </button>

                </div>

                <form method="POST" action="{{ $formAction }}">
                    @csrf
                    @if($formMethod === 'PUT')
                        @method('PUT')
                    @endif

                    <div class="p-5 space-y-5">

                        <input type="hidden" name="category" value="{{ $category }}">

                        {{-- BASIC --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-4 shadow-sm">

                            <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                Project Details
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                @php
                                    $formattedDate = $target_date ? \Carbon\Carbon::parse($target_date)->format('Y-m-d') : '';
                                    $formattedBudget = $budget ? number_format((float)$budget, 0, '.', ',') : '';
                                    
                                @endphp


                                <div>
                                    <label class="text-xs font-medium text-slate-600">
                                        Target Date
                                    </label>
                                    <p class="text-[11px] text-slate-400">
                                        Planned implementation date
                                    </p>
                                    <input type="date"
                                        name="target_date"
                                        wire:model="target_date"
                                        class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                        @disabled($viewMode)>
                                </div>

                                <div>
                                    <label class="text-xs font-medium text-slate-600">
                                        Budget
                                    </label>
                                    <p class="text-[11px] text-slate-400">
                                        Estimated total cost (₱)
                                    </p>
                                    <input type="text"
                                            x-data
                                            x-on:input="
                                                    let v = $el.value.replace(/[^0-9]/g,'');
                                                    $el.value = new Intl.NumberFormat().format(v);
                                            "
                                            {{ $attributes->merge(['class' => 'w-full rounded-lg border-slate-200 text-sm px-3 py-2']) }}
                                           name="budget"
                                           wire:model="budget"
                                           class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                           @disabled($viewMode)>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-medium text-slate-600">
                                        Project Title
                                    </label>
                                    <p class="text-[11px] text-slate-400">
                                        Clear and concise project name
                                    </p>
                                    <input type="text"
                                           name="title"
                                           wire:model="title"
                                           class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                           placeholder="e.g. Leadership Workshop"
                                           @disabled($viewMode)>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-medium text-slate-600">
                                        Implementing Body
                                    </label>
                                    <p class="text-[11px] text-slate-400">
                                        Committee or team responsible for execution
                                    </p>
                                    <textarea name="implementing_body"
                                              wire:model="implementing_body"
                                              rows="2"
                                              class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                              @disabled($viewMode)></textarea>
                                </div>

                            </div>

                        </div>

                        {{-- OBJECTIVES --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                        Objectives
                                    </div>
                                    <p class="text-[11px] text-slate-400">
                                        What the project aims to achieve
                                    </p>
                                </div>

                                @if(!$viewMode)
                                    <button type="button"
                                            wire:click="addObjective"
                                            class="text-[11px] font-semibold text-blue-600 hover:underline">
                                        + Add
                                    </button>
                                @endif
                            </div>

                            @foreach($objectives as $i => $obj)
                                <div class="flex gap-2">
                                    <input name="objectives[]"
                                           wire:model="objectives.{{ $i }}"
                                           class="w-full rounded-lg border-slate-200 text-sm"
                                           @disabled($viewMode)>

                                    @if(!$viewMode)
                                        <button type="button"
                                                wire:click="removeObjective({{ $i }})"
                                                class="text-rose-500 text-xs px-2">
                                            ✕
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- BENEFICIARIES --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                        Beneficiaries
                                    </div>
                                    <p class="text-[11px] text-slate-400">
                                        Who will benefit from this project
                                    </p>
                                </div>

                                @if(!$viewMode)
                                    <button type="button"
                                            wire:click="addBeneficiary"
                                            class="text-[11px] font-semibold text-blue-600 hover:underline">
                                        + Add
                                    </button>
                                @endif
                            </div>

                            @foreach($beneficiaries as $i => $item)
                                <div class="flex gap-2">
                                    <input name="beneficiaries[]"
                                           wire:model="beneficiaries.{{ $i }}"
                                           class="w-full rounded-lg border-slate-200 text-sm"
                                           @disabled($viewMode)>

                                    @if(!$viewMode)
                                        <button type="button"
                                                wire:click="removeBeneficiary({{ $i }})"
                                                class="text-rose-500 text-xs px-2">
                                            ✕
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- DELIVERABLES --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                        Deliverables
                                    </div>
                                    <p class="text-[11px] text-slate-400">
                                        Expected outputs or results
                                    </p>
                                </div>

                                @if(!$viewMode)
                                    <button type="button"
                                            wire:click="addDeliverable"
                                            class="text-[11px] font-semibold text-blue-600 hover:underline">
                                        + Add
                                    </button>
                                @endif
                            </div>

                            @foreach($deliverables as $i => $item)
                                <div class="flex gap-2">
                                    <input name="deliverables[]"
                                           wire:model="deliverables.{{ $i }}"
                                           class="w-full rounded-lg border-slate-200 text-sm"
                                           @disabled($viewMode)>

                                    @if(!$viewMode)
                                        <button type="button"
                                                wire:click="removeDeliverable({{ $i }})"
                                                class="text-rose-500 text-xs px-2">
                                            ✕
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- PARTNERS --}}
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-3 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                                        Partners
                                    </div>
                                    <p class="text-[11px] text-slate-400">
                                        External collaborators or sponsors
                                    </p>
                                </div>

                                @if(!$viewMode)
                                    <button type="button"
                                            wire:click="addPartner"
                                            class="text-[11px] font-semibold text-blue-600 hover:underline">
                                        + Add
                                    </button>
                                @endif
                            </div>

                            @foreach($partners as $i => $item)
                                <div class="flex gap-2">
                                    <input name="partners[]"
                                           wire:model="partners.{{ $i }}"
                                           class="w-full rounded-lg border-slate-200 text-sm"
                                           @disabled($viewMode)>

                                    @if(!$viewMode)
                                        <button type="button"
                                                wire:click="removePartner({{ $i }})"
                                                class="text-rose-500 text-xs px-2">
                                            ✕
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>

                    @if(!$viewMode)
                        <div class="sticky bottom-0 bg-white border-t border-slate-200 px-5 py-4 flex justify-between items-center">

                            <div class="text-[11px] text-slate-400">
                                Ensure all required details are complete before saving
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg
                                           bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
                                <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                Save Project
                            </button>

                        </div>
                    @endif

                </form>

            </div>
        </div>
    @endif
</div>