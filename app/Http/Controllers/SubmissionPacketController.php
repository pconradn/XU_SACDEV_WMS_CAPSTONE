<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\LiquidationReportItem;
use App\Models\SubmissionPacket;
use App\Models\SubmissionPacketReceipt;
use App\Models\SubmissionPacketDv;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionPacketController extends Controller
{



    public function generate(Project $project, ProjectDocument $document)
    {
        if ($document->status !== 'approved_by_sacdev') {
            return back()->with('error', 'Liquidation report must be approved before generating packet.');
        }

        DB::transaction(function () use ($project, $document) {

            $packet = SubmissionPacket::create([
                'packet_code' => $this->generatePacketCode(),
                'project_id' => $project->id,
                'project_document_id' => $document->id,
                'has_liquidation_report' => true,
                'has_receipts' => true,
                'generated_by' => Auth::id(),
                'generated_at' => now(),
            ]);

            $receipts = LiquidationReportItem::where(
                'project_document_id',
                $document->id
            )
            ->whereNotNull('or_number')
            ->pluck('or_number');

            foreach ($receipts as $or) {

                SubmissionPacketReceipt::create([
                    'packet_id' => $packet->id,
                    'or_number' => $or
                ]);
            }
        });

        return back()->with('success', 'Submission packet generated.');
    }



    public function show(Project $project, SubmissionPacket $packet)
    {
        $packet->load([
            'receipts',
            'dvs',
            'document'
        ]);

        return view('org.packets.show', compact('project', 'packet'));
    }


    public function update(Request $request, Project $project, SubmissionPacket $packet)
    {
        $packet->update([
            'has_disbursement_voucher' => $request->boolean('has_disbursement_voucher'),
            'has_collection_report' => $request->boolean('has_collection_report'),
            'has_certificates' => $request->boolean('has_certificates'),
        ]);

        return back()->with('success', 'Packet updated.');
    }

/

    public function destroy(Project $project, SubmissionPacket $packet)
    {
        $packet->delete();

        return back()->with('success', 'Packet deleted.');
    }



    public function addDv(Request $request, SubmissionPacket $packet)
    {
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

        return back()->with('success', 'DV added.');
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

}