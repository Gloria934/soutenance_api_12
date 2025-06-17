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
    public function store(Request $request, $medicamentsPrescrits)
    {
        $validator = $request->validate([
            'montant_total' => ['required', 'float'],
        ]);
        $ordonnance = Ordonnance::create(
            [

                'montant_total' => $validator['montant_paye'],
            ]
        );
        foreach ($medicamentsPrescrits as $medicamentPrescrit) {
            MedicamentPrescrit::create([
                'ordonnance_id' => $ordonnance->id,
                'quantite' => $medicamentPrescrit['quantite'],
                'statut' => $medicamentPrescrit['statut'],
                'posologie' => $medicamentPrescrit['posologie'],
                'duree' => $medicamentPrescrit['duree'],
                'avis' => $medicamentPrescrit['avis'],
                'substitution_autorisee' => $medicamentPrescrit['substitution_autorisee'],
            ]);
        }
        return response()->json([
            'message' => 'Enregistrement effectué avec succès',
            'ordonnance' => $ordonnance,
        ]);
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
