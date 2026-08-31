<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        // Traz os carros com os dados da categoria e todas as categorias para o <select>
        $cars = Car::with('category')->latest()->get();
        $categories = Category::orderBy('nome', 'asc')->get();

        return view('cars.index', compact('cars', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:255',
            'ano'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'placa'       => 'required|string|max:255|unique:cars,placa',
            'preco'       => 'required|numeric|min:0',
        ]);

        Car::create($request->all());

        return redirect()->route('cars.index')->with('success', 'Carro cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:255',
            'ano'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'placa'       => 'required|string|max:255|unique:cars,placa,' . $id,
            'preco'       => 'required|numeric|min:0',
        ]);

        $car->update($request->all());

        return redirect()->route('cars.index')->with('success', 'Carro atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Carro eliminado com sucesso!');
    }
}