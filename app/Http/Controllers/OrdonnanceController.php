<?php

namespace App\Http\Controllers;

use App\Models\MedicamentPrescrit;
use App\Models\Ordonnance;
// use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        \Log::info('Début de la méthode store', [
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);

        try {
            // Validation des données
            \Log::info('Début de la validation des données');
            $validator = $request->validate([
                'montant_total' => ['required', 'numeric'],
                'medicaments_prescrits' => ['required', 'array'],
                'medicaments_prescrits.*.quantite' => ['required', 'integer'],
                'medicaments_prescrits.*.statut' => ['required', 'boolean'],
                'medicaments_prescrits.*.posologie' => ['nullable', 'string'],
                'medicaments_prescrits.*.duree' => ['nullable', 'string'],
                'medicaments_prescrits.*.avis' => ['nullable', 'string'],
                'medicaments_prescrits.*.substitution_autorisee' => ['nullable', 'boolean'],
            ]);
            \Log::info('Validation réussie', ['validated_data' => $validator]);

            // Création de l'ordonnance
            \Log::info('Création de l\'ordonnance');
            $ordonnance = Ordonnance::create([
                'montant_total' => $validator['montant_total'],
                'montant_paye' => 0, // Ajustez selon votre logique
            ]);
            \Log::info('Ordonnance créée', ['ordonnance_id' => $ordonnance->id]);

            // Création des médicaments prescrits
            \Log::info('Début de la création des médicaments prescrits');
            foreach ($validator['medicaments_prescrits'] as $index => $medicamentPrescrit) {
                \Log::info('Création du médicament prescrit', ['index' => $index, 'data' => $medicamentPrescrit]);
                MedicamentPrescrit::create([
                    'ordonnance_id' => $ordonnance->id,
                    'quantite' => $medicamentPrescrit['quantite'],
                    'statut' => $medicamentPrescrit['statut'],
                    'posologie' => $medicamentPrescrit['posologie'] ?? null,
                    'duree' => $medicamentPrescrit['duree'] ?? null,
                    'avis' => $medicamentPrescrit['avis'] ?? null,
                    'substitution_autorisee' => $medicamentPrescrit['substitution_autorisee'] ?? false,
                    // Ajoutez 'pharmaceutical_product_id' si nécessaire
                ]);
            }
            \Log::info('Médicaments prescrits créés avec succès');

            // Réponse JSON en cas de succès
            \Log::info('Préparation de la réponse JSON');
            return response()->json([
                'message' => 'Enregistrement effectué avec succès',
                'ordonnance' => $ordonnance,
            ], 201);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            \Log::error('Erreur de validation', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Gestion des autres erreurs
            \Log::error('Erreur inattendue dans la méthode store', [
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
