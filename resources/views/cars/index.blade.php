<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Carros</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        
        <!-- Alerta de Sucesso -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Cabeçalho da Página -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestão de Carros</h1>
                <p class="text-gray-600 text-sm">Gerencie o inventário de veículos e categorias</p>
            </div>
            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition flex items-center space-x-2">
                <span>+ Novo Carro</span>
            </button>
        </div>

        <!-- Tabela de Listagem de Carros -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Categoria</th>
                        <th class="px-6 py-3">Marca</th>
                        <th class="px-6 py-3">Modelo</th>
                        <th class="px-6 py-3">Cor</th>
                        <th class="px-6 py-3">Ano</th>
                        <th class="px-6 py-3">Placa</th>
                        <th class="px-6 py-3">Preço</th>
                        <th class="px-6 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse ($cars as $car)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $car->id }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $car->category->nome ?? 'Sem Categoria' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $car->marca }}</td>
                            <td class="px-6 py-4">{{ $car->modelo }}</td>
                            <td class="px-6 py-4">{{ $car->cor }}</td>
                            <td class="px-6 py-4">{{ $car->ano }}</td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $car->placa }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($car->preco, 2, ',', '.') }} KZ</td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <!-- Botão Editar com todos os dados via JSON -->
                                <button type="button" 
                                    onclick="openEditModal({{ json_encode($car) }})" 
                                    class="text-blue-600 hover:text-blue-900 font-medium">
                                    Editar
                                </button>

                                <!-- Botão Eliminar -->
                                <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja eliminar este carro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                Nenhum carro cadastrado no momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Cadastro / Edição -->
    <div id="carModal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 {{ $errors->any() ? '' : 'hidden' }}">
        
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[85vh] flex flex-col overflow-hidden">
            
            <!-- Cabeçalho Fixo do Modal -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Cadastrar Novo Carro</h3>
                <button onclick="closeModal()" type="button" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <!-- Formulário -->
            <form id="carForm" action="{{ route('cars.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div id="methodSpoofing"></div> <!-- Input oculto para PUT na edição -->

                <!-- Corpo Scrollável com todos os campos -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-4">
                            <div class="flex">
                                <div class="ml-1">
                                    <h3 class="text-sm font-medium text-red-800">Por favor, corrija os erros abaixo:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Campo: Categoria -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                        <select name="category_id" id="category_id" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo: Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                        <input type="text" name="marca" id="marca" value="{{ old('marca') }}" placeholder="Ex: Toyota" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>

                    <!-- Campo: Modelo -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                        <input type="text" name="modelo" id="modelo" value="{{ old('modelo') }}" placeholder="Ex: Land Cruiser" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>

                    <!-- Campo: Cor -->
                    <div>
                        <label for="cor" class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
                        <select name="cor" id="cor" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                            <option value="">Selecione uma cor</option>
                            @foreach(['Branco', 'Preto', 'Cinzento', 'Prata', 'Azul', 'Vermelho', 'Verde', 'Amarelo', 'Castanho'] as $cor)
                                <option value="{{ $cor }}" {{ old('cor') == $cor ? 'selected' : '' }}>{{ $cor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo: Ano -->
                    <div>
                        <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                        <input type="number" name="ano" id="ano" value="{{ old('ano') }}" placeholder="Ex: 2024" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>

                    <!-- Campo: Placa -->
                    <div>
                        <label for="placa" class="block text-sm font-medium text-gray-700 mb-1">Placa</label>
                        <input type="text" name="placa" id="placa" value="{{ old('placa') }}" placeholder="Ex: LDA-28-62-RP" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>

                    <!-- Campo: Preço -->
                    <div>
                        <label for="preco" class="block text-sm font-medium text-gray-700 mb-1">Preço (KZ)</label>
                        <input type="text" name="preco" id="preco" value="{{ old('preco') }}" placeholder="Ex: 25000000" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>

                </div>

                <!-- Rodapé Fixo com Botões sempre visíveis -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 focus:outline-none">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none shadow-sm">
                        Salvar
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Scripts de Controle do Modal (Novo vs Editar) -->
    <script>
        const carModal = document.getElementById('carModal');
        const carForm = document.getElementById('carForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodSpoofing = document.getElementById('methodSpoofing');

        function openCreateModal() {
            modalTitle.innerText = "Cadastrar Novo Carro";
            carForm.action = "{{ route('cars.store') }}";
            methodSpoofing.innerHTML = ""; // Limpa método PUT
            carForm.reset();
            carModal.classList.remove('hidden');
        }

        function openEditModal(car) {
            modalTitle.innerText = `Editar Carro #${car.id}`;
            carForm.action = `/cars/${car.id}`;
            methodSpoofing.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
            
            // Preenche todos os campos do formulário com os dados do registro
            document.getElementById('category_id').value = car.category_id;
            document.getElementById('marca').value = car.marca;
            document.getElementById('modelo').value = car.modelo;
            document.getElementById('cor').value = car.cor;
            document.getElementById('ano').value = car.ano;
            document.getElementById('placa').value = car.placa;
            document.getElementById('preco').value = car.preco;

            carModal.classList.remove('hidden');
        }

        function closeModal() {
            carModal.classList.add('hidden');
        }
    </script>

</body>
</html>