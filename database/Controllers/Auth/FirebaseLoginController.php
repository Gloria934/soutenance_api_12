<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Laravel\Sanctum\HasApiTokens; // 

/**
 * @method \Laravel\Sanctum\NewAccessToken createToken(string $name, array $abilities = ['*'], ?\DateTimeInterface $expiresAt = null)
 */
class FirebaseLoginController extends Controller
{
    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 1. Authentification Laravel
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Identifiants invalides'], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        // 2. Vérification Firebase
        try {
            $auth = app('firebase.auth');
            $firebaseUser = $auth->getUserByEmail($request->email);

            // 3. Génération du token avec type hinting
            $token = $this->generateApiToken($user);

            return response()->json([
                'user' => $user->only(['id', 'name', 'email']),
                'access_token' => $token,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'user' => $user->only(['id', 'name', 'email']),
                'access_token' => $this->generateApiToken($user, 'fallback-token'),
                'warning' => 'Firebase non disponible',
            ]);
        }
    }

    /**
     * Génère un token API avec typage fort
     */
    protected function generateApiToken(User $user, string $name = 'auth-token'): string
    {
        return $user->createToken($name)->plainTextToken;
    }
}