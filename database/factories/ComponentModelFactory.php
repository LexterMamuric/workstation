<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ComponentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ComponentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'component_model' => $this->faker->text(255),
        ];
    }
}
