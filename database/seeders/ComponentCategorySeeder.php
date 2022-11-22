<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComponentCategory;

class ComponentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ComponentCategory::factory()
            ->count(5)
            ->create();
    }
}
