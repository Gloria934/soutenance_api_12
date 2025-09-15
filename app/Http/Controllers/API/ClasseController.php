<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;
use Validator;

class ClasseController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        $classes = Classe::all();
        return response()->json([
            'status' => 200,
            'classes' => $classes
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

        $classe = Classe::create([
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Classe créé avec succès .',
            'classe' => $classe
        ], 201);
    }

    // Affiche une catégorie spécifique
    public function show($id)
    {
        $classe = Classe::find($id);
        if ($classe) {
            return response()->json([
                'status' => 200,
                'classe' => $classe
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Classe non trouvé'
            ], 404);
        }
    }

    // Met à jour une catégorie
    public function update(Request $request, $id)
    {
        $classe = Classe::find($id);
        if (!$classe) {
            return response()->json([
                'status' => 404,
                'message' => 'Classe non trouvée.'
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

        $classe->update([
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Dci mis à jour avec succès',
            'classe' => $classe
        ]);
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $classe = Classe::find($id);
        if (!$classe) {
            return response()->json([
                'status' => 404,
                'message' => 'Classe non trouvé'
            ], 404);
        }

        $classe->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Classe supprimée avec succès.'
        ]);
    }
}
