<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->words(1, true) . ' ' . fake()->randomElement(['SUV', 'Sedan', 'Pick-up']),
        ];
    }
}