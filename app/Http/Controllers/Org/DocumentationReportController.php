<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\DocumentationReportData;
use App\Models\DocumentationReportIndicator;
use App\Models\DocumentationReportObjective;
use App\Models\DocumentationReportPartner;
use App\Models\FormType;
use App\Models\OrganizationSchoolYear;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\SchoolYear;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentationReportController extends BaseProjectDocumentController
{
    public function create(Request $request, Project $project)
    {
        $document = $this->getDocument($project, 'DOCUMENTATION_REPORT');

        $report = $document?->documentationReport;

        $user = auth()->user();

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);

        $roles = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        $proposalDocument = $this->getDocument($project, 'PROJECT_PROPOSAL')
            ?? $this->getDocument($project, 'project_proposal');

        $proposal = $proposalDocument?->proposalData;

        $prefill = $this->buildPrefillData($proposalDocument, $proposal, $report);

        return view('org.projects.documents.documentation-report.create', [
            'project'            => $project,
            'document'           => $document,
            'report'             => $report,
            'proposal'           => $proposal,
            'prefill'            => $prefill,
            'currentSignature'   => $currentSignature,
            'isReadOnly'         => $isReadOnly,
            'isProjectHead'      => $isProjectHead,
            ...$roles
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $formType = FormType::query()
            ->where('code', 'DOCUMENTATION_REPORT')
            ->firstOrFail();

        $data = $this->validateRequest($request);

        [$data, $clean] = $this->normalizeData($data);

        DB::transaction(function () use ($project, $formType, $data, $clean, $request) {

            $document = $this->saveDocument($project, $formType);

            if ($document->isLocked()) {
                abort(403, 'This documentation report is already approved by the moderator and is locked.');
            }

            $existingReport = DocumentationReportData::where('project_document_id', $document->id)->first();

            $photoPath = $existingReport?->photo_document_path;

            if ($request->hasFile('photo_document')) {
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }

                $photoPath = $request->file('photo_document')
                    ->store('documentation-reports/photo-documents', 'public');
            }

            $this->saveMainReport($document->id, $data, $photoPath);

            $this->saveMultiEntries($document->id, $clean);

            $this->resetApprovalsAfterEdit($document);
        });

        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Documentation Report saved as draft.');
    }

    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'DOCUMENTATION_REPORT')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft' && $document->status !== 'returned') {
            return back()->with('error', 'This documentation report is already submitted.');
        }

        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            return back()->with('error', 'No active school year is currently set.');
        }

        $isRegistered = OrganizationSchoolYear::where('organization_id', $project->organization_id)
            ->where('school_year_id', $activeSy->id)
            ->exists();

        if (!$isRegistered) {
            return back()->with('error', 'This organization is not registered for the active school year.');
        }

        DB::transaction(function () use ($document) {

            $document->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'remarks' => null,
                'returned_by' => null,
                'returned_at' => null,
            ]);

            $document->signatures()->delete();

            $this->createWorkflow($document);
        });

        $document->load('signatures', 'formType', 'project');

        $this->notifyNextApprover($document);

        Audit::log(
            'document.submitted',
            'Documentation Report submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'DOCUMENTATION_REPORT',
                ]
            ]
        );

        return back()->with('success', 'Documentation Report submitted successfully.');
    }

    public function approve(Project $project)
    {
        $document = $this->getDocument($project, 'DOCUMENTATION_REPORT');

        if (!$document) {
            return back()->with('error', 'Documentation Report not found.');
        }

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document is not awaiting approval.');
        }

        $this->handleApproval($project, $document);

        return back()->with('success', 'Documentation Report approved successfully.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required', 'string']
        ]);

        $document = $this->getDocument($project, 'DOCUMENTATION_REPORT');

        if (!$document) {
            return back()->with('error', 'Documentation Report not found.');
        }

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success', 'Documentation Report returned for revision.');
    }

    public function show(Project $project)
    {
        $formType = FormType::where('code', 'DOCUMENTATION_REPORT')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->with([
                'documentationReport',
                'signatures',
                'documentationReport.objectives',
                'documentationReport.indicators',
                'documentationReport.partners',
            ])
            ->firstOrFail();

        return view('org.projects.documents.documentation-report.show', [
            'project'  => $project,
            'document' => $document,
            'report'   => $document->documentationReport,
        ]);
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'objectives_met' => ['nullable', 'in:yes,no'],
            'contributing_factors' => ['nullable', 'string'],

            'expected_participants' => ['nullable', 'integer', 'min:0'],
            'actual_participants' => ['nullable', 'integer', 'min:0'],

            'implementation_rating' => ['nullable', 'integer', 'min:1', 'max:5'],

            'pre_implementation_stage' => ['nullable', 'string'],
            'implementation_stage' => ['nullable', 'string'],
            'post_implementation_stage' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],

            'proposed_budget' => ['nullable', 'numeric', 'min:0'],
            'actual_budget' => ['nullable', 'numeric', 'min:0'],
            'balance' => ['nullable', 'numeric'],

            'photo_document' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['nullable', 'string'],

            'success_indicators' => ['nullable', 'array'],
            'success_indicators.*' => ['nullable', 'string'],

            'partners' => ['nullable', 'array'],
            'partners.*.name' => ['nullable', 'string', 'max:255'],
            'partners.*.type' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function saveDocument(Project $project, FormType $formType): ProjectDocument
    {
        return ProjectDocument::updateOrCreate(
            [
                'project_id' => $project->id,
                'form_type_id' => $formType->id,
            ],
            [
                'created_by_user_id' => auth()->id(),
                'status' => 'draft',
            ]
        );
    }

    private function saveMainReport(int $documentId, array $data, ?string $photoPath = null): DocumentationReportData
    {
        return DocumentationReportData::updateOrCreate(
            ['project_document_id' => $documentId],
            [
                'objectives_met' => match ($data['objectives_met'] ?? null) {
                    'yes' => true,
                    'no' => false,
                    default => null,
                },
                'contributing_factors' => $data['contributing_factors'] ?? null,
                'expected_participants' => $data['expected_participants'] ?? null,
                'actual_participants' => $data['actual_participants'] ?? null,
                'implementation_rating' => $data['implementation_rating'] ?? null,
                'pre_implementation_stage' => $data['pre_implementation_stage'] ?? null,
                'implementation_stage' => $data['implementation_stage'] ?? null,
                'post_implementation_stage' => $data['post_implementation_stage'] ?? null,
                'recommendations' => $data['recommendations'] ?? null,
                'proposed_budget' => $data['proposed_budget'] ?? null,
                'actual_budget' => $data['actual_budget'] ?? null,
                'balance' => $data['balance'] ?? null,
                'photo_document_path' => $photoPath,
            ]
        );
    }

    private function saveMultiEntries(int $documentId, array $clean): void
    {
        DocumentationReportObjective::where('project_document_id', $documentId)->delete();

        if (!empty($clean['objectives'])) {
            DocumentationReportObjective::insert(
                array_map(fn($txt) => [
                    'project_document_id' => $documentId,
                    'objective' => trim($txt),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['objectives'])
            );
        }

        DocumentationReportIndicator::where('project_document_id', $documentId)->delete();

        if (!empty($clean['indicators'])) {
            DocumentationReportIndicator::insert(
                array_map(fn($txt) => [
                    'project_document_id' => $documentId,
                    'indicator' => trim($txt),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['indicators'])
            );
        }

        DocumentationReportPartner::where('project_document_id', $documentId)->delete();

        if (!empty($clean['partners'])) {
            DocumentationReportPartner::insert(
                array_map(fn($partner) => [
                    'project_document_id' => $documentId,
                    'name' => trim((string) ($partner['name'] ?? '')),
                    'type' => !empty($partner['type']) ? trim((string) $partner['type']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['partners'])
            );
        }
    }

    private function normalizeData(array $data): array
    {
        $cleanStrings = function (?array $arr): array {
            $arr = is_array($arr) ? $arr : [];
            $arr = array_map(fn($v) => is_string($v) ? trim($v) : $v, $arr);

            return array_values(array_filter($arr, function ($v) {
                if (is_string($v)) {
                    return $v !== '';
                }

                return !empty($v);
            }));
        };

        $clean = [];

        $clean['objectives'] = $cleanStrings($data['objectives'] ?? []);
        $clean['indicators'] = $cleanStrings($data['success_indicators'] ?? []);

        $clean['partners'] = array_values(array_filter(
            $data['partners'] ?? [],
            function ($partner) {
                return !empty(trim((string) ($partner['name'] ?? '')));
            }
        ));

        return [$data, $clean];
    }

    private function resetApprovalsAfterEdit(ProjectDocument $document): void
    {
        if ($document->status === 'draft') {
            return;
        }

        $document->signatures()
            ->whereIn('role', [
                'president',
                'moderator',
                'sacdev_admin',
            ])
            ->delete();

        $document->update([
            'status' => 'draft',
        ]);
    }

    private function buildPrefillData($proposalDocument, $proposal, $report): array
    {
        if ($report) {
            return [
                'objectives' => $report->objectives()->get(),
                'indicators' => $report->indicators()->get(),
                'partners' => $report->partners()->get(),
                'expected_participants' => $report->expected_participants,
                'proposed_budget' => $report->proposed_budget,
            ];
        }

        $proposalObjectives = $proposalDocument?->proposalData?->objectives ?? collect();
        $proposalIndicators = $proposalDocument?->proposalData?->indicators ?? collect();
        $proposalPartners = $proposalDocument?->proposalData?->partners ?? collect();

        $expectedParticipants = null;

        if ($proposal) {
            $expectedParticipants =
                ((int) ($proposal->expected_xu_participants ?? 0)) +
                ((int) ($proposal->expected_non_xu_participants ?? 0));
        }

        return [
            'objectives' => $proposalObjectives,
            'indicators' => $proposalIndicators,
            'partners' => $proposalPartners,
            'expected_participants' => $expectedParticipants,
            'proposed_budget' => $proposal?->total_budget,
        ];
    }
}