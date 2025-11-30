<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|size:14|unique:companies,cnpj',
            'address' => 'required|string|max:255',

            'employee_ids' => 'array',
            'employee_ids.*' => 'exists:employees,id',
        ];
    }
}
