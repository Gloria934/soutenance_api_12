<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dci;
use Illuminate\Http\Request;
use Validator;

class DciController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        $dcis = Dci::all();
        return response()->json([
            'status' => 200,
            'dcis' => $dcis
        ]);
    }

    // Crée une nouvelle catégorie
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $dci = Dci::create([
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Dci créé avec succès .',
            'dci' => $dci
        ], 201);
    }

    // Affiche une catégorie spécifique
    public function show($id)
    {
        $dci = Dci::find($id);
        if ($dci) {
            return response()->json([
                'status' => 200,
                'dci' => $dci
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Dci non trouvé'
            ], 404);
        }
    }

    // Met à jour une catégorie
    public function update(Request $request, $id)
    {
        $dci = Dci::find($id);
        if (!$dci) {
            return response()->json([
                'status' => 404,
                'message' => 'Dci non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $dci->update([
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Dci mis à jour avec succès',
            'dci' => $dci
        ]);
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $dci = Dci::find($id);
        if (!$dci) {
            return response()->json([
                'status' => 404,
                'message' => 'Dci non trouvé'
            ], 404);
        }

        $dci->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Dci supprimé avec succès.'
        ]);
    }
}
