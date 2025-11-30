<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'required|string|alpha_dash|unique:employees,login',
            'name'  => 'required|string|max:255',
            'cpf'   => 'required|string|size:11|unique:employees,cpf',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:6',

            'company_ids' => 'array',
            'company_ids.*' => 'exists:companies,id',
        ];
    }
}
