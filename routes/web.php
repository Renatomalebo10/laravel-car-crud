<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::post('/cars/{car}/buy', [PurchaseController::class, 'store'])->name('cars.buy');
    Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

    // Rotas apenas para admin
    Route::middleware(['admin'])->group(function () {
        Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
        Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
        Route::resource('categories', CategoryController::class);
    });
});
