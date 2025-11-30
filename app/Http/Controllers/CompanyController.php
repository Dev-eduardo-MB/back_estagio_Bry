<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller {

    public function index() {
        return Company::with('employees')->get();
    }

    public function show($id) {
        $company = Company::with('employees')->find($id);

        if (!$company)
            return response()->json(['message' => 'Company not found'], 404);

        return $company;
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'name' => 'required|string',
            'cnpj' => 'required|digits:14|unique:companies,cnpj',
            'address' => 'required|string',
            'employee_ids' => 'array'
        ]);

        
        $companyData = $validated;
        unset($companyData['employee_ids']);

        $company = Company::create($companyData);

        // Sincronizar funcionários na tabela pivot
        if (!empty($validated['employee_ids'])) {
            $company->employees()->sync($validated['employee_ids']);
        }

        return response()->json($company->load('employees'), 201);
    }

    public function update(Request $request, $id) {

        $company = Company::find($id);
        if (!$company)
            return response()->json(['message' => 'Company not found'], 404);

        $validated = $request->validate([
            'name' => 'string',
            'cnpj' => [
                'digits:14',
                Rule::unique('companies')->ignore($company->id)
            ],

            'address' => 'string',
            'employee_ids' => 'array'
        ]);

        // Separar employee_ids
        $companyData = $validated;
        unset($companyData['employee_ids']);

        // Atualizar empresa
        $company->update($companyData);

       if (isset($validated['employee_ids'])) {
    $company->employees()->sync($validated['employee_ids']);
}


        return $company->load('employees');
    }

    public function destroy($id) {

        $company = Company::find($id);
        if (!$company)
            return response()->json(['message' => 'Company not found'], 404);

        $company->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
