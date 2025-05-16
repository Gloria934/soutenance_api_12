<?php

// ce controller va effectuer un renvoi de notification

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;

class FirebaseEmailVerificationNotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $auth = app('firebase.auth');
            $firebaseUser = $auth->getUserByEmail($request->email);
            
            // Envoi le lien de vérification Firebase
            $auth->sendEmailVerificationLink($request->email);
            
            return response()->json(['status' => 'verification-link-sent']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur d\'envoi'], 500);
        }
    }
}
