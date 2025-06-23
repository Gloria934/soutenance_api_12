<?php

namespace App\Http\Controllers;

use App\Models\MedicamentPrescrit;
use App\Models\Ordonnance;
use App\Models\PharmaceuticalProduct;
use App\Models\User;
// use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


// use Illuminate\Support\Facades\Log;

// ValidationException


class OrdonnanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordonnances = Ordonnance::with('medicaments_prescrits', 'patient')->get();
        if ($ordonnances != null) {
            return response()->json([
                'message' => 'Ordonnaces récupérées avec succès',
                'ordonnances' => $ordonnances,


            ], );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log de la requête entrante pour vérifier les données envoyées
        \Illuminate\Support\Facades\Log::info('Début de la méthode store', [
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);

        try {
            // Validation des données
            \Illuminate\Support\Facades\Log::info('Début de la validation des données');
            \Illuminate\Support\Facades\Log::info('Liste des meds', ['validated_data' => $request->medicaments_prescrits]);

            $validator = $request->validate([
                'montant_total' => ['required', 'numeric'],
                'medicaments_prescrits' => ['required', 'array'],
                'patient_id'=>['required','numeric'],
                // 'medicaments_prescrits.*.quantite' => ['required', 'integer'],
                // 'medicaments_prescrits.*.statut' => ['required', 'boolean'],
                // 'medicaments_prescrits.*.posologie' => ['nullable', 'string'],
                // 'medicaments_prescrits.*.duree' => ['nullable', 'string'],
                // 'medicaments_prescrits.*.avis' => ['nullable', 'string'],
                // 'medicaments_prescrits.*.substitution_autorisee' => ['nullable', 'boolean'],
            ]);
            \Illuminate\Support\Facades\Log::info('Validation réussie', ['validated_data' => $validator]);

            // Création de l'ordonnance
            \Illuminate\Support\Facades\Log::info('Création de l\'ordonnance');
            $ordonnance = Ordonnance::create([
                'montant_total' => $validator['montant_total'],
                'montant_paye' => 0, // Ajustez selon votre logique
                'patient_id'=>$validator['patient_id'],
            ]);
            \Illuminate\Support\Facades\Log::info('Ordonnance créée', ['ordonnance_id' => $ordonnance->id]);

            // Création des médicaments prescrits
            \Illuminate\Support\Facades\Log::info('Début de la création des médicaments prescrits');
            foreach ($validator['medicaments_prescrits'] as $index => $medicamentPrescrit) {
                \Illuminate\Support\Facades\Log::info('Création du médicament prescrit', ['index' => $index, 'data' => $medicamentPrescrit]);
                MedicamentPrescrit::create([
                    'ordonnance_id' => $ordonnance->id,
                    'quantite' => $medicamentPrescrit['quantite'],
                    'statut' => $medicamentPrescrit['statut'],
                    'posologie' => $medicamentPrescrit['posologie'] ?? null,
                    'duree' => $medicamentPrescrit['duree'] ?? null,
                    'avis' => $medicamentPrescrit['avis'] ?? null,
                    'substitution_autorisee' => $medicamentPrescrit['substitution_autorisee'] ?? false,
                    'pharmaceutical_product_id' => $medicamentPrescrit['pharmaceutical_product_id'],

                    // Ajoutez 'pharmaceutical_product_id' si nécessaire
                ]);
            }
            \Illuminate\Support\Facades\Log::info('Médicaments prescrits créés avec succès');

            // Réponse JSON en cas de succès
            \Illuminate\Support\Facades\Log::info('Préparation de la réponse JSON');
            return response()->json([
                'message' => 'Enregistrement effectué avec succès',
                'ordonnance' => $ordonnance,
            ], 201);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            \Illuminate\Support\Facades\Log::error('Erreur de validation', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Gestion des autres erreurs
            \Illuminate\Support\Facades\Log::error('Erreur inattendue dans la méthode store', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'enregistrement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function getUserOrdonnances()
    {
        $user = Auth::guard('api')->user();
        $ordonnances = Ordonnance::where('patient_id',$user->id)->with('medicaments_prescrits','medicaments_prescrits.pharmaceutical_product')->get();

        return response()->json([
            'message'=>'succès',
            'ordonnances'=>$ordonnances,
        ],200);
    }

    public function updateOrdonnance(Request $request)
    {
        // Log de la requête entrante pour vérifier les données envoyées
        // \Illuminate\Support\Facades\Log::info('Début de la méthode store', [
        //     'request_data' => $request->all(),
        //     'headers' => $request->headers->all(),
        //     'ip' => $request->ip(),
        //     'url' => $request->fullUrl(),
        // ]);

        try {
            // Validation des données
            \Illuminate\Support\Facades\Log::info('Début de la validation des données');
            \Illuminate\Support\Facades\Log::info('Liste des meds', ['validated_data' => $request->medicaments_prescrits]);

            $validator = $request->validate([
                'montant_paye' => ['required'],
                'medicaments_prescrits' => ['required', 'array'],
                // 'patient_id'=>['required','numeric'],
                
            ]);
            \Illuminate\Support\Facades\Log::info('Validation réussie', ['validated_data' => $validator]);

            // Création de l'ordonnance
            \Illuminate\Support\Facades\Log::info('Création de l\'ordonnance');

            $ordonnance = Ordonnance::find($validator['medicaments_prescrits'][0]);
            $ordonnance->montant_paye = $alidator['montant_paye'];

            // $ordonnance = Ordonnance::create([
            //     'montant_total' => $validator['montant_total'],
            //     'montant_paye' => 0, // Ajustez selon votre logique
            //     'patient_id'=>$validator['patient_id'],
            // ]);
            \Illuminate\Support\Facades\Log::info('Ordonnance mise à jour ');

            // Création des médicaments prescrits
            \Illuminate\Support\Facades\Log::info('Début de la création des médicaments prescrits');
            foreach ($validator['medicaments_prescrits'] as $index => $medicamentPrescrit) {
                \Illuminate\Support\Facades\Log::info('Création du médicament prescrit', ['index' => $index, 'data' => $medicamentPrescrit]);
                MedicamentPrescrit::create([
                    'statut' => true,
                ]);
            }
            \Illuminate\Support\Facades\Log::info('Médicaments prescrits créés avec succès');

            // Réponse JSON en cas de succès
            \Illuminate\Support\Facades\Log::info('Préparation de la réponse JSON');
            return response()->json([
                'message' => 'Mise à jour effectuée avec succès',
            ], 201);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            \Illuminate\Support\Facades\Log::error('Erreur de validation', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Gestion des autres erreurs
            \Illuminate\Support\Facades\Log::error('Erreur inattendue dans la méthode store', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
