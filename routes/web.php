<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return redirect()->route('cars.index');
});

// Rotas completas para o CRUD de Carros
Route::resource('cars', CarController::class);

// Rotas para gerir Categorias (Ver lista/criar, Salvar, Atualizar, Eliminar)
Route::resource('categories', CategoryController::class)->only(['create', 'store', 'update', 'destroy']);