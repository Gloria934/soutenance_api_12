<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Allergy;
use Illuminate\Http\Request;
use Validator;

class AllergyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allergies = Allergy::all();
        return response()->json([
            'status' => 200,
            'allergies' => $allergies
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'nom' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $allergy = Allergy::create([
            'patient_id' => $request->patient_id,
            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Alergie ajoutée avec succès',
            'allergy' => $allergy
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $allergies = Allergy::where('patient_id', $id)->get();
        if ($allergies) {
            return response()->json([
                'status' => 200,
                'allergies' => $allergies
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Allergies introuvables'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $allergy = Allergy::find($id);
        if (!$allergy) {
            return response()->json([
                'status' => 404,
                'message' => 'Allergie non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'nom' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $allergy->update([
            'patient_id' => $request->patient_id,

            'nom' => $request->nom,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Allergie mis à jour avec succès.',
            'allergie' => $allergy,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $allergy = Allergy::find($id);
        if (!$allergy) {
            return response()->json([
                'status' => 404,
                'message' => 'Allergie non trouvée'
            ], 404);
        }

        $allergy->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Allergie supprimée avec succès.'
        ]);
    }
}
