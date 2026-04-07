<?php

namespace App\Http\Requests\Org;

use Illuminate\Foundation\Http\FormRequest;

class SubmitStrategicPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'org_acronym' => $this->org_acronym ? strtoupper(trim($this->org_acronym)) : null,
            'org_name' => $this->org_name ? trim($this->org_name) : null,
            'mission' => $this->mission ? trim($this->mission) : null,
            'vision' => $this->vision ? trim($this->vision) : null,
        ]);
    }

    public function rules(): array
    {

        return [
           
            'confirm' => ['required', 'in:yes'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirm.required' => 'Please confirm before submitting.',
            'confirm.in' => 'Invalid confirmation value.',

            'projects.required' => 'At least one project is required.',
            'projects.min' => 'At least one project is required.',

            'projects.*.objectives.required' => 'Each project must have at least one objective.',
            'projects.*.beneficiaries.required' => 'Each project must have at least one beneficiary.',
            'projects.*.deliverables.required' => 'Each project must have at least one deliverable.',

            'fund_sources.required' => 'At least one fund source is required.',
            'fund_sources.min' => 'At least one fund source is required.',
        ];
    }
}