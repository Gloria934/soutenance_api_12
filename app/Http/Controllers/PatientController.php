<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = User::role('patient')->get();
        if ($patients != null) {
            return response()->json([
                'patients' => $patients,
            ], 200);
        }
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
    public function findUserWithCodePatient(Request $request)
    {
        $user = User::where('code_patient', $request->code_patient)->first();
        if ($user) {
            return response()->json([
                'message' => 'success',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
