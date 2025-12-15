<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
   public function run()
{
    Role::firstOrCreate(['name' => 'super_admin']);
    Role::firstOrCreate(['name' => 'company_admin']);
    Role::firstOrCreate(['name' => 'agent']);
    Role::firstOrCreate(['name' => 'customer']);
}
}
