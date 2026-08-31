<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Nova Categoria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden border border-gray-100">
        
        <!-- Cabeçalho do Card -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Cadastrar Categoria</h1>
            <a href="{{ route('cars.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                ← Voltar
            </a>
        </div>

        <!-- Formulário -->
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

            <!-- Campo: Nome da Categoria -->
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome da Categoria</label>
                <input type="text" 
                       name="nome" 
                       id="nome" 
                       value="{{ old('nome') }}" 
                       placeholder="Ex: SUV, Sedan, Camioneta" 
                       class="w-full border-gray-300 border rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 p-2.5 text-sm" 
                       required 
                       autofocus>
            </div>

            <!-- Botões de Ação -->
            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                <a href="{{ route('cars.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 shadow-sm transition">
                    Salvar Categoria
                </button>
            </div>
        </form>

    </div>

</body>
</html>