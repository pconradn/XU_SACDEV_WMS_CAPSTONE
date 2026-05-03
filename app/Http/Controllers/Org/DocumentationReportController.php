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
use App\Models\DocumentationReportAttendee;

class DocumentationReportController extends BaseProjectDocumentController
{

    public function create(Request $request, Project $project)
    {
        $document = $this->getDocument($project, 'DOCUMENTATION_REPORT');
        $formCode = 'DOCUMENTATION_REPORT';
        if ($document) {
            $document->load([
                'documentationReport.objectives',
                'documentationReport.indicators',
                'documentationReport.partners',
                'documentationReport.attendees',
                'signatures',
                
            ]);
        }

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
            'project' => $project,
            'document' => $document,
            'report' => $report,
            'proposal' => $proposal,
            'prefill' => $prefill,
            'currentSignature' => $currentSignature,
            'isReadOnly' => $isReadOnly,
            'isProjectHead' => $isProjectHead,
            'formCode' => $formCode,
            ...$roles
        ]);
    }

    private function copyProposalDataIfEmpty(Project $project, ProjectDocument $document): void
    {
        if (DocumentationReportObjective::where('project_document_id', $document->id)->exists()) {
            return;
        }

        $proposalDocument = $this->getDocument($project, 'PROJECT_PROPOSAL')
            ?? $this->getDocument($project, 'project_proposal');

        if (!$proposalDocument) {
            return;
        }

        $proposal = $proposalDocument->proposalData;

        if (!$proposal) {
            return;
        }

        if ($proposal->objectives->count()) {

            DocumentationReportObjective::insert(
                $proposal->objectives->map(fn($obj) => [
                    'project_document_id' => $document->id,
                    'objective' => $obj->objective,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray()
            );
        }

        if ($proposal->indicators->count()) {

            DocumentationReportIndicator::insert(
                $proposal->indicators->map(fn($ind) => [
                    'project_document_id' => $document->id,
                    'indicator' => $ind->indicator,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray()
            );
        }

        if ($proposal->partners->count()) {

            DocumentationReportPartner::insert(
                $proposal->partners->map(fn($p) => [
                    'project_document_id' => $document->id,
                    'name' => $p->name,
                    'type' => $p->type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray()
            );
        }
    }

    public function store(Request $request, Project $project)
    {
        $formType = FormType::query()
            ->where('code', 'DOCUMENTATION_REPORT')
            ->firstOrFail();

        $user = auth()->user();

        $isProjectHead = $this->isProjectHead($project, $user->id);
        $isDraftee = $this->isDraftee($project, $user->id);

        $action = $request->input('action', 'draft');

        if ($action === 'submit' && $isDraftee) {
            return back()->withErrors([
                'action' => 'Only project head can submit this document.'
            ])->withInput();
        }

        $request->merge([
            'proposed_budget' => str_replace(',', '', $request->proposed_budget),
            'actual_budget' => str_replace(',', '', $request->actual_budget),
            'balance' => str_replace(',', '', $request->balance),
        ]);

        try {
            $data = $this->validateRequest($request);
        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->getOrCreateDocument($project, 'DOCUMENTATION_REPORT');

            return back()
                ->with('warning', 'Form has errors. Saved as draft instead.')
                ->withErrors($e->validator)
                ->withInput();
        }

        $data = $this->sanitizeNumericInputs($data);

        [$data, $clean] = $this->normalizeData($data);

        $existingDocument = $this->getDocument($project, 'DOCUMENTATION_REPORT');

        $document = $existingDocument
            ?: $this->getOrCreateDocument($project, 'DOCUMENTATION_REPORT');

        if ($existingDocument && $response = $this->checkConflict($request, $document)) {
            return $response;
        }

        DB::transaction(function () use ($project, $formType, $data, $clean, $request) {

            $document = $this->saveDocument($project, $formType);

            if ($document->isLocked() && !$document->edit_mode) {
                abort(403, 'This documentation report is already approved by the moderator and is locked.');
            }

            $this->copyProposalDataIfEmpty($project, $document);


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


            if (!$document->edit_mode) {
                $this->resetApprovalsAfterEdit($document);
            }
        });

        $action = $request->input('action');

        if ($document && $document->edit_mode) {
            $action = 'submit';
        }

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return back()->with('success', 'Documentation Report saved as draft.');
    }


    private function sanitizeNumericInputs(array $data): array
    {
        $fields = [
            'proposed_budget',
            'actual_budget',
            'balance',
            'expected_participants',
            'actual_participants',
        ];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data[$field] = is_string($data[$field])
                    ? str_replace(',', '', $data[$field])
                    : $data[$field];
            }
        }

        return $data;
    }



    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'DOCUMENTATION_REPORT')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft' && !$document->edit_mode) {
            return back()->with('error', 'This form cannot be submitted.');
        }

        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            return back()->with('error', 'No active school year is currently set.');
        }

        $isRegistered = OrganizationSchoolYear::where('organization_id', $project->organization_id)
            ->where('school_year_id', $activeSy->id)
            ->exists();

        if (!$isRegistered) {

            $document->update([
                'status' => 'draft'
            ]);

            return back()->with('warning', 
                'Organization is not registered for this school year. Saved as draft instead.'
            );
        }

        $this->handleRequestSubmit($project, $document);

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
        $isSubmit = $request->input('action') === 'submit';

        $data = $request->validate([
            'description' => [$isSubmit ? 'required' : 'nullable', 'string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],

            'implementation_start_date' => [$isSubmit ? 'required' : 'nullable', 'date'],
            'implementation_end_date' => [$isSubmit ? 'required' : 'nullable', 'date', 'after_or_equal:implementation_start_date'],

            'implementation_start_time' => [$isSubmit ? 'required' : 'nullable', 'date_format:H:i'],
            'implementation_end_time' => [$isSubmit ? 'required' : 'nullable', 'date_format:H:i'],

            'on_campus_venue' => ['nullable', 'string', 'max:255','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'off_campus_venue' => ['nullable', 'string', 'max:255','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],

            'objectives_met' => [$isSubmit ? 'required' : 'nullable', 'in:yes,no'],
            'contributing_factors' => [$isSubmit ? 'required' : 'nullable', 'string'],

            'expected_participants' => [$isSubmit ? 'required' : 'nullable', 'integer', 'min:0'],
            'actual_participants' => [$isSubmit ? 'required' : 'nullable', 'integer', 'min:0'],

            'implementation_rating' => [$isSubmit ? 'required' : 'nullable', 'integer', 'min:1', 'max:5'],

            'pre_implementation_stage' => [$isSubmit ? 'required' : 'nullable', 'string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'implementation_stage' => [$isSubmit ? 'required' : 'nullable', 'string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'post_implementation_stage' => [$isSubmit ? 'required' : 'nullable', 'string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'recommendations' => ['nullable', 'string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],

            'proposed_budget' => ['nullable', 'numeric', 'min:0'],
            'actual_budget' => ['nullable', 'numeric', 'min:0'],
            'balance' => ['nullable', 'numeric'],

            'photo_document' => [
                function ($attribute, $value, $fail) use ($request) {

                    $isSubmit = $request->input('action') === 'submit';

                    if (!$isSubmit) return;

                    $document = $this->getDocument(
                        $request->route('project'),
                        'DOCUMENTATION_REPORT'
                    );

                    $existing = $document?->documentationReport?->photo_document_path;

                    if (!$value && !$existing) {
                        $fail('The photo document is required.');
                    }
                },
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'objectives' => [$isSubmit ? 'required' : 'nullable', 'array', 'min:1'],
            'objectives.*' => ['nullable', 'string'],

            'success_indicators' => [$isSubmit ? 'required' : 'nullable', 'array', 'min:1'],
            'success_indicators.*' => ['nullable', 'string'],

            'partners' => ['nullable', 'array'],
            'partners.*.name' => ['nullable', 'string', 'max:255'],
            'partners.*.type' => ['nullable', 'string', 'max:255'],

            'attendees' => ['nullable', 'array'],
            'attendees.*.name' => ['nullable', 'string', 'max:255'],
            'attendees.*.affiliation' => ['nullable', 'string', 'max:255'],
            'attendees.*.designation' => ['nullable', 'string', 'max:255'],
        ]);

        if (
            $isSubmit &&
            empty(trim((string) ($data['on_campus_venue'] ?? ''))) &&
            empty(trim((string) ($data['off_campus_venue'] ?? '')))
        ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'on_campus_venue' => 'At least one venue must be provided.',
            ]);
        }

        return $data;
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
                'description' => $data['description'] ?? null,

                'implementation_start_date' => $data['implementation_start_date'] ?? null,
                'implementation_end_date' => $data['implementation_end_date'] ?? null,
                'implementation_start_time' => $data['implementation_start_time'] ?? null,
                'implementation_end_time' => $data['implementation_end_time'] ?? null,

                'on_campus_venue' => $data['on_campus_venue'] ?? null,
                'off_campus_venue' => $data['off_campus_venue'] ?? null,

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

        DocumentationReportAttendee::where('project_document_id', $documentId)->delete();

        if (!empty($clean['attendees'])) {
            DocumentationReportAttendee::insert(
                array_map(fn($a) => [
                    'project_document_id' => $documentId,
                    'name' => trim((string)($a['name'] ?? '')),
                    'affiliation' => !empty($a['affiliation']) ? trim($a['affiliation']) : null,
                    'designation' => !empty($a['designation']) ? trim($a['designation']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['attendees'])
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


        $clean['attendees'] = array_values(array_filter(
            $data['attendees'] ?? [],
            fn($a) => !empty(trim((string)($a['name'] ?? '')))
        ));

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
                'description' => $report->description,

                'implementation_start_date' => $report->implementation_start_date,
                'implementation_end_date' => $report->implementation_end_date,
                'implementation_start_time' => $report->implementation_start_time,
                'implementation_end_time' => $report->implementation_end_time,

                'on_campus_venue' => $report->on_campus_venue,
                'off_campus_venue' => $report->off_campus_venue,

                'objectives' => $report->objectives()->get(),
                'indicators' => $report->indicators()->get(),
                'partners' => $report->partners()->get(),
                'attendees' => $report->attendees()->get(),

                'expected_participants' => $report->expected_participants,
                'proposed_budget' => $report->proposed_budget,
            ];
        }

        $expectedParticipants = null;

        if ($proposal) {
            $expectedParticipants =
                ((int) ($proposal->expected_xu_participants ?? 0)) +
                ((int) ($proposal->expected_non_xu_participants ?? 0));
        }

        return [
            'description' => $proposal?->description,

            'implementation_start_date' => $proposal?->start_date,
            'implementation_end_date' => $proposal?->end_date,
            'implementation_start_time' => $proposal?->start_time,
            'implementation_end_time' => $proposal?->end_time,

            'on_campus_venue' => $proposal?->venue_type === 'on_campus' ? $proposal?->venue_name : null,
            'off_campus_venue' => $proposal?->venue_type === 'off_campus' ? $proposal?->venue_name : null,

            'objectives' => $proposalDocument?->proposalData?->objectives ?? collect(),
            'indicators' => $proposalDocument?->proposalData?->indicators ?? collect(),
            'partners' => $proposalDocument?->proposalData?->partners ?? collect(),
            'attendees' => collect(),

            'expected_participants' => $expectedParticipants,
            'proposed_budget' => $proposal?->total_budget,
        ];
    }



}