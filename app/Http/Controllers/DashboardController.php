<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Exibe o painel com estatísticas do inventário.
     */
    public function index()
    {
        $totalCars = Car::count();
        $totalCategories = Category::count();
        $totalValue = Car::sum('preco');
        $recentCars = Car::with('category')->latest('id')->take(5)->get();

        return view('dashboard', compact('totalCars', 'totalCategories', 'totalValue', 'recentCars'));
    }
}