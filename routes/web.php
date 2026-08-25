<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::get('/', function () {
    return redirect()->route('cars.index');
});

Route::resource('cars', CarController::class);