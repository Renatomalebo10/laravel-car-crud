<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Carros</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 p-8" x-data="{ openCreateModal: {{ $errors->any() && !session('error_car_id') ? 'true' : 'false' }} }">

    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow">
        
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Lista de Carros</h1>
                <p class="text-sm text-gray-500">Gestão do inventário e categorias de veículos</p>
            </div>
            <button @click="openCreateModal = true" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                + Novo Carro
            </button>
        </div>

        <!-- Mensagem de Sucesso Global -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- TABELA DE CARROS -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-200 border-b text-sm font-semibold text-gray-700">
                        <th class="p-3">ID</th>
                        <th class="p-3">Marca</th>
                        <th class="p-3">Modelo</th>
                        <th class="p-3">Categoria</th>
                        <th class="p-3">Cor</th>
                        <th class="p-3">Ano</th>
                        <th class="p-3">Placa</th>
                        <th class="p-3">Preço</th>
                        <th class="p-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($cars as $car)
                        <tr class="hover:bg-gray-50 transition" x-data="{ openEditModal: {{ session('error_car_id') == $car->id ? 'true' : 'false' }} }">
                            <td class="p-3 font-medium">{{ $car->id }}</td>
                            <td class="p-3">{{ $car->marca }}</td>
                            <td class="p-3">{{ $car->modelo }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    {{ $car->category->nome ?? 'Sem Categoria' }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 bg-gray-100 border rounded text-xs text-gray-700">
                                    {{ $car->cor }}
                                </span>
                            </td>
                            <td class="p-3">{{ $car->ano }}</td>
                            <td class="p-3 font-mono text-sm">{{ $car->placa }}</td>
                            <td class="p-3 font-semibold text-gray-800">{{ number_format($car->preco, 2, ',', '.') }} KZ</td>
                            <td class="p-3 flex justify-center gap-2">
                                
                                <!-- Botão Editar -->
                                <button @click="openEditModal = true" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">
                                    Editar
                                </button>

                                <!-- Botão Eliminar -->
                                <form action="{{ route('cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja eliminar o veículo {{ $car->marca }} {{ $car->modelo }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition">
                                        Eliminar
                                    </button>
                                </form>

                                <!-- MODAL DE EDITAR CARRO -->
                                <div x-show="openEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-cloak>
                                    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl" @click.away="openEditModal = false">
                                        <div class="flex justify-between items-center mb-4 border-b pb-2">
                                            <h2 class="text-xl font-bold text-gray-800">Editar Carro #{{ $car->id }}</h2>
                                            <button @click="openEditModal = false" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                        </div>

                                        <!-- Alerta de Erros do Modal de Edição -->
                                        @if ($errors->any() && session('error_car_id') == $car->id)
                                            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                                                <ul class="list-disc pl-5">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form action="{{ route('cars.update', $car->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Categoria</label>
                                                <select name="category_id" class="w-full border p-2 rounded bg-white mt-1 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                                    <option value="">Selecione uma Categoria</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id', $car->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->nome }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Marca</label>
                                                <input type="text" name="marca" value="{{ old('marca', $car->marca) }}" class="w-full border p-2 rounded mt-1" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Modelo</label>
                                                <input type="text" name="modelo" value="{{ old('modelo', $car->modelo) }}" class="w-full border p-2 rounded mt-1" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Cor</label>
                                                <select name="cor" class="w-full border p-2 rounded bg-white mt-1" required>
                                                    <option value="">Selecione uma cor</option>
                                                    @php
                                                        $cores = ['Branco', 'Preto', 'Cinzento', 'Prata', 'Azul', 'Vermelho', 'Verde', 'Amarelo', 'Castanho'];
                                                        $corAtual = old('cor', $car->cor);
                                                    @endphp
                                                    @foreach($cores as $cor)
                                                        <option value="{{ $cor }}" {{ $corAtual == $cor ? 'selected' : '' }}>
                                                            {{ $cor }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Ano</label>
                                                <input type="number" name="ano" value="{{ old('ano', $car->ano) }}" class="w-full border p-2 rounded mt-1" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="block text-sm font-medium text-gray-700">Placa</label>
                                                <input type="text" name="placa" value="{{ old('placa', $car->placa) }}" class="w-full border p-2 rounded mt-1" required>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Preço (KZ)</label>
                                                <input type="text" name="preco" value="{{ old('preco', $car->preco) }}" class="w-full border p-2 rounded mt-1" required>
                                            </div>

                                            <div class="flex justify-end gap-2 border-t pt-3">
                                                <button type="button" @click="openEditModal = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                                                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Atualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- FIM MODAL EDITAR -->

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-gray-500">Nenhum carro cadastrado até ao momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- MODAL DE CRIAR NOVO CARRO -->
    <div x-show="openCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-cloak>
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl" @click.away="openCreateModal = false">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-bold text-gray-800">Cadastrar Novo Carro</h2>
                <button @click="openCreateModal = false" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
            </div>

            <!-- Alerta de Erros do Modal de Criação -->
            @if ($errors->any() && !session('error_car_id'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cars.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Categoria</label>
                    <select name="category_id" class="w-full border p-2 rounded bg-white mt-1" required>
                        <option value="">Selecione uma Categoria</option>
                        @php $catSel = session('error_car_id') ? '' : old('category_id'); @endphp
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $catSel == $category->id ? 'selected' : '' }}>
                                {{ $category->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Marca</label>
                    <input type="text" name="marca" value="{{ session('error_car_id') ? '' : old('marca') }}" class="w-full border p-2 rounded mt-1" placeholder="Ex: Toyota" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Modelo</label>
                    <input type="text" name="modelo" value="{{ session('error_car_id') ? '' : old('modelo') }}" class="w-full border p-2 rounded mt-1" placeholder="Ex: Land Cruiser" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Cor</label>
                    <select name="cor" class="w-full border p-2 rounded bg-white mt-1" required>
                        <option value="">Selecione uma cor</option>
                        @php
                            $cores = ['Branco', 'Preto', 'Cinzento', 'Prata', 'Azul', 'Vermelho', 'Verde', 'Amarelo', 'Castanho'];
                            $corSel = session('error_car_id') ? '' : old('cor');
                        @endphp
                        @foreach($cores as $cor)
                            <option value="{{ $cor }}" {{ $corSel == $cor ? 'selected' : '' }}>
                                {{ $cor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Ano</label>
                    <input type="number" name="ano" value="{{ session('error_car_id') ? '' : old('ano') }}" class="w-full border p-2 rounded mt-1" placeholder="Ex: 2024" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Placa</label>
                    <input type="text" name="placa" value="{{ session('error_car_id') ? '' : old('placa') }}" class="w-full border p-2 rounded mt-1" placeholder="Ex: LDA-28-62-RP" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Preço (KZ)</label>
                    <input type="text" name="preco" value="{{ session('error_car_id') ? '' : old('preco') }}" class="w-full border p-2 rounded mt-1" placeholder="Ex: 25000000" required>
                </div>

                <div class="flex justify-end gap-2 border-t pt-3">
                    <button type="button" @click="openCreateModal = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- FIM MODAL CRIAR -->

</body>
</html>