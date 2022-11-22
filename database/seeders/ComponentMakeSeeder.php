<?php

namespace Database\Seeders;

use App\Models\ComponentMake;
use Illuminate\Database\Seeder;

class ComponentMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ComponentMake::factory()
            ->count(5)
            ->create();
    }
}
