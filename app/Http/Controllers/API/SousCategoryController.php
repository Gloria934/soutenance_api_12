<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SousCategory;
use Illuminate\Http\Request;
use Validator;

class SousCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sous_categories = SousCategory::all();
        return response()->json([
            'status' => 200,
            'sous_categories' => $sous_categories
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categorie_id' => 'required',
            'nom' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $sous_category = SousCategory::create([
            'categorie_id' => $request->categorie_id,
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Sous Catégorie crée avec succès',
            'sous_category' => $sous_category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sous_categories = SousCategory::where('categorie_id', $id)->get();
        if ($sous_categories) {
            return response()->json([
                'status' => 200,
                'sous_categories' => $sous_categories
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Sous catégories introuvables'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sous_categorie = SousCategory::find($id);
        if (!$sous_categorie) {
            return response()->json([
                'status' => 404,
                'message' => 'Sous catégorie non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'categorie_id' => 'required',
            'nom' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $sous_categorie->update([
            'categorie_id' => $request->categorie_id,

            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Categorie mis à jour avec succès.',
            'sous_categorie' => $sous_categorie
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sous_categorie = SousCategory::find($id);
        if (!$sous_categorie) {
            return response()->json([
                'status' => 404,
                'message' => 'Sous catégorie non trouvée'
            ], 404);
        }

        $sous_categorie->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Sous catégorie supprimée avec succès.'
        ]);
    }
}
