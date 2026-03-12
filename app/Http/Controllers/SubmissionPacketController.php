<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SubmissionPacket;
use App\Models\SubmissionPacketDv;
use App\Models\SubmissionPacketLetter;
use App\Models\SubmissionPacketReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionPacketController extends Controller
{

    public function index(Project $project)
    {
        $packets = SubmissionPacket::where('project_id', $project->id)
            ->with(['receipts','dvs','letters'])
            ->latest()
            ->get();

        return view('org.packets.index', compact('project','packets'));
    }



    public function create(Project $project)
    {
        $packet = SubmissionPacket::create([
            'packet_code' => $this->generatePacketCode(),
            'project_id' => $project->id,
            'generated_by' => Auth::id(),
            'generated_at' => now(),
            'status' => 'generated',
            'has_solicitation_letter' => false,
            'has_disbursement_voucher' => false,
            'has_collection_report' => false,
            'has_certificates' => false,
            'has_receipts' => false,
        ]);

        return redirect()
            ->route('org.projects.packet.show', [$project,$packet])
            ->with('success','Submission packet created.');
    }



    public function show(Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id,404);

        $packet->load([
            'receipts',
            'dvs',
            'letters'
        ]);

        return view('org.packets.show', compact('project','packet'));
    }



    public function update(Request $request, Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already received by SACDEV and can no longer be edited.');
        }

        $packet->update([
            'has_solicitation_letter' => $request->boolean('has_solicitation_letter'),
            'has_disbursement_voucher' => $request->boolean('has_disbursement_voucher'),
            'has_collection_report' => $request->boolean('has_collection_report'),
            'has_certificates' => $request->boolean('has_certificates'),
            'has_receipts' => $request->boolean('has_receipts'),
            'other_items' => $request->other_items
        ]);

        return back()->with('success','Packet updated.');
    }



    public function destroy(Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already received and cannot be archived.');
        }

        $packet->delete();

        return back()->with('success','Packet archived.');
    }



    public function addDv(Request $request, Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already locked.');
        }

        $request->validate([
            'dv_reference' => 'nullable|string|max:255',
            'dv_label' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric'
        ]);

        SubmissionPacketDv::create([
            'packet_id' => $packet->id,
            'dv_reference' => $request->dv_reference,
            'dv_label' => $request->dv_label,
            'amount' => $request->amount
        ]);

        return back()->with('success','Disbursement voucher added.');
    }



    public function removeDv(Project $project, SubmissionPacket $packet, SubmissionPacketDv $dv)
    {
        abort_unless($packet->project_id === $project->id,404);
        abort_unless($dv->packet_id === $packet->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already locked.');
        }

        $dv->delete();

        return back()->with('success','Disbursement voucher removed.');
    }



    public function addReceipt(Request $request, Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already locked.');
        }

        $request->validate([
            'or_number' => 'required|string|max:255'
        ]);

        SubmissionPacketReceipt::create([
            'packet_id' => $packet->id,
            'or_number' => $request->or_number
        ]);

        return back()->with('success','Receipt added.');
    }



    public function removeReceipt(Project $project, SubmissionPacket $packet, SubmissionPacketReceipt $receipt)
    {
        abort_unless($packet->project_id === $project->id,404);
        abort_unless($receipt->packet_id === $packet->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already locked.');
        }

        $receipt->delete();

        return back()->with('success','Receipt removed.');
    }



    public function addLetter(Request $request, Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already locked.');
        }

        $request->validate([
            'control_number' => 'required|string|max:255',
            'organization_name' => 'nullable|string|max:255'
        ]);

        SubmissionPacketLetter::create([
            'packet_id' => $packet->id,
            'control_number' => $request->control_number,
            'organization_name' => $request->organization_name
        ]);

        return back()->with('success','Solicitation letter added.');
    }



    public function removeLetter(Project $project, SubmissionPacket $packet, SubmissionPacketLetter $letter)
    {
        abort_unless($packet->project_id === $project->id,404);
        abort_unless($letter->packet_id === $packet->id,404);

        if($packet->received_at){
            return back()->with('error','Packet already locked.');
        }

        $letter->delete();

        return back()->with('success','Letter removed.');
    }



    protected function generatePacketCode()
    {
        $year = now()->year;

        $last = SubmissionPacket::whereYear('created_at',$year)
            ->orderByDesc('id')
            ->first();

        $number = 1;

        if($last && preg_match('/PKT-\d{4}-(\d+)/',$last->packet_code,$matches)){
            $number = intval($matches[1]) + 1;
        }

        return sprintf('PKT-%s-%04d',$year,$number);
    }



    public function print(Project $project, SubmissionPacket $packet)
    {
        abort_unless($packet->project_id === $project->id,404);

        $packet->load([
            'receipts',
            'dvs',
            'letters'
        ]);

        return view('org.packets.print', compact('project','packet'));
    }

    

}