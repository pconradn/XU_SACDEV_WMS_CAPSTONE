<?php

namespace App\Http\Requests\Org;

use Illuminate\Foundation\Http\FormRequest;

class SaveDraftStrategicPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'logo' => ['nullable', 'image', 'max:2048'], // 2MB default; adjust

            'org_acronym' => ['nullable', 'string', 'max:50'],
            'org_name' => ['nullable', 'string', 'max:255'],
            'mission' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],

            // Projects array (draft can be incomplete)
            'projects' => ['nullable', 'array'],
            'projects.*.category' => ['required_with:projects.*.title', 'in:org_dev,student_services,community_involvement'],
            'projects.*.target_date' => ['nullable', 'date'],
            'projects.*.title' => ['nullable', 'string', 'max:255'],
            'projects.*.implementing_body' => ['nullable', 'string', 'max:255'],
            'projects.*.budget' => ['nullable', 'numeric', 'min:0'],

            'projects.*.objectives' => ['nullable', 'array'],
            'projects.*.objectives.*' => ['nullable', 'string'],
            'projects.*.beneficiaries' => ['nullable', 'array'],
            'projects.*.beneficiaries.*' => ['nullable', 'string'],
            'projects.*.deliverables' => ['nullable', 'array'],
            'projects.*.deliverables.*' => ['nullable', 'string'],
            'projects.*.partners' => ['nullable', 'array'],
            'projects.*.partners.*' => ['nullable', 'string'],

            // Fund sources
            'fund_sources' => ['nullable', 'array'],
            'fund_sources.*.type' => ['required_with:fund_sources.*.amount', 'in:org_funds,aeco,pta,membership_fee,raised_funds,other'],
            'fund_sources.*.label' => ['nullable', 'string', 'max:255'],
            'fund_sources.*.amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
