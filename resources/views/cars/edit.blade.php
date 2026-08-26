<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Carro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Editar Carro #{{ $car->id }}</h1>
        <form action="{{ route('cars.update', $car->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div><label class="block">Marca</label><input type="text" name="marca" value="{{ $car->marca }}" required class="w-full border p-2 rounded"></div>
            <div><label class="block">Modelo</label><input type="text" name="modelo" value="{{ $car->modelo }}" required class="w-full border p-2 rounded"></div>
            <div><label class="block">Ano</label><input type="number" name="ano" value="{{ $car->ano }}" required class="w-full border p-2 rounded"></div>
            <div><label class="block">Placa</label><input type="text" name="placa" value="{{ $car->placa }}" required class="w-full border p-2 rounded"></div>
            <div><label class="block">Cor</label><input type="text" name="cor" value="{{ $car->cor }}" required class="w-full border p-2 rounded"></div>
            <div><label class="block">Preço (AOA)</label><input type="number" step="0.01" name="preco" value="{{ $car->preco }}" required class="w-full border p-2 rounded"></div>
            <div class="flex justify-between">
                <a href="{{ route('cars.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Voltar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Atualizar</button>
            </div>
        </form>
    </div>
</body>
</html>