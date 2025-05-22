<?php

//ce controller permet à l'utilisateur de créer un compte (ce qui suppose qu'il n'a pas encore de compte)


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseRegisterController extends Controller
{
    public function register(Request $request) 
    {
        $request->validate([
            'firebase_uid' => 'required|string', // Nouveau champ
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        try {
            // 1. Vérification manuelle de l'UID Firebase (optionnel)
            $auth = app('firebase.auth');
            $firebaseUser = $auth->getUser($request->firebase_uid);

            // 2. Création de l'utilisateur
            $user = User::create([
                'firebase_uid' => $request->firebase_uid,
                'email' => $request->email,
                'name' => $request->name ?? 'Utilisateur',
                'password' => Hash::make($request->password), // Hash Laravel
            ]);

            // 3. Réponse avec token d'accès
            return response()->json([
                'user' => $user,
                'access_token' => $user->createToken('firebase-token')->plainTextToken,
            ], 201);

        } catch (FailedToVerifyToken $e) {
            return response()->json(['error' => 'UID Firebase invalide'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur d\'inscription: ' . $e->getMessage()], 500);
        }
    }
}