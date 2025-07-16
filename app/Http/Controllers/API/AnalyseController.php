<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Analyse;
use App\Models\AnalysePatient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Auth;
use App\Enums\StatutEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;






class AnalyseController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        $analyses = Analyse::all();
        return response()->json([
            'status' => 200,
            'analyses' => $analyses
        ]);
    }

    public function getAuthAnalyses()
    {
        $user = Auth::guard('api')->user();

        $analyses = AnalysePatient::where('patient_id', $user->id)->with('analyse', 'patient')->get();
        return response()->json([
            'status' => 200,
            'analyses' => $analyses
        ]);
    }

    // Crée une nouvelle catégorie
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'string|nullable',
            'prix' => 'numeric|nullable',


        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $analyse = Analyse::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Analyse created successfully',
            'analyse' => $analyse
        ], 201);
    }
    public function storeAnalyseRdv(Request $request)
    {
        $user = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [

            'analyse_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $rdv = AnalysePatient::create([

            'patient_id' => $user->id,
            'analyse_id' => $request->analyse_id,
            'code_analyse_patient' => "ESP-" . $this->generateCode(),
            'statut' => StatutEnum::ENATTENTE->value,
        ]);


        return response()->json([
            'message' => 'Rendez-vous créé avec succès ...',
            'rdv' => $rdv
        ], 200);
    }
    public function getAnalyseAppointments()
    {
        $analyses = AnalysePatient::with('patient', 'analyse')->get();
        if ($analyses) {
            return response()->json([
                'message' => 'succès',
                'analyses' => $analyses,
            ], 200);
        } else {
            return response()->json([
                'message' => 'aucun rendez-vous d\'analyse trouvé',

            ], 210);
        }
    }

    public function updateAnalyse(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'analyse_patient_id' => ['required', 'numeric', 'min:0'],
            ]);

            // Log incoming request
            Log::info('Incoming updateAnalyse request', [
                'data' => $request->all(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            // Use database transaction to ensure data consistency
            return DB::transaction(function () use ($validated) {
                // Update Ordonnance
                AnalysePatient::findOrFail($validated['analyse_patient_id'])->update(['statut' => StatutEnum::CONFIRME->value]);
                // $consultation->statut = 
                // $ordonnance->save();

                Log::info('Analyse updated successfully');

                return response()->json([
                    'message' => 'Mise à jour effectuée avec succès',
                ], 200);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error in updateConsultation', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Unexpected error in updateAnalyse', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editerAnalyse(Request $request)
    {
        $analyse = AnalysePatient::findOrFail($request->analyse_patient_id);
        if ($analyse) {
            $analyse->date_rdv = $request->date_rdv;
            $analyse->save();
            $patient = User::findOrFail($analyse->patient_id);
            $this->sendOtpViaOneSignal($patient->device_token);

            return response()->json([
                'success' => true,
                'analyse' => $analyse,
                'message' => 'Analyse modifiée avec succès'
            ], 200);

        }
    }

    public function invaliderAnalyse(Request $request)
    {
        // $ordonnance = Ordonnance::findOrFail($request->id);
        $analyse = AnalysePatient::where('code_analyse_patient', $request->code_analyse_patient)->first();
        $analyse->statut = StatutEnum::TERMINE->value;
        $analyse->save();
        return response()->json([
            'message' => 'succès',
            'analyse' => $analyse,
        ], 200);

    }


    // Affiche une catégorie spécifique
    public function show($id)
    {
        $analyse = Analyse::find($id);
        if ($analyse) {
            return response()->json([
                'status' => 200,
                'analyse' => $analyse
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'analyse not found'
            ], 404);
        }
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

    // Met à jour une catégorie
    public function update(Request $request, $id)
    {
        $analyse = Analyse::find($id);
        if (!$analyse) {
            return response()->json([
                'status' => 404,
                'message' => 'analyse not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $analyse->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'analyse updated successfully',
            'analyse' => $analyse
        ]);
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $analyse = Analyse::find($id);
        if (!$analyse) {
            return response()->json([
                'status' => 404,
                'message' => 'analyse not found'
            ], 404);
        }

        $analyse->delete();
        return response()->json([
            'status' => 200,
            'message' => 'analyse deleted successfully'
        ]);
    }

    private function sendOtpViaOneSignal(string $playerId)
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
            "contents" => ["en" => "Votre demande d'analyse vient d'être approuvée. Veuillez procéder maintenant au paiement"],
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