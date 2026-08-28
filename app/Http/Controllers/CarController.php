<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::with('category')->latest()->get();
        $categories = Category::all();

        return view('cars.index', compact('cars', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:50',
            'ano'         => 'required|integer|min:1900|max:' . date('Y'),
            'placa'       => ['required', 'unique:cars,placa', 'regex:/^[A-Z]{3}-\d{2}-\d{2}-[A-Z]{2}$/'],
            'preco'       => 'required|numeric|gt:0',
        ], [
            'category_id.required' => 'Selecione uma categoria.',
            'category_id.exists'   => 'A categoria selecionada é inválida.',
            'cor.required'         => 'Selecione a cor do carro.',
            'ano.min'              => 'O ano deve ser no mínimo 1900.',
            'ano.max'              => 'O ano não pode ser maior que o ano atual.',
            'placa.regex'          => 'A placa deve seguir o formato de Angola (Ex: LDA-28-62-RP).',
            'placa.unique'         => 'Esta placa já está cadastrada.',
            'preco.gt'             => 'O preço deve ser maior que zero.',
        ]);

        Car::create($request->all());

        return redirect()->route('cars.index')->with('success', 'Carro cadastrado com sucesso!');
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:50',
            'ano'         => 'required|integer|min:1900|max:' . date('Y'),
            'placa'       => ['required', Rule::unique('cars', 'placa')->ignore($id), 'regex:/^[A-Z]{3}-\d{2}-\d{2}-[A-Z]{2}$/'],
            'preco'       => 'required|numeric|gt:0',
        ], [
            'category_id.required' => 'Selecione uma categoria.',
            'category_id.exists'   => 'A categoria selecionada é inválida.',
            'cor.required'         => 'Selecione a cor do carro.',
            'ano.min'              => 'O ano deve ser no mínimo 1900.',
            'ano.max'              => 'O ano não pode ser maior que o ano atual.',
            'placa.regex'          => 'A placa deve seguir o formato de Angola (Ex: LDA-28-62-RP).',
            'placa.unique'         => 'Esta placa já está cadastrada em outro carro.',
            'preco.gt'             => 'O preço deve ser maior que zero.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_car_id', $id);
        }

        $car = Car::findOrFail($id);
        $car->update($request->all());

        return redirect()->route('cars.index')->with('success', 'Carro atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Carro eliminado com sucesso!');
    }
}