<x-app-layout>

<style>
[x-cloak] { display: none !important; }
</style>

<div class="mx-auto max-w-5xl px-4 py-6 space-y-6"

    x-data="{
        receipts: {{ $packet->has_receipts ? 'true' : 'false' }},
        dvs: {{ $packet->has_disbursement_voucher ? 'true' : 'false' }},
        letters: {{ $packet->has_solicitation_letter ? 'true' : 'false' }}
    }"
>

@php
$locked = $packet->received_at !== null;
@endphp

{{-- ================= HEADER ================= --}}
<div class="flex items-start justify-between gap-4">

    <div>
        <h1 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
            <i data-lucide="inbox" class="w-5 h-5 text-amber-600"></i>
            Org Packet Submission
        </h1>

        <div class="text-xs text-slate-500 mt-1">
            {{ $packet->packet_code }}
        </div>
    </div>

    <div class="flex items-center gap-3">

        <a href="{{ route('org.projects.packets.print', [$project,$packet]) }}"
           class="px-3 py-2 text-xs bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition flex items-center gap-1">
            <i data-lucide="printer" class="w-3 h-3"></i>
            Print Cover
        </a>

        <a href="{{ route('org.projects.packets.index', $project) }}"
           class="text-xs text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-3 h-3"></i>
            Back
        </a>

    </div>

</div>


{{-- ================= INSTRUCTIONS ================= --}}
<div class="rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-white p-4 flex gap-3">

    <i data-lucide="info" class="w-4 h-4 text-amber-600 mt-0.5"></i>

    <div class="text-xs text-amber-800 leading-relaxed">

        <div class="font-semibold mb-1">
            About this Packet
        </div>

        <p>
            This packet represents a <span class="font-medium">physical submission</span> of project-related documents
            to the SACDEV Office. Include all supporting materials such as letters, receipts, vouchers,
            and reports related to your activity.
        </p>

        <p class="mt-1">
            Once SACDEV marks this packet as <span class="font-medium">received</span>, it will be locked and can no longer be edited.
        </p>

    </div>

</div>


{{-- ================= LOCKED NOTICE ================= --}}
@if($locked)

<div class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-xs text-amber-800 flex gap-2 items-start">

    <i data-lucide="lock" class="w-4 h-4 mt-0.5"></i>

    <div>
        <div class="font-semibold">
            Packet Received by SACDEV
        </div>

        <div class="mt-1">
            {{ \Carbon\Carbon::parse($packet->received_at)->format('F d, Y h:i A') }}
        </div>

        <div class="text-[11px] mt-1 text-amber-700">
            Editing is now locked.
        </div>
    </div>

</div>

@endif


{{-- ================= PROJECT INFO ================= --}}
<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

    <div class="px-5 py-4 border-b border-slate-100 text-xs font-semibold text-slate-700 flex items-center gap-2">
        <i data-lucide="folder" class="w-4 h-4 text-slate-500"></i>
        Project Information
    </div>

    <div class="px-5 py-4 text-xs text-slate-700 space-y-2">
        <div><strong>Project:</strong> {{ $project->title }}</div>
        <div><strong>Packet Code:</strong> {{ $packet->packet_code }}</div>
        <div><strong>Generated:</strong>
            {{ \Carbon\Carbon::parse($packet->generated_at)->format('F d, Y') }}
        </div>
    </div>

</div>


{{-- ================= RETURN REMARKS ================= --}}
@if($packet->return_remarks)

<div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">

    <div class="font-semibold mb-1 flex items-center gap-1">
        <i data-lucide="corner-down-left" class="w-3 h-3"></i>
        Packet Returned by SACDEV
    </div>

    <div>{{ $packet->return_remarks }}</div>

    @if($packet->returned_at)
        <div class="text-[11px] mt-1 italic text-amber-700">
            Returned on {{ \Carbon\Carbon::parse($packet->returned_at)->format('F d, Y h:i A') }}
        </div>
    @endif

</div>

@endif


<form method="POST" action="{{ route('org.projects.packets.update', [$project,$packet]) }}">
@csrf

{{-- ================= DOCUMENT CHECKLIST ================= --}}
<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm">

    <div class="px-5 py-4 border-b border-slate-100 text-xs font-semibold text-slate-700 flex items-center gap-2">
        <i data-lucide="check-square" class="w-4 h-4 text-slate-500"></i>
        Documents Included
    </div>

    <div class="px-5 py-4 text-xs space-y-3">

        @foreach([
            'has_solicitation_letter' => 'Solicitation / Sponsorship Letters',
            'has_disbursement_voucher' => 'Disbursement Voucher',
            'has_collection_report' => 'Collection Report',
            'has_certificates' => 'Certificates',
            'has_receipts' => 'Official Receipts'
        ] as $field => $label)

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox"
                name="{{ $field }}"
                value="1"

                @if($field === 'has_receipts')
                    x-model="receipts"
                @endif

                @if($field === 'has_disbursement_voucher')
                    x-model="dvs"
                @endif

                @if($field === 'has_solicitation_letter')
                    x-model="letters"
                @endif

                {{ $packet->$field ? 'checked' : '' }}
                {{ $locked ? 'disabled' : '' }}
                class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">

            <span class="text-slate-700">{{ $label }}</span>
        </label>

        @endforeach

    </div>


    {{-- OTHER ITEMS --}}
    <div class="px-5 pb-4">

        <label class="block text-xs font-medium mb-1 text-slate-600">
            Other Items Included
        </label>

        <textarea
            name="other_items"
            rows="2"
            {{ $locked ? 'disabled' : '' }}
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
            placeholder="Example: Attendance Sheet, Signed MOA, Event Photos"
        >{{ old('other_items', $packet->other_items) }}</textarea>

    </div>

    @if(!$locked)
    <div class="px-5 py-4 border-t border-slate-100 text-right">

        <button
            type="submit"
            name="save_all"
            value="1"
            class="px-3 py-2 text-xs font-medium rounded-lg bg-amber-600 text-white hover:bg-amber-700 transition shadow-sm">
            Save Changes
        </button>

    </div>
    @endif

</div>


{{-- ================= SOLICITATION LETTERS ================= --}}
<div x-show="letters" x-transition x-cloak
     class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm mb-6 overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">

        <div class="flex items-center gap-2">
            <i data-lucide="mail" class="w-4 h-4 text-amber-600"></i>

            <span class="text-xs font-semibold text-slate-700">
                Solicitation / Sponsorship Letters
            </span>
        </div>

        <span class="text-[10px] text-slate-400">
            Optional
        </span>

    </div>


    <div class="px-5 py-4 text-xs text-slate-700 space-y-4">

        {{-- TABLE --}}
        @if($packet->letters->count())

        <div class="overflow-x-auto">

            <table class="w-full text-xs">

                <thead class="border-b border-slate-100 text-slate-500">
                    <tr>
                        <th class="text-left py-2 font-medium">Control Number</th>
                        <th class="text-left py-2 font-medium">Organization</th>
                        <th class="text-right py-2 font-medium">Action</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($packet->letters as $letter)

                <tr class="border-b border-slate-100 hover:bg-slate-50 transition">

                    <td class="py-2 text-slate-800 font-medium">
                        {{ $letter->control_number }}
                    </td>

                    <td class="py-2 text-slate-600">
                        {{ $letter->organization_name }}
                    </td>

                    <td class="text-right">

                        @if(!$locked)
                            <button type="button"
                                onclick="deleteItem('{{ route('org.projects.packets.letters.destroy', [$project,$packet,$letter]) }}')"
                                class="text-rose-600 hover:text-rose-700 font-medium transition">
                                Remove
                            </button>

                        @endif

                    </td>

                </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        @else

        {{-- EMPTY STATE --}}
        <div class="text-[11px] text-slate-400 italic">
            No solicitation letters added yet.
        </div>

        @endif


        {{-- FORM FIELDS NOW USE MAIN UPDATE FORM --}}
        @if(!$locked)

        <div class="space-y-3">

            <div class="grid grid-cols-2 gap-3">

                <input type="text"
                       name="control_number"
                       value="{{ old('control_number') }}"
                       placeholder="Control Number"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs
                              focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">

                <input type="text"
                       name="organization_name"
                       value="{{ old('organization_name') }}"
                       placeholder="Organization"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs
                              focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">

            </div>

            <div class="flex justify-end">

                <button
                    type="submit"
                    name="add_letter"
                    value="1"
                    class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg
                           bg-amber-600 text-white hover:bg-amber-700 transition shadow-sm">

                    <i data-lucide="plus" class="w-3 h-3"></i>
                    Add Letter
                </button>

            </div>

        </div>

        @endif

    </div>

</div>



{{-- RECEIPTS --}}
<div x-show="receipts" x-transition x-cloak>
    @include('org.packets.partials.receipts')
</div>

{{-- DISBURSEMENT VOUCHERS --}}
<div x-show="dvs" x-transition x-cloak>
    @include('org.packets.partials.dvs')
</div>

</form>

</div>

<script>
function deleteItem(url) {
    if (!confirm('Remove this item?')) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
    `;

    document.body.appendChild(form);
    form.submit();
}
</script>

</x-app-layout>