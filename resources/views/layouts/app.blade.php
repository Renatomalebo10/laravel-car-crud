<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestão de Carros')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-white border-b border-gray-200 px-6 py-3 shadow-sm">
        <div class="container mx-auto flex justify-between items-center max-w-7xl">
            <div class="flex items-center space-x-2">
                @auth
                    @if(Auth::user()->isAdmin())
                        <span class="text-xs bg-purple-100 text-purple-800 font-semibold px-2.5 py-1 rounded-full">Administrador</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-800 font-semibold px-2.5 py-1 rounded-full">Utilizador</span>
                    @endif
                @endauth
                <a href="{{ route('cars.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Painel de Veículos</a>
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <span class="text-sm text-gray-600">Olá, <strong>{{ Auth::user()->name }}</strong></span>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded transition">
                            Encerrar Sessão
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>