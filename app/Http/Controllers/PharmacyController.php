<?php

namespace App\Http\Controllers;

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
}
