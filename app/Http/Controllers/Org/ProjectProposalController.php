<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\FormType;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Models\ProjectProposalData;
use App\Models\ProjectProposalGuest;
use App\Models\ProjectProposalObjective;
use App\Models\ProjectProposalPartner;
use App\Models\ProjectProposalPlanOfAction;
use App\Models\ProjectProposalRole;
use App\Models\ProjectProposalSuccessIndicator;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Documents\BaseProjectDocumentController;

class ProjectProposalController extends BaseProjectDocumentController
{


    public function create(Request $request, Project $project)
    {
        $document = $this->getDocument($project, 'project_proposal');

        $proposal = $document?->proposalData;

        $user = auth()->user();

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);

        $roles = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        return view('org.projects.documents.project-proposal.create', [
            'project'          => $project,
            'document'         => $document,
            'proposal'         => $proposal,
            'currentSignature' => $currentSignature,
            'isReadOnly'       => $isReadOnly,
            'isProjectHead'    => $isProjectHead,
            ...$roles
        ]);
    }

    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft') {
            return back()->with('error', 'This proposal is already submitted.');
        }


        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            return back()->with('error', 'No active school year is currently set.');
        }

        $isRegistered = OrganizationSchoolYear::where('organization_id', $project->organization_id)
            ->where('school_year_id', $activeSy->id)
            ->exists();

        if (!$isRegistered) {
            return back()->with('error', 'This organization is not registered for the active school year. Project proposals cannot be submitted.');
        }

        DB::transaction(function () use ($project, $document) {

            $document->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'remarks' => null,
                'returned_by' => null,
                'returned_at' => null,
            ]);

            $document->signatures()->delete();

    
            $projectHead = ProjectAssignment::where('project_id', $project->id)
                ->where('assignment_role', 'project_head')
                ->whereNull('archived_at')
                ->firstOrFail();

            ProjectDocumentSignature::create([
                'project_document_id' => $document->id,
                'user_id' => $projectHead->user_id,
                'role' => 'project_head',
                'status' => 'signed',
                'signed_at' => now(),
            ]);

    

            $treasurer = OrgMembership::where('organization_id', $project->organization_id)
                ->where('school_year_id', $project->school_year_id)
                ->where('role', 'treasurer')
                ->whereNull('archived_at')
                ->firstOrFail();

            ProjectDocumentSignature::create([
                'project_document_id' => $document->id,
                'user_id' => $treasurer->user_id,
                'role' => 'treasurer',
                'status' => 'pending',
            ]);



            $president = OrgMembership::where('organization_id', $project->organization_id)
                ->where('school_year_id', $project->school_year_id)
                ->where('role', 'president')
                ->whereNull('archived_at')
                ->firstOrFail();

            ProjectDocumentSignature::create([
                'project_document_id' => $document->id,
                'user_id' => $president->user_id,
                'role' => 'president',
                'status' => 'pending',
            ]);

    
            $moderator = OrgMembership::where('organization_id', $project->organization_id)
                ->where('school_year_id', $project->school_year_id)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->firstOrFail();

            ProjectDocumentSignature::create([
                'project_document_id' => $document->id,
                'user_id' => $moderator->user_id,
                'role' => 'moderator',
                'status' => 'pending',
            ]);


            $admin = User::where('system_role', 'sacdev_admin')->firstOrFail();

            ProjectDocumentSignature::create([
                'project_document_id' => $document->id,
                'user_id' => $admin->id,
                'role' => 'sacdev_admin',
                'status' => 'pending',
            ]);
        });

        return back()->with('success', 'Project Proposal submitted successfully.');
    }

    public function store(Request $request, Project $project)
    {
        $formType = FormType::query()
            ->where('code', 'project_proposal')
            ->firstOrFail();


        $data = $this->validateRequest($request);

        [$data, $clean] = $this->normalizeData($data);

        DB::transaction(function () use ($project, $formType, $data, $clean) {

            $document = $this->saveDocument($project, $formType);

            if ($document->isLocked()) {
                abort(403, 'This proposal is already approved by the moderator and is locked.');
            }

            $proposal = $this->saveMainProposal($document->id, $data);

            $this->saveFundSources($proposal, $data['fund_sources'] ?? []);

            $this->saveMultiEntries($document->id, $clean, $data);

            $this->resetApprovalsAfterEdit($document);
        });

        $this->ensureOffCampusDocument($project);

        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Project Proposal saved as draft.');
    }

    protected function ensureOffCampusDocument(Project $project): void
    {
        $proposal = $project->proposalDocument?->proposalData;

        if (!$proposal) {
            return;
        }

        $formType = FormType::where('code', 'OFF_CAMPUS_APPLICATION')->first();

        if (!$formType) {
            return;
        }

        $document = ProjectDocument::firstOrCreate(
            [
                'project_id' => $project->id,
                'form_type_id' => $formType->id
            ],
            [
                'status' => 'draft'
            ]
        );

        if ($proposal->venue_type === 'off_campus') {
            $document->update(['is_active' => true]);
        } else {
            $document->update(['is_active' => false]);
        }
    }




    private function resetApprovalsAfterEdit(ProjectDocument $document): void
    {
       
        if ($document->status === 'draft') {
            return;
        }

       
        $document->signatures()
            ->whereIn('role', [
                'treasurer',
                'president',
                'moderator',
                'sacdev_admin',
            ])
            ->delete();
    }

    private function validateRequest(Request $request): array
    {
        $startTime = $request->start_time . ':00';
        return $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
           

            'start_time' => [
                'nullable',
                Rule::anyOf([
                    'date_format:H:i',
                    'date_format:H:i:s',
                ]),
            ],
            'venue_type' => ['required', 'in:on_campus,off_campus'],
            'venue_name' => ['required', 'string', 'max:255'],

            'engagement_type' => ['required', 'in:organizer,partner,participant'],
            'main_organizer' => ['nullable', 'string', 'max:255'],

            'project_nature' => ['nullable', 'array'],
            'project_nature.*' => ['string', 'max:100'],

            'sdg' => ['nullable', 'array'],
            'sdg.*' => ['string', 'max:255'],

            'area_focus' => ['nullable', 'array'],
            'area_focus.*' => ['string', 'max:100'],

            'description' => ['required', 'string'],
            'org_link' => ['required', 'string'],
            'org_cluster' => ['nullable', 'string', 'max:255'],

            'total_budget' => ['nullable', 'numeric', 'min:0'],
            'fund_sources' => ['nullable', 'array'],
            'fund_sources.*' => ['nullable', 'numeric', 'min:0'],

            'audience_type' => ['required', 'string'],
            'xu_subtypes' => ['nullable', 'array'],
            'xu_subtypes.*' => ['string'],
            'audience_details' => ['nullable', 'string'],

            'expected_xu_participants' => ['nullable', 'integer'],
            'expected_non_xu_participants' => ['nullable', 'integer'],
            'has_guest_speakers' => ['nullable', 'boolean'],

            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['nullable', 'string'],

            'success_indicators' => ['nullable', 'array'],
            'success_indicators.*' => ['nullable', 'string'],

            'partners' => ['nullable', 'array'],
            'partners.*' => ['nullable', 'string'],
            'roles.*' => ['nullable', 'string'],

            'guests' => ['nullable', 'array'],
            'guests.*.full_name' => ['nullable', 'string'],
            'guests.*.affiliation' => ['nullable', 'string'],
            'guests.*.designation' => ['nullable', 'string'],

            'plan_of_actions' => ['nullable', 'array'],
            'plan_of_actions.*.date' => ['nullable', 'date'],
            'plan_of_actions.*.time' => ['nullable'],
            'plan_of_actions.*.activity' => ['nullable', 'string'],
            'plan_of_actions.*.venue' => ['nullable', 'string'],
            'roles' => ['nullable', 'array'],
        ]);
    }
    
    private function saveDocument(Project $project, $formType)
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

    private function saveMainProposal(int $documentId, array $data)
    {
        return ProjectProposalData::updateOrCreate(
            ['project_document_id' => $documentId],
            [
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'start_time' => $data['start_time'] ?? null,
                'venue_type' => $data['venue_type'],
                'venue_name' => $data['venue_name'],
                'engagement_type' => $data['engagement_type'],
                'main_organizer' => $data['main_organizer'] ?? null,
                'project_nature' => implode(', ', $data['project_nature'] ?? []),
                'sdg' => implode(', ', $data['sdg'] ?? []),
                'area_focus' => implode(', ', $data['area_focus'] ?? []),
                'description' => $data['description'],
                'org_link' => $data['org_link'],

                'xu_subtypes' => isset($data['xu_subtypes'])
                    ? implode(', ', $data['xu_subtypes'])
                    : null,


                'org_cluster' => $data['org_cluster'] ?? null,
                'total_budget' => $data['total_budget'] ?? null,
                'audience_type' => $data['audience_type'],
                'audience_details' => $data['audience_details'] ?? null,
                'expected_xu_participants' => $data['expected_xu_participants'] ?? null,
                'expected_non_xu_participants' => $data['expected_non_xu_participants'] ?? null,
                'has_guest_speakers' => (bool) ($data['has_guest_speakers'] ?? false),
            ]
        );
    }

    private function saveFundSources(ProjectProposalData $proposal, array $fundSources)
    {
        $proposal->fundSources()->delete();

        foreach ($fundSources as $source => $amount) {

            if ($amount === null || $amount === '') {
                continue;
            }

            $proposal->fundSources()->create([
                'source_name' => $source,
                'amount' => $amount,
            ]);
        }
    }

    private function saveMultiEntries(int $documentId, array $clean, array $data): void
    {
  
        ProjectProposalObjective::where('project_document_id', $documentId)->delete();

        if (!empty($clean['objectives'])) {
            ProjectProposalObjective::insert(
                array_map(fn($txt) => [
                    'project_document_id' => $documentId,
                    'objective' => trim($txt),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['objectives'])
            );
        }

        ProjectProposalSuccessIndicator::where('project_document_id', $documentId)->delete();

        if (!empty($clean['indicators'])) {
            ProjectProposalSuccessIndicator::insert(
                array_map(fn($txt) => [
                    'project_document_id' => $documentId,
                    'indicator' => trim($txt),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['indicators'])
            );
        }

        ProjectProposalPartner::where('project_document_id', $documentId)->delete();

        if (!empty($clean['partners'])) {
            ProjectProposalPartner::insert(
                array_map(fn($name) => [
                    'project_document_id' => $documentId,
                    'name' => trim($name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['partners'])
            );
        }

     
        ProjectProposalGuest::where('project_document_id', $documentId)->delete();

        if (!empty($clean['guests'])) {
            ProjectProposalGuest::insert(
                array_map(fn($g) => [
                    'project_document_id' => $documentId,
                    'full_name' => trim((string)($g['full_name'] ?? '')),
                    'affiliation' => !empty($g['affiliation']) ? trim($g['affiliation']) : null,
                    'designation' => !empty($g['designation']) ? trim($g['designation']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['guests'])
            );
        }

   
        ProjectProposalPlanOfAction::where('project_document_id', $documentId)->delete();

        if (!empty($clean['plan'])) {
            ProjectProposalPlanOfAction::insert(
                array_map(fn($row) => [
                    'project_document_id' => $documentId,
                    'date' => !empty($row['date']) ? $row['date'] : $data['start_date'],
                    'time' => !empty($row['time']) ? substr($row['time'], 0, 5) : null,
                    'activity' => trim((string)($row['activity'] ?? '')),
                    'venue' => !empty($row['venue']) ? trim($row['venue']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['plan'])
            );
        }

     
        ProjectProposalRole::where('project_document_id', $documentId)->delete();

        if (!empty($clean['roles'])) {
            ProjectProposalRole::insert(
                array_map(fn($role) => [
                    'project_document_id' => $documentId,
                    'role_name' => trim($role),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['roles'])
            );
        }
    }

    private function normalizeData(array $data): array
    {
        $cleanStrings = function (?array $arr): array {
            $arr = is_array($arr) ? $arr : [];
            $arr = array_map(fn($v) => is_string($v) ? trim($v) : $v, $arr);
            return array_values(array_filter($arr, fn($v) => is_string($v) ? $v !== '' : !empty($v)));
        };

        $clean = [];

        
        $clean['objectives'] = $cleanStrings($data['objectives'] ?? []);
        $clean['indicators'] = $cleanStrings($data['success_indicators'] ?? []);
        $clean['partners'] = $cleanStrings($data['partners'] ?? []);
        $clean['roles'] = $cleanStrings($data['roles'] ?? []);

     
        $clean['guests'] = array_values(array_filter(
            $data['guests'] ?? [],
            function ($g) {
                return isset($g['full_name']) && trim((string)$g['full_name']) !== '';
            }
        ));

  
        $clean['plan'] = array_values(array_filter(
            $data['plan_of_actions'] ?? [],
            function ($row) {
                return isset($row['activity']) && trim((string)$row['activity']) !== '';
            }
        ));

        return [$data, $clean];
    }

    public function show(Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->with([
                'proposalData',
                'signatures',
                'proposalData.objectives',
                'proposalData.indicators',
                'proposalData.partners',
                'proposalData.roles',
                'proposalData.guests',
                'proposalData.planOfActions',
            ])
            ->firstOrFail();

        return view('org.projects.documents.project-proposal.show', [
            'project' => $project,
            'document' => $document,
            'proposal' => $document->proposalData,
        ]);
    }

    public function approve(Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();


        //dd($document);

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document is not currently awaiting approval.');
        }

       
        $userId = auth()->id();

        $userSignature = ProjectDocumentSignature::where('project_document_id', $document->id)
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        if ($userSignature->status === 'signed') {
            return back()->with('error', 'You have already approved this document.');
        }

        $currentPending = ProjectDocumentSignature::where('project_document_id', $document->id)
            ->where('status', 'pending')
            ->orderBy('id')
            ->first();

        if (!$currentPending) {
            return back()->with('error', 'No pending approvals remain.');
        }

        if ($currentPending->user_id !== $userId) {
            return back()->with('error', 'It is not your turn to approve yet.');
        }

        DB::transaction(function () use ($document, $currentPending) {

            $currentPending->update([
                'status' => 'signed',
                'signed_at' => now(),
            ]);

            $remaining = ProjectDocumentSignature::where('project_document_id', $document->id)
                ->where('status', 'pending')
                ->exists();

            if (!$remaining) {
                $document->update([
                    'status' => 'approved',
                ]);
            }
        });

        return back()->with('success', 'Project proposal approved successfully.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document cannot be returned unless it is submitted.');
        }

        $userId = auth()->id();

        $userSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        $currentPending = $document->signatures
            ->where('status', 'pending')
            ->sortBy('id')
            ->first();

        if (!$currentPending) {
            return back()->with('error', 'No pending approvals remain.');
        }

        if ($currentPending->user_id !== $userId) {
            return back()->with('error', 'It is not your turn to return this document yet.');
        }

        DB::transaction(function () use ($document, $request) {

            foreach ($document->signatures as $signature) {
                $signature->update([
                    'status' => 'pending',
                    'signed_at' => null,
                ]);
            }

            $document->update([
                'status' => 'draft',
                'remarks' => $request->remarks,
                'returned_by' => auth()->id(),
                'returned_at' => now(),
            ]);

        });

        return back()->with('success', 'Project proposal returned for revision.');
    }


}