<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Models\Ordonnance;

use Illuminate\Support\Facades\Auth;

class OrdonnanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }




public function download($id)
    {
        $ordonnance = Ordonnance::findOrFail($id);

        // Optionnel : s'assurer que l'utilisateur est bien le patient concerné
        if (Auth::user()->id !== $ordonnance->patient->user_id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        if (! $ordonnance->fichier || !Storage::disk('public')->exists($ordonnance->fichier)) {
            return response()->json(['message' => 'Fichier non trouvé.'], 404);
        }

        return response()->download(storage_path('app/public/' . $ordonnance->fichier));

    }

}
