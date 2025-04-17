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
        $patient = Patient::where('code_patient', $code)->with('user')->first();

        if (! $patient) {
            return response()->json(['message' => 'Aucun patient trouvé'], 404);
        }

        return response()->json([
            'patient' => $patient,
            'user' => $patient->user,
        ]);
    }

}
