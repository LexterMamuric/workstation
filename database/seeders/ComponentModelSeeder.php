<?php

namespace Database\Seeders;

use App\Models\ComponentModel;
use Illuminate\Database\Seeder;

class ComponentModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ComponentModel::factory()
            ->count(5)
            ->create();
    }
}
