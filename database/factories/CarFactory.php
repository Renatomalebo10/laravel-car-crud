<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'marca'       => fake()->randomElement(['Toyota', 'Honda', 'Hyundai', 'Kia', 'Nissan', 'Mercedes-Benz']),
            'modelo'      => fake()->words(2, true),
            'cor'         => fake()->randomElement(['Branco', 'Preto', 'Cinzento', 'Prata', 'Azul', 'Vermelho', 'Verde']),
            'ano'         => fake()->numberBetween(2000, 2025),
            'placa'       => fake()->regexify('[A-Z]{2}-\d{2}-\d{2}-[A-Z]{2}'),
            'preco'       => fake()->numberBetween(1500000, 40000000),
        ];
    }
}