<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar - Gestão de Carros</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 text-center">
                <h1 class="text-xl font-bold text-gray-800">Gestão de Carros</h1>
                <p class="text-sm text-gray-600 mt-1">Crie a sua conta</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="p-6 space-y-4">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="O seu nome">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           required
                           class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="seu@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Palavra-passe</label>
                    <input type="password"
                           name="password"
                           id="password"
                           required
                           class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Mínimo 8 caracteres">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Palavra-passe</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           required
                           class="w-full border-gray-300 border rounded-md shadow-sm p-2.5 text-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Repita a palavra-passe">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-md shadow-sm transition">
                    Criar Conta
                </button>

                <p class="text-center text-sm text-gray-600">
                    Já tem uma conta?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Iniciar sessão
                    </a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>
