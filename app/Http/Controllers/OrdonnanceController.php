<?php

namespace App\Http\Controllers;

use App\Models\MedicamentPrescrit;
use App\Models\Ordonnance;
use Illuminate\Http\Request;

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

        $ordonnance = Ordonnance::create([
            'montant_total' => $validator['montant_total'],
            'montant_paye' => 0, // Ajustez selon votre logique
        ]);

        foreach ($validator['medicaments_prescrits'] as $medicamentPrescrit) {
            MedicamentPrescrit::create([
                'ordonnance_id' => $ordonnance->id,
                'quantite' => $medicamentPrescrit['quantite'],
                'statut' => $medicamentPrescrit['statut'],
                'posologie' => $medicamentPrescrit['posologie'],
                'duree' => $medicamentPrescrit['duree'],
                'avis' => $medicamentPrescrit['avis'],
                'substitution_autorisee' => $medicamentPrescrit['substitution_autorisee'],
                // Ajoutez 'pharmaceutical_product_id' si nécessaire
            ]);
        }

        return response()->json([
            'message' => 'Enregistrement effectué avec succès',
            'ordonnance' => $ordonnance,
        ], 201);
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
