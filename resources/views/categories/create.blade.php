<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Categorias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen p-4 md:p-8">

    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Mensagem de Sucesso -->
        @if (session('success'))
            <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card de Cadastro -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Cadastrar Categoria</h1>
                <a href="{{ route('cars.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                    ← Voltar aos Carros
                </a>
            </div>

            <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Erros de Validação -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome da Categoria</label>
                    <div class="flex space-x-3">
                        <input type="text" 
                               name="nome" 
                               id="nome" 
                               value="{{ old('nome') }}" 
                               placeholder="Ex: SUV, Sedan, Camioneta" 
                               class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 p-2.5 text-sm" 
                               required 
                               autofocus>
                        <button type="submit" class="px-5 py-2.5 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 shadow-sm transition whitespace-nowrap">
                            Salvar Categoria
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabela de Categorias Existentes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Categorias Cadastradas</h2>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Nome</th>
                        <th class="px-6 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $category->id }}</td>
                            <td class="px-6 py-4 font-medium">{{ $category->nome }}</td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button type="button" 
                                        onclick="openEditCategoryModal({{ json_encode($category) }})" 
                                        class="text-blue-600 hover:text-blue-900 font-medium">
                                    Editar
                                </button>

                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja eliminar esta categoria?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                                Nenhuma categoria cadastrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Modal de Edição de Categoria -->
    <div id="editCategoryModal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Editar Categoria</h3>
                <button onclick="closeEditModal()" type="button" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <form id="editCategoryForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_nome" class="block text-sm font-medium text-gray-700 mb-1">Nome da Categoria</label>
                    <input type="text" name="nome" id="edit_nome" class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm" required>
                </div>

                <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 shadow-sm">
                        Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditCategoryModal(category) {
            const form = document.getElementById('editCategoryForm');
            const input = document.getElementById('edit_nome');
            
            form.action = `/categories/${category.id}`;
            input.value = category.nome;
            
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
        }
    </script>

</body>
</html>