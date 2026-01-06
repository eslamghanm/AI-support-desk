<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $demo = Company::where('slug', 'demo-company')->first();
        $second = Company::where('slug', 'second-co')->first();

        // Super Admin (بدون شركة)
        $super = User::firstOrCreate(
            ['email' => 'super@admin.com'],
            ['name' => 'super_admin', 'password' => Hash::make('password'), 'company_id' => null]
        );
        $super->syncRoles(['super_admin']);

        // Company Admin demo
        $adminDemo = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            ['name' => 'company_admin', 'password' => Hash::make('password'), 'company_id' => $demo?->id]
        );
        $adminDemo->syncRoles(['company_admin']);

        // Agents demo
        for ($i = 1; $i <= 2; $i++) {
            $agent = User::firstOrCreate(
                ['email' => "agent{$i}@demo.com"],
                ['name' => "Demo Agent {$i}", 'password' => Hash::make('password'), 'company_id' => $demo?->id]
            );
            $agent->syncRoles(['agent']);
        }

        // Company Admin second
        $admin2 = User::firstOrCreate(
            ['email' => 'admin@second.com'],
            ['name' => 'company_admin', 'password' => Hash::make('password'), 'company_id' => $second?->id]
        );
        $admin2->syncRoles(['company_admin']);
    }
}
