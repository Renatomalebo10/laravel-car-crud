<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function store(Request $request, Car $car)
    {
        $existing = Purchase::where('user_id', Auth::id())
            ->where('car_id', $car->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Voce ja comprou este carro.');
        }

        Purchase::create([
            'user_id' => Auth::id(),
            'car_id' => $car->id,
            'preco_compra' => $car->preco,
        ]);

        return redirect()->back()->with('success', 'Carro comprado com sucesso!');
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->user_id !== Auth::id()) {
            abort(403);
        }

        $purchase->delete();

        return redirect()->back()->with('success', 'Compra cancelada com sucesso!');
    }
}
