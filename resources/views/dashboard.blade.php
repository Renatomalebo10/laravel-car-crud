@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 text-sm">Resumo do inventário de veículos</p>
        </div>

        <a href="{{ route('cars.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
            Ver Inventário
        </a>
    </div>

    <!-- Cartões de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Veículos</p>
            <p class="text-4xl font-bold text-gray-800 mt-2">{{ number_format($totalCars) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Categorias</p>
            <p class="text-4xl font-bold text-gray-800 mt-2">{{ number_format($totalCategories) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Valor Total</p>
            <p class="text-4xl font-bold text-gray-800 mt-2">{{ number_format($totalValue, 2, ',', '.') }} KZ</p>
        </div>
    </div>

    <!-- Veículos Recentes -->
    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Veículos Recentes</h2>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Categoria</th>
                    <th class="px-6 py-3">Marca/Modelo</th>
                    <th class="px-6 py-3">Placa</th>
                    <th class="px-6 py-3">Preço</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse ($recentCars as $car)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">#{{ $car->id }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $car->category->nome ?? 'Sem Categoria' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $car->marca }} {{ $car->modelo }}</td>
                        <td class="px-6 py-4 font-mono text-xs uppercase">{{ $car->placa }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($car->preco, 2, ',', '.') }} KZ</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Nenhum veículo cadastrado ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection