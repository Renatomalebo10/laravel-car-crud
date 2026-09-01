<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Exibe a lista de carros e categorias para os modais.
     */
    public function index()
    {
        $cars = Car::with('category')->latest()->get();
        $categories = Category::orderBy('nome', 'asc')->get();

        return view('cars.index', compact('cars', 'categories'));
    }

    /**
     * Regista um novo carro no banco de dados e faz o upload da imagem.
     */
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
            'imagem'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Máximo 2MB
        ], [
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists'   => 'A categoria selecionada é inválida.',
            'placa.unique'         => 'Esta placa já se encontra cadastrada.',
            'imagem.image'         => 'O ficheiro enviado deve ser uma imagem.',
            'imagem.mimes'         => 'Formatação inválida. Use apenas JPG, PNG ou WEBP.',
            'imagem.max'           => 'A imagem não pode exceder o tamanho de 2MB.',
        ]);

        $data = $request->all();

        // Processa e armazena a imagem na pasta storage/app/public/cars
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()->route('cars.index')->with('success', 'Carro cadastrado com sucesso!');
    }

    /**
     * Atualiza os dados do carro e substitui a imagem se enviada uma nova.
     */
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
            'imagem'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'category_id.required' => 'A categoria é obrigatória.',
            'placa.unique'         => 'Esta placa já pertence a outro veículo.',
            'imagem.max'           => 'A imagem não pode exceder 2MB.',
        ]);

        $data = $request->all();

        // Se uma nova imagem for enviada, remove a anterior e guarda a nova
        if ($request->hasFile('imagem')) {
            if ($car->imagem && Storage::disk('public')->exists($car->imagem)) {
                Storage::disk('public')->delete($car->imagem);
            }
            $data['imagem'] = $request->file('imagem')->store('cars', 'public');
        }

        $car->update($data);

        return redirect()->route('cars.index')->with('success', 'Carro atualizado com sucesso!');
    }

    /**
     * Elimina o carro da base de dados e o ficheiro de imagem associado.
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        // Remove o ficheiro de imagem da pasta de armazenamento
        if ($car->imagem && Storage::disk('public')->exists($car->imagem)) {
            Storage::disk('public')->delete($car->imagem);
        }

        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Carro eliminado com sucesso!');
    }
}