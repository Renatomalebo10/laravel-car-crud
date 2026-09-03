<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Exibe a lista de carros e categorias ordenados por ID decrescente.
     */
 public function index()
{
    $cars = Car::with('category')->orderBy('id', 'asc')->get();
    $categories = Category::orderBy('id', 'asc')->get();

    return view('cars.index', compact('cars', 'categories'));
}

    /**
     * Exibe o formulário de criação de um novo carro.
     */
    public function create()
    {
        $categories = Category::orderBy('id', 'asc')->get();
        return view('cars.create', compact('categories'));
    }

    /**
     * Cadastra um novo carro na base de dados.
     */
    public function store(Request $request)
    {
        // Sanitiza a placa para maiúsculas
        if ($request->has('placa')) {
            $request->merge([
                'placa' => strtoupper(trim($request->placa))
            ]);
        }

        // Converte vírgula para ponto no preço para evitar erro HY000 no MySQL
        if ($request->has('preco')) {
            $request->merge([
                'preco' => str_replace(',', '.', $request->preco)
            ]);
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:255',
            'ano'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'placa'       => [
                'required',
                'string',
                'max:20',
                'unique:cars,placa',
                'regex:/^[A-Z]{2,3}-\d{2}-\d{2}-[A-Z]{1,2}$/i',
            ],
            'preco'       => 'required|numeric|min:0',
            'imagem'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists'   => 'A categoria selecionada é inválida.',
            'placa.required'       => 'A placa do veículo é obrigatória.',
            'placa.unique'         => 'Esta placa já se encontra cadastrada.',
            'placa.regex'          => 'Formato de placa inválido (Ex: LD-12-34-AB).',
            'imagem.image'         => 'O ficheiro enviado deve ser uma imagem.',
            'imagem.max'           => 'A imagem não pode exceder o tamanho de 2MB.',
        ]);

        // Processa o upload da imagem se enviada
        if ($request->hasFile('imagem')) {
            $data['imagem'] = $request->file('imagem')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()->route('cars.index')->with('success', 'Veículo cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um carro existente.
     */
    public function edit($id)
    {
        $car = Car::findOrFail($id);
        $categories = Category::orderBy('id', 'desc')->get();

        return view('cars.edit', compact('car', 'categories'));
    }

    /**
     * Atualiza os dados de um carro na base de dados.
     */
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        if ($request->has('placa')) {
            $request->merge([
                'placa' => strtoupper(trim($request->placa))
            ]);
        }

        if ($request->has('preco')) {
            $request->merge([
                'preco' => str_replace(',', '.', $request->preco)
            ]);
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:255',
            'ano'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'placa'       => [
                'required',
                'string',
                'max:20',
                'unique:cars,placa,' . $id, // Ignora o ID do próprio carro na verificação
                'regex:/^[A-Z]{2,3}-\d{2}-\d{2}-[A-Z]{1,2}$/i',
            ],
            'preco'       => 'required|numeric|min:0',
            'imagem'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'category_id.required' => 'A categoria é obrigatória.',
            'placa.required'       => 'A placa do veículo é obrigatória.',
            'placa.unique'         => 'Esta placa já pertence a outro veículo.',
            'placa.regex'          => 'Formato de placa inválido (Ex: LD-12-34-AB).',
            'imagem.max'           => 'A imagem não pode exceder 2MB.',
        ]);

        // Substituição de imagem mantendo a limpeza no disco
        if ($request->hasFile('imagem')) {
            if ($car->imagem && Storage::disk('public')->exists($car->imagem)) {
                Storage::disk('public')->delete($car->imagem);
            }

            $data['imagem'] = $request->file('imagem')->store('cars', 'public');
        } else {
            // Se NÃO enviou novo ficheiro, remove do array para preservar a foto atual no banco
            unset($data['imagem']);
        }

        $car->update($data);

        return redirect()->back()->with('success', 'Veículo atualizado com sucesso!');
    }

    /**
     * Elimina um carro e o seu ficheiro de imagem.
     */
    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->imagem && Storage::disk('public')->exists($car->imagem)) {
            Storage::disk('public')->delete($car->imagem);
        }

        $car->delete();

        return redirect()->route('cars.index')->with('success', 'Veículo eliminado com sucesso!');
    }
}