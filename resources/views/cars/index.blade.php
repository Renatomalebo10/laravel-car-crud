<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Carros</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Gestão de Veículos</h1>
            <a href="{{ route('cars.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Novo Carro</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Marca</th>
                    <th class="p-3 border">Modelo</th>
                    <th class="p-3 border">Ano</th>
                    <th class="p-3 border">Placa</th>
                    <th class="p-3 border">Cor</th>
                    <th class="p-3 border">Preço (AOA)</th>
                    <th class="p-3 border text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                    <tr class="border-b">
                        <td class="p-3">{{ $car->id }}</td>
                        <td class="p-3">{{ $car->marca }}</td>
                        <td class="p-3">{{ $car->modelo }}</td>
                        <td class="p-3">{{ $car->ano }}</td>
                        <td class="p-3 font-mono">{{ $car->placa }}</td>
                        <td class="p-3">{{ $car->cor }}</td>
                        <td class="p-3">{{ number_format($car->preco, 2, ',', '.') }}</td>
                        <td class="p-3 text-center flex gap-2 justify-center">
                            <a href="{{ route('cars.edit', $car->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded">Editar</a>
                            <form action="{{ route('cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Deseja excluir?');">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-3 py-1 rounded">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="p-4 text-center text-gray-500">Nenhum carro cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>