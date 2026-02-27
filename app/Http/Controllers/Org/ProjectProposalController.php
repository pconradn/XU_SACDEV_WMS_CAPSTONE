<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectProposalController extends Controller
{
    public function create(Request $request, Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->first();

        return view('org.projects.documents.project-proposal.create', [
            'project' => $project,
            'document' => $document,
        ]);
    }


    public function store(Request $request, Project $project)
    {
        $formType = FormType::query()
            ->where('code', 'project_proposal')
            ->firstOrFail();

        // -------------------------
        // Validation
        // -------------------------
        $data = $request->validate([
            // schedule + venue
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'venue_type' => ['required', 'in:on_campus,off_campus'],
            'venue_name' => ['required', 'string', 'max:255'],

            // engagement
            'engagement_type' => ['required', 'in:organizer,partner,participant'],
            'main_organizer' => ['nullable', 'string', 'max:255'],

            // multiple selections (checkbox arrays)
            'project_nature' => ['nullable', 'array'],
            'project_nature.*' => ['string', 'max:100'],
            'project_nature_other' => ['nullable', 'string', 'max:255'],

            'sdg' => ['nullable', 'array'],
            'sdg.*' => ['string', 'max:255'],

            'area_focus' => ['nullable', 'array'],
            'area_focus.*' => ['string', 'max:100'],

            // description + links
            'description' => ['required', 'string'],
            'org_link' => ['required', 'string'],
            'org_cluster' => ['nullable', 'string', 'max:255'],

            // budget + audience (adjust if your blade names differ)
            'total_budget' => ['nullable', 'numeric', 'min:0'],
            'source_of_funds' => ['required', 'string', 'max:255'],
            'counterpart_amount' => ['nullable', 'numeric', 'min:0'],
            'audience_type' => ['required', 'string', 'max:255'],
            'audience_details' => ['nullable', 'string', 'max:255'],
            'expected_xu_participants' => ['nullable', 'integer', 'min:0'],
            'expected_non_xu_participants' => ['nullable', 'integer', 'min:0'],
            'has_guest_speakers' => ['nullable', 'boolean'],

            // multi-entry sections (arrays)
            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['nullable', 'string'],

            'success_indicators' => ['nullable', 'array'],
            'success_indicators.*' => ['nullable', 'string'],

            'partners' => ['nullable', 'array'],
            'partners.*.name' => ['nullable', 'string', 'max:255'],
            'partners.*.type' => ['nullable', 'string', 'max:255'],

            'guests' => ['nullable', 'array'],
            'guests.*.full_name' => ['nullable', 'string', 'max:255'],
            'guests.*.affiliation' => ['nullable', 'string', 'max:255'],
            'guests.*.designation' => ['nullable', 'string', 'max:255'],

            'plan_of_actions' => ['nullable', 'array'],
            'plan_of_actions.*.date' => ['nullable', 'date'],
            'plan_of_actions.*.time' => ['nullable', 'date_format:H:i'],
            'plan_of_actions.*.activity' => ['nullable', 'string', 'max:255'],
            'plan_of_actions.*.venue' => ['nullable', 'string', 'max:255'],

            'roles' => ['nullable', 'array'],
            'roles.*.role_name' => ['nullable', 'string', 'max:255'],
            'roles.*.description' => ['nullable', 'string'],
        ]);

        // -------------------------
        // Small rules
        // -------------------------
        if (($data['engagement_type'] ?? null) !== 'participant') {
            $data['main_organizer'] = null;
        }

        $data['has_guest_speakers'] = (bool) ($data['has_guest_speakers'] ?? false);

        // Clean array inputs: trim + remove empties
        $cleanStrings = function (?array $arr): array {
            $arr = is_array($arr) ? $arr : [];
            $arr = array_map(fn($v) => is_string($v) ? trim($v) : $v, $arr);
            return array_values(array_filter($arr, fn($v) => is_string($v) ? $v !== '' : !empty($v)));
        };

        $objectives = $cleanStrings($data['objectives'] ?? []);
        $indicators = $cleanStrings($data['success_indicators'] ?? []);

        $projectNature = $cleanStrings($data['project_nature'] ?? []);
        $sdg = $cleanStrings($data['sdg'] ?? []);
        $areaFocus = $cleanStrings($data['area_focus'] ?? []);

        $partners = array_values(array_filter($data['partners'] ?? [], function ($p) {
            return isset($p['name']) && trim((string)$p['name']) !== '';
        }));

        $guests = array_values(array_filter($data['guests'] ?? [], function ($g) {
            return isset($g['full_name']) && trim((string)$g['full_name']) !== '';
        }));

        $plan = array_values(array_filter($data['plan_of_actions'] ?? [], function ($row) {
            return isset($row['activity']) && trim((string)$row['activity']) !== '';
        }));

        $roles = array_values(array_filter($data['roles'] ?? [], function ($r) {
            return isset($r['role_name']) && trim((string)$r['role_name']) !== '';
        }));

        DB::transaction(function () use (
            $project,
            $formType,
            $data,
            $projectNature,
            $sdg,
            $areaFocus,
            $objectives,
            $indicators,
            $partners,
            $guests,
            $plan,
            $roles
        ) {
            // Document row
            $document = ProjectDocument::query()->updateOrCreate(
                [
                    'project_id' => $project->id,
                    'form_type_id' => $formType->id,
                ],
                [
                    'created_by_user_id' => auth()->id(),
                    'status' => 'draft',
                ]
            );

            // ---- IMPORTANT ----
            // If your DB columns for project_nature/sdg/area_focus are NOT json yet,
            // this fallback stores as a comma-separated string.
            // Once you migrate them to JSON, you can store arrays directly.
            $storeProjectNature = $projectNature;
            $storeSdg = $sdg;
            $storeAreaFocus = $areaFocus;

            $asStringIfNeeded = function ($value) {
                return is_array($value) ? implode(', ', $value) : $value;
            };

            // Main proposal data
            \App\Models\ProjectProposalData::query()->updateOrCreate(
                ['project_document_id' => $document->id],
                [
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'start_time' => $data['start_time'] ?? null,
                    'end_time' => null, // you removed end_time earlier

                    'venue_type' => $data['venue_type'],
                    'venue_name' => $data['venue_name'],

                    'engagement_type' => $data['engagement_type'],
                    'main_organizer' => $data['main_organizer'] ?? null,

                    // If JSON columns exist: replace $asStringIfNeeded(...) with $storeProjectNature etc
                    'project_nature' => $asStringIfNeeded($storeProjectNature),
                    'project_nature_other' => $data['project_nature_other'] ?? null,

                    'sdg' => $asStringIfNeeded($storeSdg),
                    'area_focus' => $asStringIfNeeded($storeAreaFocus),

                    'description' => $data['description'],
                    'org_link' => $data['org_link'],
                    'org_cluster' => $data['org_cluster'] ?? null,

                    'total_budget' => $data['total_budget'] ?? null,
                    'source_of_funds' => $data['source_of_funds'],
                    'counterpart_amount' => $data['counterpart_amount'] ?? null,

                    'audience_type' => $data['audience_type'],
                    'audience_details' => $data['audience_details'] ?? null,

                    'expected_xu_participants' => $data['expected_xu_participants'] ?? null,
                    'expected_non_xu_participants' => $data['expected_non_xu_participants'] ?? null,

                    'has_guest_speakers' => (bool) ($data['has_guest_speakers'] ?? false),
                ]
            );

            // ---- Refresh multi rows (delete then insert) ----
            \App\Models\ProjectProposalObjective::query()
                ->where('project_document_id', $document->id)
                ->delete();

            if (!empty($objectives)) {
                \App\Models\ProjectProposalObjective::query()->insert(
                    array_map(fn($txt) => [
                        'project_document_id' => $document->id,
                        'objective' => $txt,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $objectives)
                );
            }

            \App\Models\ProjectProposalSuccessIndicator::query()
                ->where('project_document_id', $document->id)
                ->delete();

            if (!empty($indicators)) {
                \App\Models\ProjectProposalSuccessIndicator::query()->insert(
                    array_map(fn($txt) => [
                        'project_document_id' => $document->id,
                        'indicator' => $txt,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $indicators)
                );
            }

            \App\Models\ProjectProposalPartner::query()
                ->where('project_document_id', $document->id)
                ->delete();

            if (!empty($partners)) {
                \App\Models\ProjectProposalPartner::query()->insert(
                    array_map(fn($p) => [
                        'project_document_id' => $document->id,
                        'name' => trim((string)($p['name'] ?? '')),
                        'type' => isset($p['type']) ? trim((string)$p['type']) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $partners)
                );
            }

            \App\Models\ProjectProposalGuest::query()
                ->where('project_document_id', $document->id)
                ->delete();

            if (!empty($guests)) {
                \App\Models\ProjectProposalGuest::query()->insert(
                    array_map(fn($g) => [
                        'project_document_id' => $document->id,
                        'full_name' => trim((string)($g['full_name'] ?? '')),
                        'affiliation' => isset($g['affiliation']) ? trim((string)$g['affiliation']) : null,
                        'designation' => isset($g['designation']) ? trim((string)$g['designation']) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $guests)
                );
            }

            \App\Models\ProjectProposalPlanOfAction::query()
                ->where('project_document_id', $document->id)
                ->delete();

            if (!empty($plan)) {
                \App\Models\ProjectProposalPlanOfAction::query()->insert(
                    array_map(fn($row) => [
                        'project_document_id' => $document->id,
                        'date' => $row['date'] ?? $data['start_date'],
                        'time' => $row['time'] ?? null,
                        'activity' => trim((string)($row['activity'] ?? '')),
                        'venue' => isset($row['venue']) ? trim((string)$row['venue']) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $plan)
                );
            }

            \App\Models\ProjectProposalRole::query()
                ->where('project_document_id', $document->id)
                ->delete();

            if (!empty($roles)) {
                \App\Models\ProjectProposalRole::query()->insert(
                    array_map(fn($r) => [
                        'project_document_id' => $document->id,
                        'role_name' => trim((string)($r['role_name'] ?? '')),
                        'description' => isset($r['description']) ? trim((string)$r['description']) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $roles)
                );
            }
        });

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Project Proposal saved as draft.');
    }
}