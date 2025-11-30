<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller {

    public function index() {
        return Employee::with('companies')->get();
    }

    public function show($id) {
        $employee = Employee::with('companies')->find($id);

        if (!$employee)
            return response()->json(['message' => 'Employee not found'], 404);

        return $employee;
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'login' => [
                'required',
                'unique:employees,login',
                'regex:/^[A-Za-z0-9_.]+$/',
            ],
            'name' => 'required|string',
            'cpf' => [
                'required',
                'digits:11',                
                'unique:employees,cpf'
            ],
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:6',
            'company_ids' => 'array'
        ]);
        // Caso queeira visualizar a senha em si comentar a hash abaixo   
        // Hash da senha
        $validated['password'] = Hash::make($validated['password']);

        // Remover company_ids
        $employeeData = $validated;
        unset($employeeData['company_ids']);

        $employee = Employee::create($employeeData);

        // Relacionar empresas
        if (!empty($validated['company_ids'])) {
            $employee->companies()->sync($validated['company_ids']);
        }

        return response()->json($employee->load('companies'), 201);
    }

    public function update(Request $request, $id) {

        $employee = Employee::find($id);
        if (!$employee)
            return response()->json(['message' => 'Employee not found'], 404);

       $validated = $request->validate([
        'login' => ['regex:/^[A-Za-z0-9_.]+$/', Rule::unique('employees')->ignore($employee->id)],
        'name' => 'string',
        'cpf' => ['digits:11', Rule::unique('employees')->ignore($employee->id)],
        'email' => ['email', Rule::unique('employees')->ignore($employee->id)],
        'password' => 'string|min:6',
        'company_ids' => 'array'
    ]);


        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $employee->update($validated);

        if (isset($validated['company_ids'])) {
            $employee->companies()->sync($validated['company_ids']);
        }

        return $employee->load('companies');
    }

    public function destroy($id) {
        $employee = Employee::find($id);

        if (!$employee)
            return response()->json(['message' => 'Employee not found'], 404);

        $employee->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
