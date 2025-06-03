<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

class CategoryController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 200,
            'categories' => $categories
        ]);
    }

    // Crée une nouvelle catégorie
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $category = Category::create([
            'nom' => $request->nom,
            'description' => $request->description,


        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    // Affiche une catégorie spécifique
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
            ], 404);
        }
    }

    // Met à jour une catégorie
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
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

        $category->update([
            'nom' => $request->nom,
            'description' => $request->description,



        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found'
            ], 404);
        }

        $category->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Category deleted successfully'
        ]);
    }
}