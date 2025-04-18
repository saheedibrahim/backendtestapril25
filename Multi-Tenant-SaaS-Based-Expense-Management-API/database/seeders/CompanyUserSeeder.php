<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory()->count(5)->create()->each(function ($company) {
            // Create 3 Admins
            User::factory()->count(3)->create([
                'role' => 'Admin',
                'company_id' => $company->id,
            ]);

            // Create 3 Managers
            User::factory()->count(3)->create([
                'role' => 'Manager',
                'company_id' => $company->id,
            ]);

            // Create 3 Employees
            User::factory()->count(3)->create([
                'role' => 'Employee',
                'company_id' => $company->id,
            ]);
        });
    }
}
