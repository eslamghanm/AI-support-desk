<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $companyAdminRole = Role::where('name', 'company_admin')->first();
        $agentRole = Role::where('name', 'agent')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // 1️⃣ Super Admin 
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // 2️⃣ Company Admins 
        User::factory(2)->create()->each(function ($user) use ($companyAdminRole) {
            $user->assignRole($companyAdminRole);
        });

        // 3️⃣ Agents 
        User::factory(5)->create()->each(function ($user) use ($agentRole) {
            $user->assignRole($agentRole);
        });

        // 4️⃣ Customers 
        User::factory(10)->create()->each(function ($user) use ($customerRole) {
            $user->assignRole($customerRole);
        });
    }
}
