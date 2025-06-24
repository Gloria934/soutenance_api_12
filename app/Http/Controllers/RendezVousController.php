<?php

namespace App\Http\Controllers;

use App\Enums\StatutEnum;
use App\Models\Ordonnance;
use App\Models\RendezVous;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rdvs = RendezVous::with('patient', 'service')->get();
        if ($rdvs != null) {
            return response()->json([
                'message' => 'réussite',
                'rdvs' => $rdvs,

            ], 200);
        }

    }


    public function accueil()
    {
        $user = Auth::guard('api')->user();

        // Vérifiez si l'utilisateur est authentifié
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié',
            ], 401);
        }

        // Récupérer le prochain rendez-vous
        $nextRdv = RendezVous::where('patient_id', $user->id)
            ->where('statut', StatutEnum::CONFIRME->value)
            ->orderBy('date_rdv', 'asc')
            ->first();

        // Compter les ordonnances avec montant_paye > 0
        $nombreOrdonnance = Ordonnance::where('montant_paye', '>', 0)->count();

        return response()->json([
            'message' => 'Succès',
            'nextRdv' => $nextRdv,
            'nombreOrdonnance' => $nombreOrdonnance,
        ], 200);
    }

    public function rendezVousAValider()
    {
        // Vérifier si l'utilisateur est authentifié
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié',
            ], 401);
        }

        // Récupérer les rendez-vous avec les relations patient et service
        $rdvs = RendezVous::with('patient', 'service')
            ->where('service_id', $user->service_voulu)
            ->get();

        // Vérifier si des rendez-vous existent
        if ($rdvs->isEmpty()) {
            return response()->json([
                'message' => 'Aucun rendez-vous à valider trouvé',
                'rdvs' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Liste des rendez-vous à valider récupérée avec succès',
            'rdvs' => $rdvs,
        ], 200);
    }

    public function getUserRdv()
    {
        $user = Auth::guard('api')->user();
        $rdvs = RendezVous::whereNotNull('date_rdv')->where('patient_id', $user->id)->with('patient', 'service')->get();

        return response()->json([
            'message' => 'succès',
            'rdvs' => $rdvs,
        ], 200);
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
            'nom_visiteur' => $request->nom_visiteur,
            'prenom_visiteur' => $request->prenom_visiteur,
            'numero_visiteur' => $request->numero_visiteur,
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
