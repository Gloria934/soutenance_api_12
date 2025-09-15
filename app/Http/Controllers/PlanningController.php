<?php

namespace App\Http\Controllers;

use App\Models\AnalysePatient;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;


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

            // Ligne suivante pour les analyses
            // $analyses = AnalysePatient::where('patient_id', $user->id)->whereNotNull('date_rdv')->with('analyse')->get();

            return response()->json([
                "message" => "Utilisateur trouvé",
                "rendez-vous" => $rendezvousService . isEmpty() ? false : true,
                "rdvsEtConsultationsServices" => $rendezvousService,
                "rdvsSpecilistes" => $rendezvousSpecialistes,
                // "analysesPatients" => $analyses,


            ], 200);
        } elseif (!$user) {
            return response()->json([
                "message" => "Utilisateur non trouvé",

            ], 201);
        }
    }
    public function authenticatedServicePlannings()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $rendezvousService = RendezVous::where('service_id', $user->service_voulu)->whereNotNull('service_id')->with('patient')->get();
            $rendezvousSpecialistes = RendezVous::where('specialiste_id', $user->id)->whereNull('service_id')->with('patient')->get();
            // $analyses = AnalysePatient::where('patient_id', $user->id)->whereNotNull('date_rdv')->with('analyse')->get();

            return response()->json([
                "message" => " Trouvé",
                "rdvsEtConsultationsServices" => $rendezvousService,
                "rdvsSpecilistes" => $rendezvousSpecialistes,
                // "analysesPatients" => $analyses,


            ], 200);
        } elseif (!$user) {
            return response()->json([
                "message" => "Non trouvé",

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
