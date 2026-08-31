<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;

// Redireciona a rota raiz para a lista de carros
Route::get('/', function () {
    return redirect()->route('cars.index');
});

// Rotas completas para o CRUD de Carros
Route::resource('cars', CarController::class);

// Rotas dedicadas apenas para criar e salvar Categorias
Route::resource('categories', CategoryController::class)->only(['create', 'store']);