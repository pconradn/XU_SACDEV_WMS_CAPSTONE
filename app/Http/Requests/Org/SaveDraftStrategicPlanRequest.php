<?php

namespace App\Http\Requests\Org;

use Illuminate\Foundation\Http\FormRequest;

class SaveDraftStrategicPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $cleanProjects = collect($this->input('projects', []))->map(function ($proj) {
            if (isset($proj['budget'])) {
                $proj['budget'] = str_replace(',', '', $proj['budget']);
            }
            return $proj;
        })->toArray();

        $cleanFunds = collect($this->input('fund_sources', []))->map(function ($fs) {
            if (isset($fs['amount'])) {
                $fs['amount'] = str_replace(',', '', $fs['amount']);
            }
            return $fs;
        })->toArray();

        $this->merge([
            'org_acronym' => $this->org_acronym ? strtoupper(trim($this->org_acronym)) : null,
            'org_name' => $this->org_name ? trim($this->org_name) : null,
            'mission' => $this->mission ? trim($this->mission) : null,
            'vision' => $this->vision ? trim($this->vision) : null,

            'projects' => $cleanProjects,
            'fund_sources' => $cleanFunds,
        ]);
    }

    public function rules(): array
    {
        return [
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'org_acronym' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[A-Z0-9\s\-]+$/',
                function ($attr, $value, $fail) {
                    if (trim($value) === '') {
                        $fail('This field cannot be empty.');
                    }
                }
            ],
            'org_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
                function ($attr, $value, $fail) {
                    if (trim($value) === '') {
                        $fail('This field cannot be empty.');
                    }
                }
            ],
            'mission' => [
                'nullable',
                'string',
                'max:2000',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
                function ($attr, $value, $fail) {
                    if (trim($value) === '') {
                        $fail('This field cannot be empty.');
                    }
                }
            ],
            'vision' => [
                'nullable',
                'string',
                'max:2000',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
                function ($attr, $value, $fail) {
                    if (trim($value) === '') {
                        $fail('This field cannot be empty.');
                    }
                }
            ],

           
            'projects' => ['nullable', 'array'],
            'projects.*.category' => ['required_with:projects.*.title', 'in:org_dev,student_services,community_involvement'],
            'projects.*.target_date' => ['nullable', 'date'],
            'projects.*.title' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
            ],
            'projects.*.implementing_body' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
            ],
            'projects.*.budget' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99',
            ],

            'projects.*.objectives' => ['nullable', 'array'],
            'projects.*.objectives.*' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
            ],
            'projects.*.beneficiaries' => ['nullable', 'array'],
            'projects.*.beneficiaries.*' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
            ],
            'projects.*.deliverables' => ['nullable', 'array'],
            'projects.*.deliverables.*' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
            ],
            'projects.*.partners' => ['nullable', 'array'],
            'projects.*.partners.*' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
            ],

            
            'fund_sources' => ['nullable', 'array'],
            'fund_sources.*.type' => ['required_with:fund_sources.*.amount', 'in:org_funds,aeco,pta,membership_fee,raised_funds,other'],
            'fund_sources.*.label' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-\.\,\(\)\'\"]+$/u',
            ],
            'fund_sources.*.amount' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99',
            ],


        ];
    }
}
