@extends('layouts.guest')

@section('title', 'Gestão de Carros')

@section('content')
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <div class="px-6 py-5 bg-gray-50 border-b border-gray-200 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Gestão de Carros</h1>
            <p class="text-sm text-gray-600 mt-1">Sistema simples para gerir o seu inventário de veículos</p>
        </div>

        <div class="p-6 space-y-4">
            <p class="text-sm text-gray-700 leading-relaxed">
                Organize os veículos por categoria, registe fotos, preços e placas,
                pesquise rapidamente e acompanhe as estatísticas do seu inventário.
            </p>

            <ul class="text-sm text-gray-700 space-y-2">
                <li class="flex items-start space-x-2">
                    <span class="text-blue-600 font-bold">•</span>
                    <span>Cadastro e edição de veículos com upload de imagem</span>
                </li>
                <li class="flex items-start space-x-2">
                    <span class="text-green-600 font-bold">•</span>
                    <span>Gestão de categorias com contagem de veículos</span>
                </li>
                <li class="flex items-start space-x-2">
                    <span class="text-purple-600 font-bold">•</span>
                    <span>Pesquisa e paginação no inventário</span>
                </li>
                <li class="flex items-start space-x-2">
                    <span class="text-gray-600 font-bold">•</span>
                    <span>Dashboard com estatísticas do parque automóvel</span>
                </li>
            </ul>

            <div class="pt-4 flex flex-col space-y-3">
                <a href="{{ route('login') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-md shadow-sm transition">
                    Iniciar sessão
                </a>
                <a href="{{ route('register') }}" class="block text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-md transition">
                    Criar conta
                </a>
            </div>
        </div>
    </div>
@endsection