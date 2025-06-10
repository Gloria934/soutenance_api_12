<?php

namespace App\Http\Controllers;

use App\Enums\StatutEnum;
use App\Models\RendezVous;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'service_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }



        $rdv = RendezVous::create([
            'nom_visiteur'=>$request->nom_visiteur,
            'prenom_visiteur'=>$request->prenom_visiteur,
            'numero_visiteur'=>$request->numero_visiteur,
            'patient_id' => $request->patient_id,
            'service_id' => $request->service_id,
            'date_rdv' => $request->date_rdv,
            'statut' => StatutEnum::ENATTENTE->value,
        ]);

        return response()->json([
            'message' => 'Appointment created successfully',
            'rdv' => $rdv
        ], 200);
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
}
