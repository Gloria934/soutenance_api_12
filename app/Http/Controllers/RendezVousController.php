<?php

namespace App\Http\Controllers;

use App\Enums\StatutEnum;
use App\Models\Ordonnance;
use App\Models\RendezVous;
use App\Models\User;
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
        $nextRdv = RendezVous::where('patient_id', $user->id)->whereNotNull('date_rdv')
            ->where('statut', StatutEnum::CONFIRME->value)
            ->orderBy('date_rdv', 'asc')
            ->first();

        // Compter les ordonnances avec montant_paye > 0
        $nombreOrdonnance = Ordonnance::where('montant_paye', 0)->count();

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

        $user = User::create(
            [
                'nom' => $request->nom_visiteur,
                'prenom' => $request->prenom_visiteur,
                'code_patient' => 'PAT-' . $this->nextId(),

            ]
        );
        $user->assignRole('patient');



        $rdv = RendezVous::create([
            'nom_visiteur' => $request->nom_visiteur,
            'prenom_visiteur' => $request->prenom_visiteur,
            'numero_visiteur' => $request->numero_visiteur,
            'patient_id' => $request->patient_id,
            'service_id' => $request->service_id,
            'date_rdv' => $request->date_rdv,
            'code_rendez_vous' => "RDV-" . $this->generateCode(),
            'statut' => StatutEnum::ENATTENTE->value,
        ]);


        return response()->json([
            'message' => 'Rendez-vous créé avec succès ...',
            'rdv' => $rdv
        ], 200);
    }
    public function storeSpecialistRdv(Request $request)
    {
        $user = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [

            'specialiste_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }





        $rdv = RendezVous::create([

            'patient_id' => $user->id,
            'specialiste_id' => $request->specialiste_id,
            'code_rendez_vous' => "RDV-" . $this->generateCode(),
            'statut' => StatutEnum::ENATTENTE->value,
        ]);


        return response()->json([
            'message' => 'Rendez-vous créé avec succès ...',
            'rdv' => $rdv
        ], 200);
    }

    public static function generateCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }

    public function nextId()
    {
        $nextId = User::all()->count() + 1;
        return $nextId;

    }

    public function rdvRapide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
            'prenom' => 'required',
            'code_patient' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        $user = User::create(
            [
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'code_patient' => $request->code_patient,
            ]
        );
        $user->assignRole('patient');



        $rdv = RendezVous::create([
            'patient_id' => $user->id,
            'nom_visiteur' => $request->nom,
            'prenom_visiteur' => $request->prenom,
            'service_id' => $request->service_id,
            'date_rdv' => $request->date_rdv,
            'code_rendez_vous' => "RDV-" . $this->generateCode(),
            'statut' => StatutEnum::ENATTENTE->value,
        ]);


        return response()->json([
            'message' => 'Rendez-vous créé avec succès ...',
            'rdv' => $rdv
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $rdvs = RendezVous::where('patient_id', $user->id)->with('patient', 'service')->get();

        return response()->json([
            'message' => 'succès',
            'appointments' => $rdvs,
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $rdv = RendezVous::withTrashed()->find($id);

        if (!$rdv) {
            return response()->json([
                'success' => false,
                'message' => 'Rendez-vous not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'service_id' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $rdv->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $rdv,
            'message' => 'Rendez-vous updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    // Je dois récupérer les utilisateurs qui ont pour serviceId l'id du service du rendezvous 
    private function sendOtpViaOneSignal(string $playerId, string $message)
    {
        $url = "https://onesignal.com/api/v1/notifications";

        $apiKey = env("ONESIGNAL_REST_API_KEY");
        $appId = env("ONESIGNAL_APP_ID");

        $headers = [
            "Authorization: Basic " . $apiKey,

            "Content-Type: application/json"
        ];

        $data = [
            "app_id" => $appId,
            "include_player_ids" => [$playerId],
            "headings" => ["en" => "mediPay"],
            "contents" => ["en" => $message],
            "priority" => 10,
        ];

        \Log::info('OneSignal Notification - Data to send', $data);
        \Log::info('OneSignal Notification - Headers', $headers);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            \Log::error('OneSignal Notification - CURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        \Log::info('OneSignal Notification - HTTP Code', ['httpCode' => $httpCode]);
        \Log::info('OneSignal Notification - Response', ['response' => $response]);

        return [
            'status' => $httpCode,
            'response' => $response
        ];
    }
}
