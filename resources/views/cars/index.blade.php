@extends('layouts.app')

@section('title', 'Inventário de Carros')

@section('content')
    <!-- Cabeçalho -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Inventário de Carros</h1>
            <p class="text-gray-600 text-sm">Visualização e gestão do inventário de veículos</p>
        </div>

        <!-- Busca -->
        <form method="GET" action="{{ route('cars.index') }}" class="flex w-full md:w-auto">
            <input type="text"
                   name="q"
                   value="{{ $search ?? '' }}"
                   placeholder="Pesquisar veículo..."
                   class="border border-gray-300 rounded-l-md shadow-sm p-2.5 text-sm w-full md:w-64 focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white rounded-r-md text-sm font-medium hover:bg-gray-900 transition">
                Buscar
            </button>
        </form>
    </div>

    <div class="flex flex-wrap gap-3 mb-6 -mt-2">
        @if(Auth::user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg shadow transition flex items-center">
                <span>+ Gerir Categorias</span>
            </a>

            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition flex items-center">
                <span>+ Novo Carro</span>
            </button>
        @endif

        @if(!empty($search))
            <a href="{{ route('cars.index') }}" class="text-sm text-gray-600 self-center">
                Resultados para <strong>"{{ $search }}"</strong> — <span class="text-blue-600 hover:underline">limpar</span>
            </a>
        @endif
    </div>

    <!-- Tabela de Carros -->
    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-6 py-3">Foto</th>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Categoria</th>
                    <th class="px-6 py-3">Marca/Modelo</th>
                    <th class="px-6 py-3">Cor</th>
                    <th class="px-6 py-3">Ano</th>
                    <th class="px-6 py-3">Placa</th>
                    <th class="px-6 py-3">Preço</th>
                    @if(Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-right">Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse ($cars as $car)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            @if ($car->imagem)
                                <img src="{{ asset('storage/' . $car->imagem) }}" alt="{{ $car->modelo }}" class="w-14 h-10 object-cover rounded shadow-sm border">
                            @else
                                <div class="w-14 h-10 bg-gray-100 border text-gray-400 flex items-center justify-center text-xs rounded font-medium">Sem foto</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">#{{ $car->id }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $car->category->nome ?? 'Sem Categoria' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $car->marca }} {{ $car->modelo }}</td>
                        <td class="px-6 py-4">{{ $car->cor }}</td>
                        <td class="px-6 py-4">{{ $car->ano }}</td>
                        <td class="px-6 py-4 font-mono text-xs uppercase">{{ $car->placa }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($car->preco, 2, ',', '.') }} KZ</td>

                        <!-- Coluna de Ações visível APENAS para Admin -->
                        @if(Auth::user()->isAdmin())
                            <td class="px-6 py-4 text-right space-x-3">
                                <button type="button"
                                        onclick="openEditModal({{ json_encode($car) }})"
                                        class="text-blue-600 hover:text-blue-900 font-medium">
                                    Editar
                                </button>

                                <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja eliminar este veículo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? '9' : '8' }}" class="px-6 py-8 text-center text-gray-500">
                            Nenhum veículo cadastrado na base de dados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal de Cadastro / Edição (Carregado apenas se for Admin) -->
    @if(Auth::user()->isAdmin())
        <div id="carModal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 {{ $errors->any() ? '' : 'hidden' }}">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">
                        {{ old('_editing_id') ? 'Editar Carro #' . old('_editing_id') : 'Cadastrar Novo Carro' }}
                    </h3>
                    <button onclick="closeModal()" type="button" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                </div>

                <form id="carForm" action="{{ old('_editing_id') ? route('cars.update', old('_editing_id')) : route('cars.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div id="methodSpoofing">
                        @if(old('_editing_id'))
                            @method('PUT')
                        @endif
                    </div>

                    <input type="hidden" name="_editing_id" id="_editing_id" value="{{ old('_editing_id') }}">

                    <div class="p-6 overflow-y-auto space-y-4 flex-1">
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-4">
                                <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="imagem" class="block text-sm font-medium text-gray-700 mb-1">Foto do Veículo</label>
                            @php
                                $editingCar = old('_editing_id') ? $cars->firstWhere('id', old('_editing_id')) : null;
                            @endphp
                            <div id="currentImageContainer" class="{{ ($editingCar && $editingCar->imagem) ? '' : 'hidden' }} mb-2">
                                <p class="text-xs text-gray-500 mb-1">Foto atual:</p>
                                <img id="currentImagePreview" src="{{ ($editingCar && $editingCar->imagem) ? asset('storage/' . $editingCar->imagem) : '' }}" alt="Imagem Atual" class="w-20 h-14 object-cover rounded border">
                            </div>
                            <input type="file" name="imagem" id="imagem" accept="image/*" class="w-full border-gray-300 border rounded-md shadow-sm p-2 text-sm bg-gray-50">
                            <span id="imageHelpText" class="text-xs text-gray-500">
                                {{ old('_editing_id') ? 'Deixe em branco se desejar manter a foto atual.' : 'Formatos aceites: JPG, PNG, WEBP (Máx. 2MB)' }}
                            </span>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                            <select name="category_id" id="category_id" class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm" required>
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                            <input type="text" name="marca" id="marca" value="{{ old('marca') }}" placeholder="Ex: Toyota" class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm" required>
                        </div>

                        <div>
                            <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
                            <input type="text" name="modelo" id="modelo" value="{{ old('modelo') }}" placeholder="Ex: Land Cruiser" class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm" required>
                        </div>

                        <div>
                            <label for="cor" class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
                            <select name="cor" id="cor" class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm" required>
                                <option value="">Selecione uma cor</option>
                                @foreach(['Branco', 'Preto', 'Cinzento', 'Prata', 'Azul', 'Vermelho', 'Verde', 'Amarelo', 'Castanho'] as $cor)
                                    <option value="{{ $cor }}" {{ old('cor') == $cor ? 'selected' : '' }}>{{ $cor }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="ano" class="block text-sm font-medium text-gray-700 mb-1">Ano de Fabrico</label>
                            <input type="number" name="ano" id="ano" value="{{ old('ano') }}" placeholder="Ex: 2024" class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm" required>
                        </div>

                        <div>
                            <label for="placa" class="block text-sm font-medium text-gray-700 mb-1">Placa / Matrícula</label>
                            <input type="text" name="placa" id="placa" value="{{ old('placa') }}" placeholder="Ex: LD-12-34-AB" oninput="this.value = this.value.toUpperCase()" class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm uppercase" required>
                        </div>

                        <div>
                            <label for="preco" class="block text-sm font-medium text-gray-700 mb-1">Preço (KZ)</label>
                            <input type="number" step="0.01" name="preco" id="preco" value="{{ old('preco') }}" placeholder="Ex: 25000000" class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm" required>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 shadow-sm">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@if(Auth::user()->isAdmin())
    @push('scripts')
        <script>
            const carModal = document.getElementById('carModal');
            const carForm = document.getElementById('carForm');
            const modalTitle = document.getElementById('modalTitle');
            const methodSpoofing = document.getElementById('methodSpoofing');
            const currentImageContainer = document.getElementById('currentImageContainer');
            const currentImagePreview = document.getElementById('currentImagePreview');
            const imageHelpText = document.getElementById('imageHelpText');
            const editingIdInput = document.getElementById('_editing_id');

            function openCreateModal() {
                modalTitle.innerText = "Cadastrar Novo Carro";
                carForm.action = "{{ route('cars.store') }}";
                methodSpoofing.innerHTML = "";
                editingIdInput.value = "";
                carForm.reset();
                currentImageContainer.classList.add('hidden');
                imageHelpText.innerText = "Formatos aceites: JPG, PNG, WEBP (Máx. 2MB)";
                carModal.classList.remove('hidden');
            }

            function openEditModal(car) {
                modalTitle.innerText = `Editar Carro #${car.id}`;
                carForm.action = `/cars/${car.id}`;
                methodSpoofing.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
                editingIdInput.value = car.id;

                document.getElementById('category_id').value = car.category_id || '';
                document.getElementById('marca').value = car.marca || '';
                document.getElementById('modelo').value = car.modelo || '';
                document.getElementById('cor').value = car.cor || '';
                document.getElementById('ano').value = car.ano || '';
                document.getElementById('placa').value = car.placa || '';
                document.getElementById('preco').value = car.preco || '';

                if (car.imagem) {
                    currentImagePreview.src = `/storage/${car.imagem}`;
                    currentImageContainer.classList.remove('hidden');
                    imageHelpText.innerText = "Deixe em branco se desejar manter a foto atual.";
                } else {
                    currentImageContainer.classList.add('hidden');
                    imageHelpText.innerText = "Formatos aceites: JPG, PNG, WEBP (Máx. 2MB)";
                }

                carModal.classList.remove('hidden');
            }

            function closeModal() {
                carModal.classList.add('hidden');
            }
        </script>
    @endpush
@endif