<?php

namespace App\Http\Controllers;

use App\Models\Ordonnance;
use App\Models\User;
use Illuminate\Http\Request;


class PharmacyController extends Controller
{
    public function getPatient(string $code_ordonnance)
    {

        $user = User::findOrFail($code_ordonnance);
        return response()->json([
            'message' => 'Réussie',
            'user' => $user,

        ], 200);

    }
    public function getOrdonnances($id)
    {
        $user = User::findOrFail($id);
        $ordonnances = Ordonnance::where('patient_id', $user->id)->with('medicaments_prescrits', 'medicaments_prescrits.pharmaceutical_product')->get();

        return response()->json([
            'message' => 'succès',
            'ordonnances' => $ordonnances,
        ], 200);
    }
}
