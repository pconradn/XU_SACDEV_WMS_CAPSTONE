<?php

namespace App\Http\Controllers;
use App\Support\Audit;

use App\Models\Project;
use App\Models\SubmissionPacket;
use App\Models\SubmissionPacketItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionPacketController extends Controller
{
    private function isEditable(SubmissionPacket $packet): bool
    {
        return $packet->status === 'generated';
    }

    public function index(Project $project)
    {
        $packets = SubmissionPacket::with(['items'])
            ->where('project_id', $project->id)
            ->latest()
            ->get();

        return view('org.packets.index', compact('project', 'packets'));
    }

    public function create(Project $project)
    {
        $packet = SubmissionPacket::create([
            'packet_code' => $this->generatePacketCode(),
            'project_id' => $project->id,
            'generated_by' => Auth::id(),
            'generated_at' => now(),
            'status' => 'generated',
        ]);


        Audit::log(
            'packet.created',
            'Submission packet created',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id
                ]
            ]
        );



        return redirect()
            ->route('org.projects.packets.show', [$project, $packet])
            ->with('success', 'Submission packet created.');
    }

    public function show(Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id, 404);

        $packet->load('items');

        return view('org.packets.show', compact('project', 'packet'));
    }

    public function addItem(Request $request, Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id, 404);

        if (!$this->isEditable($packet)) {
            return back()->with('error', 'Packet cannot be edited.');
        }

        $request->validate([
            'type' => 'required|string',
            'reference_number' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'amount' => 'nullable|numeric',
            'organization_name' => 'nullable|string|max:255',
        ]);

        $item = SubmissionPacketItem::create([
            'packet_id' => $packet->id,
            'type' => $request->type,
            'reference_number' => $request->reference_number,
            'label' => $request->label,
            'amount' => $request->amount,
            'organization_name' => $request->organization_name,
            'review_status' => 'pending',
        ]);

        Audit::log(
            'packet.item.added',
            'Packet item added',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id,
                    'item_id' => $item->id,
                    'type' => $item->type
                ]
            ]
        );

        return back()->with('success', 'Item added.');
    }

    public function removeItem(Project $project, SubmissionPacket $packet, SubmissionPacketItem $item)
    {
        abort_unless($packet->project_id === $project->id, 404);
        abort_unless($item->packet_id === $packet->id, 404);

        if (!$this->isEditable($packet)) {
            return back()->with('error', 'Packet cannot be edited.');
        }

        Audit::log(
            'packet.item.removed',
            'Packet item removed',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id,
                    'item_id' => $item->id
                ]
            ]
        );

        $item->delete();

        return back()->with('success', 'Item removed.');
    }

    public function receive(SubmissionPacket $packet)
    {
        if ($packet->status !== 'generated') {
            return back()->with('error', 'Only generated packets can be received.');
        }

        $packet->update([
            'status' => 'under_review',
            'received_by' => auth()->id(),
            'received_at' => now(),
            'submitted_at' => now(),
        ]);

        Audit::log(
            'packet.received',
            'Submission packet received',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $packet->project->organization_id,
                'school_year_id' => $packet->project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id
                ]
            ]
        );

        return back()->with('success', 'Packet received.');
    }

    protected function notifyPacketReadyForClaiming(SubmissionPacket $packet)
    {
        $project = $packet->project;

        $assignment = \App\Models\ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        if (!$assignment || !$assignment->user) {
            return;
        }

        \App\Support\InAppNotifier::notifyOnce($assignment->user, [
            'title' => 'Submission Packet Update',
            'message' => 'Some items are ready for claiming.',
            'route' => route('org.projects.packets.show', [$project, $packet]),
            'dedupe_key' => 'packet_'.$packet->id.'_claim_ready',
            'meta' => [
                'packet_id' => $packet->id,
                'project_id' => $project->id
            ]
        ]);
    }


    public function saveReview(Request $request, SubmissionPacket $packet)
    {
 
        if ($packet->status !== 'under_review') {
            return back()->with('error', 'Packet is not under review.');
        }

        $request->validate([
            'items' => 'nullable|array',
            'mobile_items' => 'nullable|array',
            'remarks' => 'nullable|string'
        ]);

        $submittedItems = $request->input('items', []);
        $mobileItems = $request->input('mobile_items', []);

        if (!empty($mobileItems)) {
            $submittedItems = $mobileItems;
        }

        $anyReady = false;

        foreach ($submittedItems as $itemId => $data) {
            $item = SubmissionPacketItem::where('id', $itemId)
                ->where('packet_id', $packet->id)
                ->first();

            if (!$item) continue;

            $status = $data['review_status'] ?? 'pending';

            if (!in_array($status, ['pending','reviewed','revision_required','ready_for_claiming'])) {
                $status = 'pending';
            }

            if ($status === 'ready_for_claiming') {
                $anyReady = true;
            }

            $item->update([
                'review_status' => $status,
            ]);
        }

        $packet->update([
            'status'      => 'reviewed',
            'reviewed_at' => now(),
            'remarks'     => $request->remarks,
        ]);

        Audit::log(
            'packet.reviewed',
            'Submission packet reviewed',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $packet->project->organization_id,
                'school_year_id' => $packet->project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id
                ]
            ]
        );

        if ($anyReady) {
            $this->notifyPacketReadyForClaiming($packet);
        }

        return back()->with('success', 'Packet reviewed.');
    }

    public function markReady(SubmissionPacket $packet)
    {
        if ($packet->status !== 'reviewed') {
            return back()->with('error', 'Packet must be reviewed first.');
        }

        $packet->update([
            'status' => 'ready_for_claiming',
            'ready_for_claiming_at' => now(),
        ]);

        Audit::log(
            'packet.ready',
            'Packet marked ready for claiming',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $packet->project->organization_id,
                'school_year_id' => $packet->project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id
                ]
            ]
        );

        return back()->with('success', 'Ready for claiming.');
    }

    public function claimItems(Request $request, SubmissionPacket $packet)
    {

        if ($packet->status !== 'ready_for_claiming') {
            return back()->with('error', 'Packet not ready for claiming.');
        }

        $request->validate([
            'items' => 'required|array'
        ]);

        foreach ($request->items as $itemId) {

            $item = SubmissionPacketItem::where('id', $itemId)
                ->where('packet_id', $packet->id)
                ->first();

            if (!$item) continue;

            if ($item->review_status !== 'ready_for_claiming') continue;

            $item->update([
                'claimed_at' => now(),
                'claimed_by' => auth()->id(),
            ]);

            Audit::log(
                'packet.item.claimed',
                'Packet item claimed',
                [
                    'actor_user_id' => auth()->id(),
                    'organization_id' => $packet->project->organization_id,
                    'school_year_id' => $packet->project->school_year_id,
                    'meta' => [
                        'packet_id' => $packet->id,
                        'item_id' => $item->id
                    ]
                ]
            );
        }



        return back()->with('success', 'Items marked as claimed.');
    }

    public function destroy(Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id, 404);

        if ($packet->status !== 'generated') {
            return back()->with('error', 'Only unsubmitted packets can be deleted.');
        }

        Audit::log(
            'packet.deleted',
            'Submission packet deleted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id
                ]
            ]
        );

        $packet->delete();

        return back()->with('success', 'Packet deleted.');
    }

    public function print(Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id, 404);

        $packet->load('items');

        return view('org.packets.print', compact('project', 'packet'));
    }

    protected function generatePacketCode()
    {
        $year = now()->year;

        $last = SubmissionPacket::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $number = 1;

        if ($last && preg_match('/PKT-\d{4}-(\d+)/', $last->packet_code, $matches)) {
            $number = intval($matches[1]) + 1;
        }

        return sprintf('PKT-%s-%04d', $year, $number);
    }

    public function revertToUnderReview(SubmissionPacket $packet)
    {

        if (!in_array($packet->status, ['reviewed','ready_for_claiming'])) {
            return back()->with('error', 'Packet cannot be reverted.');
        }

        $packet->update([
            'status'      => 'under_review',
            'reviewed_at' => null,
            'ready_for_claiming_at' => null,
        ]);

        $packet->items()->update([
            'review_status' => 'pending',
        ]);

        Audit::log(
            'packet.reverted',
            'Packet reverted to under review',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $packet->project->organization_id,
                'school_year_id' => $packet->project->school_year_id,
                'meta' => [
                    'packet_id' => $packet->id
                ]
            ]
        );

        return back()->with('success', 'Packet reverted to under review.');
    }


    public function projectPackets(Project $project)
    {
        $packets = SubmissionPacket::where('project_id', $project->id)
            ->whereNotNull('received_at')
            ->with('items')
            ->latest()
            ->get();

        return view('admin.packets.project_index', compact('project', 'packets'));
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'packet_code' => 'required|string'
        ]);

        $packet = SubmissionPacket::where('packet_code', $request->packet_code)
            ->with(['project', 'items'])
            ->first();

        if (!$packet) {
            return back()->with('error', 'Packet not found.');
        }

        return view('admin.packets.receive', compact('packet'));
    }

    public function receivePage(Request $request)
    {
        $packet = null;

        if ($request->packet_code) {
            $packet = SubmissionPacket::where('packet_code', $request->packet_code)
                ->with(['project', 'items'])
                ->first();
        }

        return view('admin.packets.receive', compact('packet'));
    }




}