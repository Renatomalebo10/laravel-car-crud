<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $totalCars = Car::count();
            $totalCategories = Category::count();
            $totalValue = Car::sum('preco');
            $recentCars = Car::with('category')->latest('id')->take(5)->get();

            return view('dashboard', compact('totalCars', 'totalCategories', 'totalValue', 'recentCars'));
        }

        $user = Auth::user();
        $totalPurchased = $user->purchases()->count();
        $totalSpent = $user->purchases()->sum('preco_compra');
        $recentPurchases = $user->purchases()->with('car.category')->latest()->take(5)->get();

        return view('dashboard', compact('totalPurchased', 'totalSpent', 'recentPurchases'));
    }
}
