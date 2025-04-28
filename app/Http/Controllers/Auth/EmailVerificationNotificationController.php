<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;


class EmailVerificationNotificationController extends Controller
{
    /**
     * Vérifier l'email de l'utilisateur.
     */
    public function verify(Request $request)
    {
        $user = $request->user();

        // Si l'email a déjà été vérifié
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email déjà vérifié.']);
        }

        // Marquer l'email comme vérifié
        $user->markEmailAsVerified();

        // Déclencher un événement (par exemple, pour envoyer une notification)
        event(new Verified($user));

        return response()->json(['message' => 'Email vérifié avec succès.']);
    }
}
