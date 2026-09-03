<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Car; // <--- ADICIONADO: Importação do Model Car
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Exibe a lista com ordenação por ID decrescente.
     */
    public function index()
    {
        $cars = Car::with('category')->orderBy('id', 'asc')->get();
        $categories = Category::orderBy('id', 'asc')->get();

        return view('cars.index', compact('cars', 'categories'));
    }

    public function create()
    {
        // Se quiseres ordenar as categorias por ID em vez de nome aqui também:
        $categories = Category::orderBy('id', 'asc')->get();
        
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
            'nome.unique' => 'Esta categoria já se encontra cadastrada.',
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
            'nome.unique' => 'Esta categoria já existe.',
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