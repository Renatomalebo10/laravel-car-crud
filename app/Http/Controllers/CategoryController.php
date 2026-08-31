<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Exibe a página dedicada exclusivamente ao cadastro de categorias.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Grava a nova categoria na base de dados e redireciona de volta para a lista de carros.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:categories,nome',
        ], [
            'nome.required' => 'O nome da categoria é obrigatório.',
            'nome.unique' => 'Esta categoria já se encontra cadastrada.',
        ]);

        Category::create([
            'nome' => $request->nome,
        ]);

        return redirect()->route('cars.index')->with('success', 'Categoria cadastrada com sucesso!');
    }
}