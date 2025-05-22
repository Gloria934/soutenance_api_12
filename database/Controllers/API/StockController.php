<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'permission:gerer_stock']);
    }

    public function index()
    {
        $stocks = Stock::where('secretaire_id', Auth::id())->get();
        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantite_disponible' => 'required|integer|min:0',
        ]);

        $stock = Stock::create([
            'quantite_disponible' => $request->quantite_disponible,
            'secretaire_id' => Auth::id(), // Utilisateur connecté = secrétaire
        ]);

        return response()->json($stock, 201);
    }

    public function show(Stock $stock)
    {
        $this->authorizeAccess($stock);

        return response()->json($stock);
    }

    public function update(Request $request, Stock $stock)
    {
        $this->authorizeAccess($stock);

        $request->validate([
            'quantite_disponible' => 'required|integer|min:0',
        ]);

        $stock->update([
            'quantite_disponible' => $request->quantite_disponible,
        ]);

        return response()->json($stock);
    }

    public function destroy(Stock $stock)
    {
        $this->authorizeAccess($stock);

        $stock->delete();
        return response()->json(['message' => 'Stock supprimé']);
    }

    private function authorizeAccess(Stock $stock)
    {
        if ($stock->secretaire_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
    }
}
