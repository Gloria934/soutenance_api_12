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

class PharmaceuticalProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = PharmaceuticalProduit::with(['dci', 'classe', 'categorie', 'sousCategorie', 'forme']);

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('nom_produit', 'like', "%$search%")
                        ->orWhereHas('dci', function ($q) use ($search) {
                            $q->where('nom', 'like', "%$search%");
                        });
                });
            }

            $products = $query->get();
            return response()->json([
                'success' => true,
                'pharmaceutical_products' => $products,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
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
            // Handle image upload
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('public/products', $imageName);
            $relativePath = 'storage/products/' . $imageName;

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
                'pharmaceutical_product' => $product->load(['dci', 'classe', 'category', 'sousCategory', 'forme',]),
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