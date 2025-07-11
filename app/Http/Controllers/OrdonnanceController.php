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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
                'patient_id' => ['required', 'numeric'],

            ]);
            \Illuminate\Support\Facades\Log::info('Validation réussie', ['validated_data' => $validator]);

            // Création de l'ordonnance
            \Illuminate\Support\Facades\Log::info('Création de l\'ordonnance');
            $ordonnance = Ordonnance::create([
                'montant_total' => $validator['montant_total'],
                'montant_paye' => 0, // Ajustez selon votre logique
                'code_ordonnance' => "ORD-" . $this->generateCode(),
                'patient_id' => $validator['patient_id'],

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



    public static function generateCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }
    public function getUserOrdonnances()
    {
        $user = Auth::guard('api')->user();
        $ordonnances = Ordonnance::where('patient_id', $user->id)->with('medicaments_prescrits', 'medicaments_prescrits.pharmaceutical_product')->get();

        return response()->json([
            'message' => 'succès',
            'ordonnances' => $ordonnances,
        ], 200);
    }

    public function findUserOrdonnance(Request $request)
    {
        $ordonnance = Ordonnance::where('code_ordonnance', $request->code_ordonnance)->first();
        $medsPrescrits = MedicamentPrescrit::where('ordonnance_id', $ordonnance->id)->where('statut', true)->with('pharmaceutical_product')->get();
        return response()->json([
            'message' => 'succès',
            'medicaments' => $medsPrescrits,
        ], 200);

    }

    public function invaliderOrdonnance(Request $request)
    {
        // $ordonnance = Ordonnance::findOrFail($request->id);
        $ordonnance = Ordonnance::where('code_ordonnance', $request->code_ordonnance)->first();
        $ordonnance->statut = true;
        $ordonnance->save();
        return response()->json([
            'message' => 'succès',
            'ordonnance' => $ordonnance,
        ], 200);

    }

    public function updateOrdonnance(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'montant_paye' => ['required', 'numeric', 'min:0'],
                'medicaments_prescrits' => ['required', 'array', 'min:1'],
                'medicaments_prescrits.*' => ['required', 'integer', 'exists:pharmaceutical_products,id'],
                'ordonnance_id' => ['required', 'integer', 'exists:ordonnances,id'],
            ]);

            // Log incoming request
            Log::info('Incoming updateOrdonnance request', [
                'data' => $request->all(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            // Use database transaction to ensure data consistency
            return DB::transaction(function () use ($validated) {
                // Update Ordonnance
                $ordonnance = Ordonnance::findOrFail($validated['ordonnance_id']);
                $ordonnance->montant_paye = $validated['montant_paye'];
                $ordonnance->save();

                Log::info('Ordonnance updated successfully', [
                    'ordonnance_id' => $ordonnance->id,
                    'montant_paye' => $validated['montant_paye']
                ]);

                // Update MedicamentsPrescrits
                $updatedCount = MedicamentPrescrit::where('ordonnance_id', $ordonnance->id)
                    ->whereIn('pharmaceutical_product_id', $validated['medicaments_prescrits'])
                    ->update(['statut' => true]);

                Log::info('MedicamentsPrescrits updated successfully', [
                    'ordonnance_id' => $ordonnance->id,
                    'updated_count' => $updatedCount
                ]);

                return response()->json([
                    'message' => 'Mise à jour effectuée avec succès',
                    'updated_medicaments_count' => $updatedCount
                ], 200);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error in updateOrdonnance', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Unexpected error in updateOrdonnance', [
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


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $ordonnances = Ordonnance::where('patient_id', $user->id)->with('medicaments_prescrits', 'medicaments_prescrits.pharmaceutical_product')->get();

        return response()->json([
            'message' => 'succès',
            'ordonnances' => $ordonnances,
        ], 200);
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
