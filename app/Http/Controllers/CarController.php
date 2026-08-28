<?php
use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    public function index()
    {
        // 'with' carrega o relacionamento para não causar erro N+1
        $cars = Car::with('category')->latest()->get();
        $categories = Category::all(); 

        return view('cars.index', compact('cars', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id', // Valida se a categoria existe
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:50',
            'ano'         => 'required|integer|min:1900|max:' . date('Y'),
            'placa'       => ['required', 'unique:cars,placa', 'regex:/^[A-Z]{3}-\d{2}-\d{2}-[A-Z]{2}$/'],
            'preco'       => 'required|numeric|gt:0',
        ]);

        Car::create($request->all());

        return redirect()->route('cars.index')->with('success', 'Carro cadastrado com sucesso!');
    }

    public function update(Request $request, string $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'marca'       => 'required|string|max:255',
            'modelo'      => 'required|string|max:255',
            'cor'         => 'required|string|max:50',
            'ano'         => 'required|integer|min:1900|max:' . date('Y'),
            'placa'       => ['required', Rule::unique('cars', 'placa')->ignore($id), 'regex:/^[A-Z]{3}-\d{2}-\d{2}-[A-Z]{2}$/'],
            'preco'       => 'required|numeric|gt:0',
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
}