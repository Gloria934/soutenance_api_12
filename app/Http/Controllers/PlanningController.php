<?php

namespace App\Http\Controllers;

use App\Models\AnalysePatient;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function authenticatedUserPlannings()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $rendezvousService = RendezVous::where('patient_id', $user->id)->whereNotNull('service_id')->with('service')->get();
            $rendezvousSpecialistes = RendezVous::where('patient_id', $user->id)->whereNull('service_id')->with('specialiste')->get();
            $analyses = AnalysePatient::where('patient_id', $user->id)->with('analyse')->get();

            return response()->json([
                "message" => "Utilisateur trouvé",
                "rdvsEtConsultationsServices" => $rendezvousService,
                "rdvsSpecilistes" => $rendezvousSpecialistes,
                "analysesPatients" => $analyses,


            ], 200);
        } elseif (!$user) {
            return response()->json([
                "message" => "Utilisateur non trouvé",

            ], 201);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
