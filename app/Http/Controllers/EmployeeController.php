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
        if (!$employee) return response()->json(['message'=>'Employee not found'],404);
        return $employee;
    }

    public function store(Request $request) {
        $request->validate([
            'login'=>'required|alpha_num|unique:employees,login',
            'name'=>'required|string',
            'cpf'=>'required|unique:employees,cpf',
            'email'=>'required|email|unique:employees,email',
            'password'=>'required|string|min:6',
            'company_ids'=>'array'
        ]);

        $employee = Employee::create([
            'login'=>$request->login,
            'name'=>$request->name,
            'cpf'=>$request->cpf,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        if($request->has('company_ids')){
            $employee->companies()->sync($request->company_ids);
        }

        return response()->json($employee->load('companies'),201);
    }

    public function update(Request $request, $id) {
        $employee = Employee::find($id);
        if(!$employee) return response()->json(['message'=>'Employee not found'],404);

        $request->validate([
            'login'=>['alpha_num',Rule::unique('employees')->ignore($employee->id)],
            'name'=>'string',
            'cpf'=>Rule::unique('employees')->ignore($employee->id),
            'email'=>Rule::unique('employees')->ignore($employee->id),
            'password'=>'string|min:6',
            'company_ids'=>'array'
        ]);

        if($request->has('password')){
            $request->merge(['password'=>Hash::make($request->password)]);
        }

        $employee->update($request->all());

        if($request->has('company_ids')){
            $employee->companies()->sync($request->company_ids);
        }

        return $employee->load('companies');
    }

    public function destroy($id) {
        $employee = Employee::find($id);
        if(!$employee) return response()->json(['message'=>'Employee not found'],404);
        $employee->delete();
        return response()->json(['message'=>'Deleted successfully']);
    }
}
