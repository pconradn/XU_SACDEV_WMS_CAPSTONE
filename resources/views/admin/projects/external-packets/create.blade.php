<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- PAGE HEADER --}}
        <div class="rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-white shadow-sm overflow-hidden">
            <div class="px-6 py-5 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-blue-700">
                        {{-- Lucide-like icon --}}
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                            <path d="m3.3 7 8.7 5 8.7-5"/>
                            <path d="M12 22V12"/>
                        </svg>
                        External Packet Builder
                    </div>

                    <h1 class="mt-2 text-xl sm:text-2xl font-semibold text-slate-900">
                        Create External Packet
                    </h1>

                    <p class="mt-2 text-sm text-slate-600 max-w-3xl">
                        Build a packet for documents and external submission items related to this project. Select official project documents, add manual items if needed, then create the packet to generate its reference number and printable cover page.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-[11px]">
                        <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-3 py-1 font-medium text-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Project: {{ $project->title }}
                        </span>

                        @if($project->workflow_status)
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 font-medium text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Workflow: {{ str_replace('_', ' ', ucfirst($project->workflow_status)) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="shrink-0">
                    <a href="{{ route('admin.external-packets.index', $project) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 hover:shadow">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                        Back to Packets
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-gradient-to-r from-rose-50 to-white shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-rose-100">
                    <div class="flex items-center gap-2 text-sm font-semibold text-rose-800">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v5"/>
                            <path d="M12 16h.01"/>
                        </svg>
                        Please fix the following before creating the packet
                    </div>
                </div>
                <div class="px-5 py-4">
                    <ul class="space-y-1 text-sm text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @php
            $documentsByPhase = $documents->groupBy(function ($doc) {
                return $doc->formType->phase ?? 'other';
            });

            $phaseLabels = [
                'pre_implementation' => 'Pre-Implementation Forms',
                'off-campus' => 'Off-Campus Forms',
                'post_implementation' => 'Post-Implementation Forms',
                'notice' => 'Notice Forms',
                'other' => 'Other Forms',
            ];

            $oldManualItems = old('manual_items', [
                ['type' => 'other', 'label' => '', 'notes' => '']
            ]);
        @endphp

        <form method="POST"
              action="{{ route('admin.external-packets.store', $project) }}"
              x-data="{
                    search: '',
                    manualItems: @js($oldManualItems),
                    selectedDocs: @js(collect(old('documents', []))->map(fn($id) => (string) $id)->values()),
                    addManualItem() {
                        this.manualItems.push({ type: 'other', label: '', notes: '' });
                    },
                    removeManualItem(index) {
                        if (this.manualItems.length === 1) {
                            this.manualItems[0] = { type: 'other', label: '', notes: '' };
                            return;
                        }
                        this.manualItems.splice(index, 1);
                    }
              }"
              class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

                {{-- LEFT --}}
                <div class="xl:col-span-8 space-y-6">

                    {{-- BASIC INFO --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 19h16"/>
                                    <path d="M4 5h16"/>
                                    <path d="M10 9h4"/>
                                    <path d="M8 13h8"/>
                                </svg>
                                Packet Details
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Enter the destination and any remarks for this external packet.
                            </p>
                        </div>

                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-semibold uppercase tracking-wide text-slate-600 mb-2">
                                    Destination <span class="text-rose-600">*</span>
                                </label>
                                <input type="text"
                                       name="destination"
                                       value="{{ old('destination') }}"
                                       placeholder="e.g. Office of Student Affairs, College Dean, Accounting Office"
                                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-semibold uppercase tracking-wide text-slate-600 mb-2">
                                    Remarks
                                </label>
                                <textarea name="remarks"
                                          rows="4"
                                          placeholder="Optional notes about the packet purpose, delivery context, or handling instructions."
                                          class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('remarks') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- DOCUMENTS --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                        <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <path d="M14 2v6h6"/>
                                            <path d="M16 13H8"/>
                                            <path d="M16 17H8"/>
                                            <path d="M10 9H8"/>
                                        </svg>
                                        Select Project Documents
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Choose existing project documents that should be included in the external packet.
                                    </p>
                                </div>

                                <div class="relative w-full lg:w-72">
                                    <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <circle cx="11" cy="11" r="8"/>
                                            <path d="m21 21-4.3-4.3"/>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           x-model="search"
                                           placeholder="Search document name or code..."
                                           class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-3 py-2 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="divide-y divide-slate-200">
                            @forelse($documentsByPhase as $phase => $phaseDocuments)
                                <div class="p-5">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                            {{ $phaseLabels[$phase] ?? ucwords(str_replace('_', ' ', $phase)) }}
                                        </span>
                                        <span class="text-[11px] text-slate-500">
                                            {{ $phaseDocuments->count() }} item{{ $phaseDocuments->count() !== 1 ? 's' : '' }}
                                        </span>
                                    </div>

                                    <div class="overflow-hidden rounded-2xl border border-slate-200">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-xs">
                                                <thead class="bg-slate-50 text-slate-600">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide w-14">Pick</th>
                                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide">Document</th>
                                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide">Code</th>
                                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide">Status</th>
                                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide">Updated</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-200 bg-white">
                                                    @foreach($phaseDocuments as $document)
                                                        @php
                                                            $formName = $document->formType->name ?? 'Untitled Form';
                                                            $formCode = $document->formType->code ?? ($document->form_type_code ?? '—');
                                                            $docStatus = $document->status ?? 'draft';
                                                        @endphp

                                                        <tr
                                                            x-show="
                                                                search === '' ||
                                                                @js(strtolower($formName)).includes(search.toLowerCase()) ||
                                                                @js(strtolower((string) $formCode)).includes(search.toLowerCase())
                                                            "
                                                            class="transition hover:bg-slate-50"
                                                        >
                                                            <td class="px-4 py-3 align-top">
                                                                <label class="inline-flex items-center">
                                                                    <input type="checkbox"
                                                                           name="documents[]"
                                                                           value="{{ $document->id }}"
                                                                           x-model="selectedDocs"
                                                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                                </label>
                                                            </td>

                                                            <td class="px-4 py-3 align-top">
                                                                <div class="font-medium text-slate-900">
                                                                    {{ $formName }}
                                                                </div>
                                                                @if(!empty($document->remarks))
                                                                    <div class="mt-1 text-[11px] text-slate-500 line-clamp-2">
                                                                        {{ $document->remarks }}
                                                                    </div>
                                                                @endif
                                                            </td>

                                                            <td class="px-4 py-3 align-top">
                                                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                                                                    {{ $formCode }}
                                                                </span>
                                                            </td>

                                                            <td class="px-4 py-3 align-top">
                                                                @if($docStatus === 'approved_by_sacdev')
                                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700">
                                                                        Approved by SACDEV
                                                                    </span>
                                                                @elseif($docStatus === 'approved')
                                                                    <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-[11px] font-medium text-blue-700">
                                                                        Approved
                                                                    </span>
                                                                @elseif($docStatus === 'returned')
                                                                    <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2 py-0.5 text-[11px] font-medium text-rose-700">
                                                                        Returned
                                                                    </span>
                                                                @elseif($docStatus === 'submitted')
                                                                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700">
                                                                        Submitted
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                                                                        {{ ucfirst(str_replace('_', ' ', $docStatus)) }}
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            <td class="px-4 py-3 align-top text-slate-500">
                                                                {{ optional($document->updated_at)->format('M d, Y h:i A') ?? '—' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <div class="mx-auto w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <path d="M14 2v6h6"/>
                                        </svg>
                                    </div>
                                    <h3 class="mt-3 text-sm font-semibold text-slate-900">No project documents found</h3>
                                    <p class="mt-1 text-xs text-slate-500">
                                        There are currently no project documents available to include in this packet.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- MANUAL ITEMS --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-white">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                        <svg class="w-4 h-4 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M12 5v14"/>
                                            <path d="M5 12h14"/>
                                        </svg>
                                        Manual Items
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Add physical enclosures, files, letters, or other non-document items included in the packet.
                                    </p>
                                </div>

                                <button type="button"
                                        @click="addManualItem()"
                                        class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-white px-3 py-2 text-xs font-semibold text-amber-700 shadow-sm transition hover:bg-amber-50 hover:shadow">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 5v14"/>
                                        <path d="M5 12h14"/>
                                    </svg>
                                    Add Item
                                </button>
                            </div>
                        </div>

                        <div class="p-5 space-y-4">
                            <template x-for="(item, index) in manualItems" :key="index">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 shadow-sm">
                                    <div class="flex items-center justify-between gap-3 mb-4">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                                            Manual Item <span x-text="index + 1"></span>
                                        </div>

                                        <button type="button"
                                                @click="removeManualItem(index)"
                                                class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-white px-2.5 py-1.5 text-[11px] font-medium text-rose-700 transition hover:bg-rose-50">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4h8v2"/>
                                                <path d="M19 6l-1 14H6L5 6"/>
                                                <path d="M10 11v6"/>
                                                <path d="M14 11v6"/>
                                            </svg>
                                            Remove
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <div class="md:col-span-3">
                                            <label class="block text-[11px] font-semibold uppercase tracking-wide text-slate-600 mb-2">
                                                Type
                                            </label>
                                            <select :name="`manual_items[${index}][type]`"
                                                    x-model="item.type"
                                                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="form">Form</option>
                                                <option value="clearance">Clearance</option>
                                                <option value="file">File</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        <div class="md:col-span-5">
                                            <label class="block text-[11px] font-semibold uppercase tracking-wide text-slate-600 mb-2">
                                                Label
                                            </label>
                                            <input type="text"
                                                   :name="`manual_items[${index}][label]`"
                                                   x-model="item.label"
                                                   placeholder="e.g. Official Letter, Printed Permit, Supporting Certificates"
                                                   class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>

                                        <div class="md:col-span-4">
                                            <label class="block text-[11px] font-semibold uppercase tracking-wide text-slate-600 mb-2">
                                                Notes
                                            </label>
                                            <input type="text"
                                                   :name="`manual_items[${index}][notes]`"
                                                   x-model="item.notes"
                                                   placeholder="Optional note"
                                                   class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="xl:col-span-4 space-y-6">

                    {{-- WORKFLOW INFO --}}
                    <div class="rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-emerald-100">
                            <div class="flex items-center gap-2 text-sm font-semibold text-emerald-900">
                                <svg class="w-4 h-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                External Packet Workflow
                            </div>
                        </div>
                        <div class="p-5 space-y-3 text-xs text-slate-700">
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                                <div class="font-semibold text-slate-900">1. Create Packet</div>
                                <div class="mt-1 text-slate-500">
                                    Saving this form creates the packet record and automatically assigns a reference number.
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                                <div class="font-semibold text-slate-900">2. Print Cover Page</div>
                                <div class="mt-1 text-slate-500">
                                    The packet printable includes the packet details and QR code for admin receiving.
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                                <div class="font-semibold text-slate-900">3. Submit and Receive</div>
                                <div class="mt-1 text-slate-500">
                                    Once physically submitted, receiving staff can search or scan the QR code to process all enclosed items.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SUMMARY --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M3 3h18v18H3z"/>
                                    <path d="M9 9h6v6H9z"/>
                                </svg>
                                Packet Summary
                            </div>
                        </div>

                        <div class="p-5 space-y-4 text-xs">
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Selected Documents</span>
                                <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 font-semibold text-blue-700"
                                      x-text="selectedDocs.length"></span>
                            </div>

                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Manual Item Rows</span>
                                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 font-semibold text-amber-700"
                                      x-text="manualItems.length"></span>
                            </div>

                            <div class="border-t border-slate-200 pt-4">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-slate-600">
                                    <div class="font-semibold text-slate-800 mb-1">Important</div>
                                    At least one project document or one manual item with a label must be added before the packet can be created.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- REFERENCE PREVIEW --}}
                    <div class="rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-blue-100">
                            <div class="flex items-center gap-2 text-sm font-semibold text-blue-900">
                                <svg class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="M7 9h10"/>
                                    <path d="M7 13h6"/>
                                </svg>
                                Reference Number
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="rounded-xl border border-dashed border-blue-200 bg-white px-4 py-4 text-center">
                                <div class="text-[11px] uppercase tracking-wide text-blue-600 font-semibold">
                                    Generated automatically
                                </div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">
                                    Assigned after creation
                                </div>
                                <div class="mt-1 text-[11px] text-slate-500">
                                    Example format: EP-{{ now()->format('Y') }}-0001
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- HELP CARD --}}
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M9.09 9a3 3 0 1 1 5.82 1c0 2-3 2-3 4"/>
                                    <path d="M12 17h.01"/>
                                </svg>
                                Quick Guidance
                            </div>
                        </div>
                        <div class="p-5 space-y-3 text-xs text-slate-600">
                            <div class="group relative rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 hover:bg-white transition">
                                <div class="font-medium text-slate-800">Use official documents when possible</div>
                                <div class="mt-1 text-slate-500">
                                    Select actual project documents for traceability.
                                </div>
                                <div class="absolute right-3 top-3 hidden group-hover:block rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] text-slate-500 shadow-sm">
                                    Better audit trail
                                </div>
                            </div>

                            <div class="group relative rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 hover:bg-white transition">
                                <div class="font-medium text-slate-800">Use manual items for physical inclusions</div>
                                <div class="mt-1 text-slate-500">
                                    Add items like permits, signed hardcopies, and enclosures.
                                </div>
                                <div class="absolute right-3 top-3 hidden group-hover:block rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] text-slate-500 shadow-sm">
                                    Physical-only contents
                                </div>
                            </div>

                            <div class="group relative rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 hover:bg-white transition">
                                <div class="font-medium text-slate-800">Receiving decides final item results</div>
                                <div class="mt-1 text-slate-500">
                                    Staff will mark each item approved or returned from the receiving page.
                                </div>
                                <div class="absolute right-3 top-3 hidden group-hover:block rounded-lg border border-slate-200 bg-white px-2 py-1 text-[11px] text-slate-500 shadow-sm">
                                    QR-enabled receiving
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- STICKY ACTION BAR --}}
            <div class="sticky bottom-4 z-20">
                <div class="rounded-2xl border border-slate-200 bg-white/95 backdrop-blur shadow-lg px-4 py-3">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-wrap items-center gap-2 text-[11px]">
                            <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 font-semibold text-blue-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Docs Selected:
                                <span x-text="selectedDocs.length"></span>
                            </span>

                            <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 font-semibold text-amber-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Manual Rows:
                                <span x-text="manualItems.length"></span>
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.external-packets.index', $project) }}"
                               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-600 bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 hover:shadow">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 5v14"/>
                                    <path d="M5 12h14"/>
                                </svg>
                                Create External Packet
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</x-app-layout>