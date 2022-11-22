<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ComponentMake;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentMakeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ComponentMake::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'component_make' => $this->faker->text(255),
        ];
    }
}
