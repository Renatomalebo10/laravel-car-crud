<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nome' => 'SUV', 'descricao' => 'Utilitário Esportivo'],
            ['nome' => 'Sedan', 'descricao' => 'Veículo de Passeio Três Volumes'],
            ['nome' => 'Hatchback', 'descricao' => 'Compacto de Dois Volumes'],
            ['nome' => 'Pick-up', 'descricao' => 'Veículo com Caçamba'],
        ];

        foreach ($categorias as $cat) {
            Category::firstOrCreate(['nome' => $cat['nome']], $cat);
        }
    }
}