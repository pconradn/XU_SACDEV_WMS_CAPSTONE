<?php

namespace App\Http\Requests\Org;

use Illuminate\Foundation\Http\FormRequest;

class SubmitStrategicPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
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
        ];
    }
}
