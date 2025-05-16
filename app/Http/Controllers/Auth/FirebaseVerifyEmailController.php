<?php

// ce controller va servir à faire la vérification d'email

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;
use Illuminate\Http\Request;

class FirebaseVerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate(['token' => 'required']);

        try {
            $auth = app('firebase.auth');
            $verifiedToken = $auth->verifyIdToken($request->token);
            $firebaseUid = $verifiedToken->claims()->get('sub');
            
            // Synchronisation Laravel
            $user = User::where('firebase_uid', $firebaseUid)->firstOrFail();
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                $auth->updateUser($firebaseUid, ['emailVerified' => true]);
            }

            return response()->json(['status' => 'email-verified']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Token invalide'], 400);
        }
    }
}
