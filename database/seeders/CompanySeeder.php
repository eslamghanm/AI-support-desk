<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(
            ['slug' => 'demo-company'],
            ['name' => 'Demo Company', 'slug' => 'demo-company', 'is_active' => true]
        );

        Company::firstOrCreate(
            ['slug' => 'second-co'],
            ['name' => 'Second Co', 'slug' => 'second-co', 'is_active' => true]
        );
    }
}
