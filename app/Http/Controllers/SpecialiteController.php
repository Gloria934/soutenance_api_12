<?php

namespace App\Http\Controllers;

use App\Models\LangueSpecialite;
use App\Models\Specialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SpecialiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {

    }

    // Fonction pour récupérer la spécialité d'un utilisateur
    public function getUserSpecialites()
    {

        $specialistes = User::role('spécialiste')->get();
        if ($specialistes) {
            return response()->json([
                'success' => true,

                'specialistes' => $specialistes,
                'message' => 'Spécialistes récupérés avec succès.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => ' Aucun Spécialiste trouvé.'
            ], 400);
        }
    }
    // public function enregistrerSpecialiste(Request $request)
    // {
    //     $langue_ids = $request->langue_ids;
    //     $specialiste = Auth::guard('api')->user();
    //     $specialite = Specialite::create(
    //         [
    //             'description' => $request->description,
    //             'tarif' => $request->tarif,
    //             'specialiste_id' => $specialiste->id,
    //         ]
    //     );
    //     foreach ($langue_ids as $langue_id) {
    //         LangueSpecialite::create([
    //             'specialite_id' => $specialite->id,
    //             'langue_id' => $langue_id,
    //         ]);
    //     }

    // }

    public function getSpecialistInfo(string $specialist_id)
    {
        $specialite = Specialite::where('specialiste_id', $specialist_id)->first();
        if ($specialite) {
            return response()->json([
                'message' => "Succès",
                "specialite" => $specialite,
            ], 200);
        } else {
            return response()->json([
                "message" => "Spécilité inexistante",
            ], 210);
        }
    }
    public function enregistrerSpecialiste(Request $request)
    {
        // Journaliser les données d'entrée
        Log::info('Début de la fonction enregistrerSpecialiste', [
            'request_data' => $request->all(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            // Valider les données d'entrée
            $validated = $request->validate([
                'tarif' => 'required|numeric',
                'description' => 'required|string',
                'langue_ids' => 'required|array',
                'langue_ids.*' => 'integer|exists:langues,id',
            ]);

            Log::info('Données validées avec succès', [
                'validated_data' => $validated,
            ]);

            // Vérifier l'utilisateur authentifié
            $specialiste = Auth::guard('api')->user();
            if (!$specialiste) {
                Log::warning('Aucun utilisateur authentifié trouvé');
                return response()->json([
                    'message' => 'Utilisateur non authentifié',
                ], 401);
            }

            Log::info('Utilisateur authentifié', [
                'specialiste_id' => $specialiste->id,
                'specialiste_email' => $specialiste->email,
            ]);

            // Créer la spécialité
            $specialite = Specialite::create([
                'description' => $request->description,
                'tarif' => $request->tarif,
                'specialiste_id' => $specialiste->id,
            ]);

            Log::info('Spécialité créée avec succès', [
                'specialite_id' => $specialite->id,
                'specialite_data' => $specialite->toArray(),
            ]);

            // Associer les langues
            $langue_ids = $request->langue_ids;
            foreach ($langue_ids as $langue_id) {
                Log::debug('Traitement de langue_id', [
                    'langue_id' => $langue_id,
                    'specialite_id' => $specialite->id,
                ]);

                LangueSpecialite::create([
                    'specialite_id' => $specialite->id,
                    'langue_id' => $langue_id,
                ]);

                Log::debug('Langue associée avec succès', [
                    'langue_id' => $langue_id,
                    'specialite_id' => $specialite->id,
                ]);
            }

            Log::info('Enregistrement du spécialiste terminé avec succès', [
                'specialite_id' => $specialite->id,
                'langue_ids' => $langue_ids,
            ]);

            // Retourner une réponse de succès
            return response()->json([
                'message' => 'Spécialité enregistrée avec succès',
                'specialite' => $specialite,
                'langue_ids' => $langue_ids,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Données invalides',
                'errors' => $e->errors(),
            ], 400);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement du spécialiste', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'enregistrement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {

    }


    // Trouver la spécialité associée à un utilisateur en suivant son token.
    // public function findUserSpecialite()
    // {
    //     $user = Auth::guard('api')->user();
    //     $specialite = Specialite::where('specialiste_id', $user->id)->first();
    //     if ($specialite) {
    //         return response()->json([
    //             'message' => 'success',
    //             'specialite' => $specialite,
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'erreur',
    //         ], 404);
    //     }
    // }

    public function findUserSpecialite()
    {
        try {
            Log::info('Début de la fonction findUserSpecialite');

            // Vérifier si l'utilisateur est authentifié
            $user = Auth::guard('api')->user();
            if (!$user) {
                Log::warning('Aucun utilisateur authentifié trouvé');
                return response()->json([
                    'message' => 'Utilisateur non authentifié',
                ], 401);
            }
            Log::info('Utilisateur authentifié', ['user_id' => $user->id]);

            // Rechercher la spécialité
            $specialite = Specialite::where('specialiste_id', $user->id)->first();
            if ($specialite) {
                Log::info('Spécialité trouvée pour l\'utilisateur', [
                    'user_id' => $user->id,
                    'specialite_id' => $specialite->id
                ]);
                return response()->json([
                    'message' => 'success',
                    'specialite' => $specialite,
                ], 200);
            } else {
                Log::warning('Aucune spécialité trouvée pour l\'utilisateur', ['user_id' => $user->id]);
                return response()->json([
                    'message' => 'Aucune spécialité trouvée',
                ], 210);
            }
        } catch (\Exception $e) {
            Log::error('Erreur dans findUserSpecialite', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
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
