<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller {
    public function index() {
        return Company::with('employees')->get();
    }

    public function show($id) {
        $company = Company::with('employees')->find($id);
        if(!$company) return response()->json(['message'=>'Company not found'],404);
        return $company;
    }

    public function store(Request $request) {
        $request->validate([
            'name'=>'required|string',
            'cnpj'=>'required|unique:companies,cnpj',
            'address'=>'required|string',
            'employee_ids'=>'array'
        ]);

        $company = Company::create($request->all());

        if($request->has('employee_ids')){
            $company->employees()->sync($request->employee_ids);
        }

        return response()->json($company->load('employees'),201);
    }

    public function update(Request $request, $id) {
        $company = Company::find($id);
        if(!$company) return response()->json(['message'=>'Company not found'],404);

        $request->validate([
            'name'=>'string',
            'cnpj'=>'unique:companies,cnpj,'.$company->id,
            'address'=>'string',
            'employee_ids'=>'array'
        ]);

        $company->update($request->all());

        if($request->has('employee_ids')){
            $company->employees()->sync($request->employee_ids);
        }

        return $company->load('employees');
    }

    public function destroy($id) {
        $company = Company::find($id);
        if(!$company) return response()->json(['message'=>'Company not found'],404);
        $company->delete();
        return response()->json(['message'=>'Deleted successfully']);
    }
}
