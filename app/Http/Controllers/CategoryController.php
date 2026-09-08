<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

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
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.create')->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Atualiza o nome de uma categoria existente.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.create')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Elimina uma categoria da base de dados.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.create')->with('success', 'Categoria eliminada com sucesso!');
    }
}