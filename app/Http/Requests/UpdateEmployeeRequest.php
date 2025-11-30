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
            'login' => ['regex:/^[A-Za-z0-9_.]+$/', Rule::unique('employees')->ignore($employee->id)],
            'name' => 'string',
            'cpf' => ['digits:11', Rule::unique('employees')->ignore($employee->id)],
            'email' => ['email', Rule::unique('employees')->ignore($employee->id)],
            'password' => 'string|min:6',
             
            'company_ids' => 'array',
            'company_ids.*' => 'exists:companies,id',
        ];
    }
}
