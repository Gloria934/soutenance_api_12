<?php

// ce controller va effectuer une mise à jour du mot de passe

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FirebaseNewPasswordController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        try {
            $auth = app('firebase.auth');
            
            // 1. Vérifie le token Firebase
            $verifiedToken = $auth->verifyIdToken($request->token);
            $firebaseUid = $verifiedToken->claims()->get('sub');
            
            // 2. Met à jour Firebase
            $auth->changeUserPassword($firebaseUid, $request->password);
            
            // 3. Synchronise Laravel
            $user = User::where('firebase_uid', $firebaseUid)->firstOrFail();
            $user->update(['password' => Hash::make($request->password)]);
            
            return response()->json(['status' => 'password-updated']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
