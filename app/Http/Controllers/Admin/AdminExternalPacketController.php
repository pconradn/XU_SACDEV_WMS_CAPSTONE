<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExternalPacket;
use App\Models\ExternalPacketItem;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Audit;

class AdminExternalPacketController extends Controller
{
    public function index(Project $project)
    {
        $packets = ExternalPacket::with(['creator', 'updater'])
            ->where('project_id', $project->id)
            ->latest()
            ->get();



        //dd([
        //    'route_name' => request()->route()->getName(),
        //    'middleware' => request()->route()->gatherMiddleware(),
        //    'path' => request()->path(),
        //]);

        return view('admin.projects.external-packets.index', [
            'project' => $project,
            'packets' => $packets,
        ]);
    }

    public function create(Project $project)
    {
        $documents = ProjectDocument::where('project_id', $project->id)
            ->with('formType')
            ->latest()
            ->get();

        return view('admin.projects.external-packets.create', [
            'project' => $project,
            'documents' => $documents,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        //$this->ensureProjectAllowedForExternalPacket($project);
        \Log::info('STORE HIT', [
            'data' => $request->all(),
            'method' => $request->method(),
        ]);
        if (
            $request->input('intent') !== 'create_packet_form' ||
            !$request->filled('destination')
        ) {
            \Log::warning('BLOCKED AUTO STORE', [
                'data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.external-packets.create', $project);
        }

        $request->validate([
            'destination' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'integer|exists:project_documents,id',
            'manual_items' => 'nullable|array',
            'manual_items.*.type' => 'nullable|in:form,clearance,file,other',
            'manual_items.*.label' => 'nullable|string|max:255',
            'manual_items.*.notes' => 'nullable|string',
        ]);

        $selectedDocumentIds = collect($request->input('documents', []))
            ->filter()
            ->unique()
            ->values();

        $manualItems = collect($request->input('manual_items', []))
            ->filter(function ($item) {
                return !empty(trim((string)($item['label'] ?? '')));
            })
            ->values();

        if ($selectedDocumentIds->isEmpty() && $manualItems->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors([
                    'packet' => 'Add at least one item before creating the packet.'
                ]);
        }

        $packet = DB::transaction(function () use ($request, $project, $selectedDocumentIds, $manualItems) {
            $packet = ExternalPacket::create([
                'project_id' => $project->id,
                'destination' => $request->destination,
                'remarks' => $request->remarks,
                'status' => 'prepared',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            if ($selectedDocumentIds->isNotEmpty()) {
                $documents = ProjectDocument::with('formType')
                    ->where('project_id', $project->id)
                    ->whereIn('id', $selectedDocumentIds)
                    ->get()
                    ->keyBy('id');

                foreach ($selectedDocumentIds as $docId) {
                    $doc = $documents->get($docId);

                    if (!$doc) {
                        continue;
                    }

                    $this->addItem(
                        packet: $packet,
                        type: 'form',
                        label: $doc->formType->name ?? 'Project Document',
                        notes: null,
                        documentId: $doc->id,
                        formTypeCode: $doc->form_type_code ?? $doc->formType?->code
                    );
                }
            }

            foreach ($manualItems as $item) {
                $this->addItem(
                    packet: $packet,
                    type: $item['type'] ?? 'other',
                    label: trim((string)$item['label']),
                    notes: $item['notes'] ?? null,
                    documentId: null,
                    formTypeCode: null
                );
            }

            Audit::log('external_packet.created', 'External packet created', [
                'actor_user_id' => auth()->id(),
                'meta' => [
                    'project_id' => $project->id,
                    'external_packet_id' => $packet->id,
                    'reference_no' => $packet->reference_no,
                ],
            ]);

            return $packet;
        });

        return redirect()
            ->route('admin.external-packets.index', [$project, $packet])
            ->with('success', 'External packet created successfully. Ready for printing.');
    }

    public function show(Project $project, ExternalPacket $packet)
    {
        $this->ensurePacketBelongsToProject($project, $packet);

        $packet->load([
            'project',
            'items.document.formType',
            'creator',
            'updater',
        ]);

        return view('admin.projects.external-packets.show', [
            'project' => $project,
            'packet' => $packet,
        ]);
    }

    public function submit(Project $project, ExternalPacket $packet)
    {
        $this->ensurePacketBelongsToProject($project, $packet);

        if ($packet->status !== 'prepared') {
            return back()->with('error', 'Only prepared packets can be submitted.');
        }

        if ($packet->items()->count() === 0) {
            return back()->with('error', 'Cannot submit an empty packet.');
        }

        $packet->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'updated_by' => auth()->id(),
        ]);

        Audit::log('external_packet.submitted', 'External packet submitted', [
            'actor_user_id' => auth()->id(),
            'meta' => [
                'project_id' => $project->id,
                'external_packet_id' => $packet->id,
                'reference_no' => $packet->reference_no,
            ],
        ]);

        return back()->with('success', 'External packet marked as submitted.');
    }

    public function archive(Project $project, ExternalPacket $packet)
    {
        $this->ensurePacketBelongsToProject($project, $packet);

        if ($packet->status !== 'prepared') {
            return back()->with('error', 'Only prepared packets can be archived.');
        }

        $packet->update([
            'updated_by' => auth()->id(),
        ]);

        $packet->delete();

        Audit::log('external_packet.archived', 'External packet archived', [
            'actor_user_id' => auth()->id(),
            'meta' => [
                'project_id' => $project->id,
                'external_packet_id' => $packet->id,
                'reference_no' => $packet->reference_no,
            ],
        ]);

        return back()->with('success', 'External packet archived.');
    }

    public function print(Project $project, ExternalPacket $packet)
    {
        $this->ensurePacketBelongsToProject($project, $packet);

        $packet->load([
            'project',
            'items.document.formType',
            'creator',
        ]);

        return view('admin.projects.external-packets.print', [
            'project' => $project,
            'packet' => $packet,
            'receiveUrl' => route('admin.external-packets.receive', [
                'ref' => $packet->reference_no,
            ]),
        ]);
    }

    public function receivePage(Request $request)
    {
        $packet = null;
        $reference = trim((string) $request->query('ref', ''));

        if ($reference !== '') {
            $packet = ExternalPacket::with([
                    'project',
                    'items.document.formType',
                    'creator',
                    'updater',
                ])
                ->where('reference_no', $reference)
                ->first();
        }

        return view('admin.projects.external-packets.receive', [
            'packet' => $packet,
            'reference' => $reference,
        ]);
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'reference_no' => 'required|string|max:255',
        ]);

        return redirect()->route('admin.external-packets.receive', [
            'ref' => trim((string)$request->reference_no),
        ]);
    }

    public function processReceive(Request $request, ExternalPacket $packet)
    {
        if ($packet->status !== 'submitted') {
            return back()->with('error', 'Only submitted packets can be processed from receiving.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|in:approved,returned',
            'remarks' => 'nullable|string',
        ]);

        $packet->load('items');

        if ($packet->items->isEmpty()) {
            return back()->with('error', 'This packet has no items to process.');
        }

        $submittedStatuses = collect($request->input('items', []));

        $packetItemIds = $packet->items->pluck('id')->map(fn ($id) => (string) $id)->values();

        foreach ($packetItemIds as $itemId) {
            if (!$submittedStatuses->has($itemId) && !$submittedStatuses->has((int) $itemId)) {
                return back()->with('error', 'All packet items must be marked as approved or returned.');
            }
        }

        DB::transaction(function () use ($request, $packet) {
            $packet->load('items');

            $hasReturned = false;

            foreach ($packet->items as $item) {
                $newStatus = $request->input("items.{$item->id}");

                if (!in_array($newStatus, ['approved', 'returned'], true)) {
                    throw new \RuntimeException('All packet items must be marked as approved or returned.');
                }

                $item->update([
                    'status' => $newStatus,
                ]);

                if ($newStatus === 'returned') {
                    $hasReturned = true;
                }
            }

            $packet->update([
                'status' => $hasReturned ? 'returned' : 'approved',
                'remarks' => $request->remarks,
                'approved_at' => $hasReturned ? null : now(),
                'updated_by' => auth()->id(),
            ]);

            Audit::log(
                $hasReturned ? 'external_packet.returned' : 'external_packet.approved',
                $hasReturned ? 'External packet returned from receiving' : 'External packet approved from receiving',
                [
                    'actor_user_id' => auth()->id(),
                    'meta' => [
                        'external_packet_id' => $packet->id,
                        'reference_no' => $packet->reference_no,
                        'project_id' => $packet->project_id,
                    ],
                ]
            );
        });

        return redirect()
            ->route('admin.external-packets.receive', ['ref' => $packet->reference_no])
            ->with('success', 'External packet receiving record saved successfully.');
    }

    protected function ensureProjectAllowedForExternalPacket(Project $project): void
    {
        if (!in_array($project->workflow_status, ['approved_by_sacdev', 'completed'])) {

            abort(403, 'BLOCKED: Project not allowed for external packets. Status: ' . $project->workflow_status);
        }
    }

    protected function ensurePacketBelongsToProject(Project $project, ExternalPacket $packet): void
    {
        if ((int) $packet->project_id !== (int) $project->id) {

            abort(403, 'BLOCKED: Packet does not belong to this project.');
        }
    }

    private function addItem(
        ExternalPacket $packet,
        string $type,
        string $label,
        ?string $notes = null,
        ?int $documentId = null,
        ?string $formTypeCode = null
    ): ExternalPacketItem {
        return ExternalPacketItem::create([
            'external_packet_id' => $packet->id,
            'type' => $type,
            'label' => $label,
            'form_type_code' => $formTypeCode,
            'document_id' => $documentId,
            'notes' => $notes,
            'status' => 'pending',
        ]);
    }
}