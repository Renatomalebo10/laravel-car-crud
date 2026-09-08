<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Exibe a lista de carros e categorias ordenados por ID crescente,
     * com suporte a pesquisa por marca, modelo, cor ou placa.
     */
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));

        $cars = Car::with('category')
            ->when($search, function ($query, $search) {
                $query->where('marca', 'like', "%{$search}%")
                    ->orWhere('modelo', 'like', "%{$search}%")
                    ->orWhere('cor', 'like', "%{$search}%")
                    ->orWhere('placa', 'like', "%{$search}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('id', 'asc')->get();

        return view('cars.index', compact('cars', 'categories', 'search'));
    }

    /**
     * Cadastra um novo carro na base de dados.
     */
    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();

        if ($request->has('placa')) {
            $data['placa'] = strtoupper(trim($data['placa']));
        }

        if ($request->has('preco')) {
            $data['preco'] = str_replace(',', '.', $data['preco']);
        }

        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()->route('cars.index')->with('success', 'Veículo cadastrado com sucesso!');
    }

    /**
     * Atualiza os dados de um carro na base de dados.
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        $data = $request->validated();

        if ($request->has('placa')) {
            $data['placa'] = strtoupper(trim($data['placa']));
        }

        if ($request->has('preco')) {
            $data['preco'] = str_replace(',', '.', $data['preco']);
        }

        if ($request->hasFile('imagem')) {
            if ($car->imagem && Storage::disk('public')->exists($car->imagem)) {
                Storage::disk('public')->delete($car->imagem);
            }

            $data['imagem'] = $request->file('imagem')->store('cars', 'public');
        } else {
            unset($data['imagem']);
        }

        $car->update($data);

        return redirect()->back()->with('success', 'Veículo atualizado com sucesso!');
    }

    /**
     * Elimina um carro e o seu ficheiro de imagem.
     */
    public function destroy(Car $car)
    {
        if ($car->imagem && Storage::disk('public')->exists($car->imagem)) {
            Storage::disk('public')->delete($car->imagem);
        }

        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Veículo eliminado com sucesso!');
    }
}