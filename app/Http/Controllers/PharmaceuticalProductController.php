<?php

namespace App\Http\Controllers;

use App\Models\PharmaceuticalProduct;
use App\Models\Dci;
use App\Models\Classe;
use App\Models\Categorie;
use App\Models\SousCategorie;
use App\Models\Forme;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PharmaceuticalProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            // Fetch all medicaments from the database
            $medicaments = PharmaceuticalProduct::all()->map(function ($medicament) {
                return [
                    'id' => (string) $medicament->id, // Cast to string for consistency
                    'nom_produit' => $medicament->nom_produit,
                    'image_path' => $medicament->image_path ?? '', // Handle null
                    'dosage' => $medicament->dosage,
                    'prix' => (float) $medicament->prix, // Ensure float
                    'stock' => (int) $medicament->stock, // Ensure integer
                    'description' => $medicament->description,
                    'date_expiration' => $medicament->date_expiration,
                    'dci_id' => (string) $medicament->dci_id, // Cast to string
                    'classe_id' => (string) $medicament->classe_id, // Cast to string
                    'categorie_id' => (string) $medicament->categorie_id, // Cast to string
                    'sous_categorie_id' => (string) $medicament->sous_categorie_id, // Cast to string
                    'forme_id' => (string) $medicament->forme_id, // Cast to string
                ];
            });

            // Return JSON response
            return response()->json([
                'medicaments' => $medicaments
            ], 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'message' => 'Erreur lors de la récupération des médicaments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'nom_produit' => 'required|string|max:255',
    //         'dosage' => 'required|string|max:50',
    //         'prix' => 'required|numeric|min:0',
    //         'stock' => 'required|integer|min:0',
    //         'description' => 'nullable|string',
    //         'date_expiration' => 'nullable|date|after:today',
    //         'dci_id' => 'required|exists:dcis,id',
    //         'classe_id' => 'required|exists:classes,id',
    //         'categorie_id' => 'required|exists:categories,id',
    //         'sous_categorie_id' => 'required|exists:sous_categories,id',
    //         'forme_id' => 'required|exists:formes,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     try {
    //         // Handle image upload
    //         $image = $request->file('image');
    //         $imageName = time() . '_' . $image->getClientOriginalName();
    //         $imagePath = $image->storeAs('public/products', $imageName);
    //         $relativePath = 'storage/products/' . $imageName;

    //         $product = PharmaceuticalProduct::create([
    //             'image_path' => $relativePath,
    //             'nom_produit' => $request->nom_produit,
    //             'dosage' => $request->dosage,
    //             'prix' => $request->prix,
    //             'stock' => $request->stock,
    //             'description' => $request->description,
    //             'date_expiration' => $request->date_expiration ? Carbon::parse($request->date_expiration) : null,
    //             'dci_id' => $request->dci_id,
    //             'classe_id' => $request->classe_id,
    //             'categorie_id' => $request->categorie_id,
    //             'sous_categorie_id' => $request->sous_categorie_id,
    //             'forme_id' => $request->forme_id,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Produit créé avec succès',
    //             'pharmaceutical_product' => $product->load(['dci', 'classe', 'category', 'sousCategory', 'forme',]),
    //         ], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la création du produit: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nom_produit' => 'required|string|max:255',
            'dosage' => 'required|string|max:50',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'date_expiration' => 'nullable|date|after:today',
            'dci_id' => 'required|exists:dcis,id',
            'classe_id' => 'required|exists:classes,id',
            'categorie_id' => 'required|exists:categories,id',
            'sous_categorie_id' => 'required|exists:sous_categories,id',
            'forme_id' => 'required|exists:formes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Vérifiez si le fichier est reçu
            if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun fichier image valide reçu.',
                ], 422);
            }

            // Handle image upload
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Enregistrer directement dans le dossier public/storage/products
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $relativePath = 'storage/' . $imagePath;

            // Vérifiez si le fichier a été enregistré
            if (!file_exists(public_path($relativePath))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : l\'image n\'a pas été enregistrée dans ' . $relativePath,
                ], 500);
            }

            $product = PharmaceuticalProduct::create([
                'image_path' => $relativePath,
                'nom_produit' => $request->nom_produit,
                'dosage' => $request->dosage,
                'prix' => $request->prix,
                'stock' => $request->stock,
                'description' => $request->description,
                'date_expiration' => $request->date_expiration ? Carbon::parse($request->date_expiration) : null,
                'dci_id' => $request->dci_id,
                'classe_id' => $request->classe_id,
                'categorie_id' => $request->categorie_id,
                'sous_categorie_id' => $request->sous_categorie_id,
                'forme_id' => $request->forme_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès',
                'pharmaceutical_product' => $product->load(['dci', 'classe', 'category', 'sousCategory', 'forme']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du produit: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = PharmaceuticalProduct::with(['dci', 'classe', 'categorie', 'sousCategorie', 'forme', 'stock'])
                ->findOrFail($id);
            return response()->json([
                'success' => true,
                'pharmaceutical_product' => $product,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du produit: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom_produit' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dosage' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'date_expiration' => 'nullable|date|after:today',
            'dci_id' => 'required|exists:dcis,id',
            'classe_id' => 'required|exists:classes,id',
            'categorie_id' => 'required|exists:categories,id',
            'sous_categorie_id' => 'required|exists:sous_categories,id',
            'forme_id' => 'required|exists:formes,id',
        ]);

        $product = PharmaceuticalProduct::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Produit mis à jour avec succès',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy($id)
    {
        try {
            $product = PharmaceuticalProduct::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du produit: ' . $e->getMessage(),
            ], 500);
        }
    }
}