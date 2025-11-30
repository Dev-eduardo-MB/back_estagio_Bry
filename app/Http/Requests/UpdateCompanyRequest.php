<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => 'sometimes|string|max:255',
            'cnpj'    => ['sometimes', 'string', 'size:14', Rule::unique('companies')->ignore($this->company)],
            'address' => 'sometimes|string|max:255',

            'employee_ids' => 'array',
            'employee_ids.*' => 'exists:employees,id',
        ];
    }
}
