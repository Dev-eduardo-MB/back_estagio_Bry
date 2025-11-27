<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('companies', CompanyController::class);
