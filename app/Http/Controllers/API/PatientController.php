<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Patient;

class PatientController extends Controller
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


    //fonction de recher d'un patient par son code
    public function findByCode($code)
    {
        $patient = Patient::where('patient_code', $code)->with('user')->first();

        if (! $patient) {
            return response()->json(['message' => 'Aucun patient trouvé'], 404);
        }

        return response()->json([
            'patient' => $patient,
            'user' => $patient->user,
        ]);
    }


    public function mesOrdonnances(Request $request)
    {
        $ordonnances = $request->user()->patient?->ordonnances;

        if (! $ordonnances) {
            return response()->json(['message' => 'Aucune ordonnance trouvée.'], 404);
        }

        return response()->json($ordonnances);
    }


    // fonction consulterCodePatient
    public function getCodePatient(Request $request)
    {
        $user = $request->user();

        // Vérifie que l'utilisateur a le rôle 'patient'
        if (!$user->hasRole('patient')) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        // Vérifie que la relation 'patient' existe bien
        if (!$user->patient) {
            return response()->json(['message' => 'Profil patient introuvable.'], 404);
        }

        return response()->json([
            'code_patient' => $user->patient->patient_code
        ]);
    }


}
