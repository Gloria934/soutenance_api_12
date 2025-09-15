<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Forme;
use Illuminate\Http\Request;
use Validator;

class FormeController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        $formes = Forme::all();
        return response()->json([
            'status' => 200,
            'formes' => $formes
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

        $forme = Forme::create([
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Classe créé avec succès .',
            'forme' => $forme
        ], 201);
    }

    // Affiche une catégorie spécifique
    public function show($id)
    {
        $forme = Forme::find($id);
        if ($forme) {
            return response()->json([
                'status' => 200,
                'forme' => $forme
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Forme non trouvé'
            ], 404);
        }
    }

    // Met à jour une catégorie
    public function update(Request $request, $id)
    {
        $forme = Forme::find($id);
        if (!$forme) {
            return response()->json([
                'status' => 404,
                'message' => 'Forme non trouvée.'
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

        $forme->update([
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Forme mis à jour avec succès',
            'forme' => $forme
        ]);
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $forme = Forme::find($id);
        if (!$forme) {
            return response()->json([
                'status' => 404,
                'message' => 'Forme non trouvé'
            ], 404);
        }

        $forme->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Forme supprimée avec succès.'
        ]);
    }
}
