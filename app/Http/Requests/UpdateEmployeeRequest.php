<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['sometimes', 'string', 'alpha_dash', Rule::unique('employees')->ignore($this->employee)],
            'name'  => 'sometimes|string|max:255',
            'cpf'   => ['sometimes', 'string', 'size:11', Rule::unique('employees')->ignore($this->employee)],
            'email' => ['sometimes', 'email', Rule::unique('employees')->ignore($this->employee)],
            'password' => 'sometimes|string|min:6',

            'company_ids' => 'array',
            'company_ids.*' => 'exists:companies,id',
        ];
    }
}
