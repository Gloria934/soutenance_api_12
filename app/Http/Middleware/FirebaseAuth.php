<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Kreait\Firebase\Auth as FirebaseAuthSDK;
use Kreait\Firebase\Exception\Auth\InvalidToken;
use Kreait\Firebase\Exception\FirebaseException;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FirebaseAuth
{
    protected FirebaseAuthSDK $firebaseAuth;

    public function __construct(FirebaseAuthSDK $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Token manquant ou invalide.'], 401);
        }

        $idToken = substr($authHeader, 7); // Retire "Bearer "

        try {
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = User::where('firebase_uid', $uid)->first();
    
            // ↓ Supprime la création automatique et renvoie une 401 si l'utilisateur n'existe pas
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non enregistré.'], 401);
            }
    
            Auth::guard('api')->login($user);
            return $next($request);
    
        } catch (FirebaseException $e) {
            return response()->json(['message' => 'Token invalide.'], 401);
        }
        
    }
}
