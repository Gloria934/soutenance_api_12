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
        
            $firebaseUser = $this->firebaseAuth->getUser($uid);
            $email = $firebaseUser->email;
            $phone = $firebaseUser->phoneNumber;
        
            $user = User::where('firebase_uid', $uid)->first();
        
            if (!$user) {
                $user = User::create([
                    'firebase_uid' => $uid,
                    'email' => $email,
                    'telephone' => $phone,
                    'email_verified_at' => now(),
                    'password' => null,
                ]);
        
                $user->assignRole('patient');
            }
        
            Auth::guard('api')->login($user);

        
        } catch (FirebaseException $e) {
            return response()->json(['message' => 'Erreur Firebase : ' . $e->getMessage()], 401);
        }
        
        
        return $next($request);
    }
}
