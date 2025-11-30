<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Company;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        Employee::factory(2)->create()->each(function ($employee) use ($companies) {
            $employee->companies()->attach(
                $companies->random(rand(1, 3))->pluck('id')
            );
        });
    }
}
