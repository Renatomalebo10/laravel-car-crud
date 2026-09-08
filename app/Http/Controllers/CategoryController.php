<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Formulário de criação de categorias com tabela de categorias existentes.
     */
    public function create()
    {
        $categories = Category::withCount('cars')->orderBy('id', 'asc')->get();

        return view('categories.create', compact('categories'));
    }

    /**
     * Grava uma nova categoria.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:categories,nome',
        ], [
            'nome.required' => 'O nome da categoria é obrigatório.',
            'nome.unique'   => 'Esta categoria já se encontra cadastrada.',
        ]);

        Category::create([
            'nome' => $request->nome,
        ]);

        return redirect()->route('categories.create')->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Atualiza o nome de uma categoria existente.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255|unique:categories,nome,' . $id,
        ], [
            'nome.required' => 'O nome da categoria é obrigatório.',
            'nome.unique'   => 'Esta categoria já existe.',
        ]);

        $category->update([
            'nome' => $request->nome,
        ]);

        return redirect()->route('categories.create')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Elimina uma categoria da base de dados.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.create')->with('success', 'Categoria eliminada com sucesso!');
    }
}