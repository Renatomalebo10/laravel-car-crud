@extends('layouts.guest')

@section('title', 'Login - Gestão de Carros')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 text-center">
            <h1 class="text-xl font-bold text-gray-800">Gestão de Carros</h1>
            <p class="text-sm text-gray-600 mt-1">Inicie sessão para aceder ao painel</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="p-6 space-y-4">
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
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
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
                       placeholder="••••••••">
            </div>

            <div class="flex items-center">
                <input type="checkbox"
                       name="remember"
                       id="remember"
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="remember" class="ml-2 text-sm text-gray-600">
                    Lembrar-me
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-md shadow-sm transition">
                Entrar
            </button>

            <p class="text-center text-sm text-gray-600">
                Não tem uma conta?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Criar conta
                </a>
            </p>
        </form>
    </div>
@endsection