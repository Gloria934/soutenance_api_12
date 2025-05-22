<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allergy;

use Illuminate\Http\JsonResponse;


class AllergyController extends Controller
{



    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|integer|exists:users,id',
        ]);

        try {
            $allergies = Allergy::where('patient_id', $request->patient_id)->get();
            // Alternative avec relation Eloquent :
            // $user = User::findOrFail($request->patient_id);
            // $allergies = $user->allergies;

            return response()->json($allergies, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur s\'est produite lors de la récupération des allergies.',
                'message' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'patient_id' => 'required|integer|exists:users,id',
        ]);

        try {
            $allergy = Allergy::create([
                'nom' => $validatedData['nom'],
                'patient_id' => $validatedData['patient_id'],
            ]);

            return response()->json([
                'message' => 'Allergie créée avec succès.',
                'allergy' => $allergy,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur s\'est produite lors de la création de l\'allergie.',
                'message' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, int $id): JsonResponse
    {
        $allergy = Allergy::find($id);

        if (!$allergy) {
            return response()->json(['error' => 'Allergie introuvable.'], 404);
        }

        $validatedData = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'patient_id' => 'sometimes|integer|exists:users,id',
        ]);

        try {
            $allergy->update($validatedData);
            return response()->json([
                'message' => 'Allergie mise à jour avec succès.',
                'allergy' => $allergy->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur s\'est produite lors de la mise à jour de l\'allergie.',
                'message' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy(int $id): JsonResponse
    {
        $allergy = Allergy::find($id);

        if (!$allergy) {
            return response()->json(['error' => 'Allergie introuvable.'], 404);
        }

        try {
            $allergy->delete();
            return response()->json([
                'message' => 'Allergie supprimée avec succès.',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur s\'est produite lors de la suppression de l\'allergie.',
                'message' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }
}


