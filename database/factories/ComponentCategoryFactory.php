<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ComponentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComponentCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ComponentCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'component_category' => $this->faker->text(255),
        ];
    }
}
