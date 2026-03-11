<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SubmissionPacket;
use Illuminate\Http\Request;

class AdminPacketController extends Controller
{

    public function receivePage(Request $request)
    {
        $packet = null;

        if ($request->packet_code) {

            $packet = SubmissionPacket::where('packet_code', $request->packet_code)
                ->with(['project','receipts','dvs','letters'])
                ->first();
        }

        return view('admin.packets.receive', compact('packet'));
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'packet_code' => 'required|string'
        ]);

        $packet = SubmissionPacket::where('packet_code', $request->packet_code)
            ->with(['project','receipts','dvs','letters'])
            ->first();

        if(!$packet){
            return back()->with('error','Packet not found.');
        }

        return view('admin.packets.receive', [
            'packet' => $packet
        ]);
    }


    public function markReceived(SubmissionPacket $packet)
    {

        if($packet->received_at){
            return back()->with('error','Packet already received.');
        }

        $packet->update([
            'received_at' => now(),
            'status' => 'received_by_sacdev',
            'received_by' => auth()->id(),
            'return_remarks' => null,
            'returned_at' => null,
            'returned_by' => null
            
        ]);

        return back()->with('success','Packet marked as received.');
    }

    public function projectPackets(Project $project)
    {
        $packets = SubmissionPacket::where('project_id', $project->id)
            ->whereNotNull('received_at')
            ->with(['receipts','dvs','letters'])
            ->latest()
            ->get();

        return view('admin.packets.project_index', compact('project','packets'));
    }

    public function approve(SubmissionPacket $packet)
    {
        $packet->update([
            'status' => 'verified_by_sacdev',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);

        return back()->with('success','Packet approved.');
    }

    public function verify(SubmissionPacket $packet)
    {
        if ($packet->status !== 'received_by_sacdev') {
            return back()->with('error','Packet must be received first.');
        }

        $packet->update([
            'status' => 'verified_by_sacdev',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        return back()->with('success','Packet verified.');
    }

    public function return(Request $request, SubmissionPacket $packet)
    {
        $request->validate([
            'remarks' => 'required|string'
        ]);

        $packet->update([

            'status' => 'submitted_by_project_head',

            'return_remarks' => $request->remarks,

            'returned_at' => now(),

            'returned_by' => auth()->id(),

            'verified_at' => null,
            'verified_by' => null,

            'forwarded_at' => null,
            'received_at' => null,

        ]);

        return back()->with('success','Packet returned to organization.');
    }

    public function revertToReceived(SubmissionPacket $packet)
    {
        if ($packet->status !== 'verified_by_sacdev') {
            return back()->with('error','Only verified packets can be reverted.');
        }

        $packet->update([
            'status' => 'received_by_sacdev',
            'verified_at' => null,
            'verified_by' => null,
            'return_remarks' => null,
            'returned_at' => null,
            'returned_by' => null
        ]);

        return back()->with('success','Verification reverted.');
    }

    public function forwardToFinance(SubmissionPacket $packet)
    {
        if ($packet->status !== 'verified_by_sacdev') {
            return back()->with('error','Packet must be verified first.');
        }

        $packet->update([
            'status' => 'forwarded_to_finance',
            'forwarded_at' => now()
        ]);

        return back()->with('success','Packet forwarded to finance.');
    }

    public function revertFromFinance(SubmissionPacket $packet)
    {
        if ($packet->status !== 'forwarded_to_finance') {
            return back()->with('error','Packet is not forwarded.');
        }

        $packet->update([
            'status' => 'verified_by_sacdev',
            'forwarded_at' => null
        ]);

        return back()->with('success','Finance forwarding reverted.');
    }    

}