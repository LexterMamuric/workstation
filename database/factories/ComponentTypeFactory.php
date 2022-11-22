<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ComponentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ComponentType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'component_type' => $this->faker->text(255),
        ];
    }
}
