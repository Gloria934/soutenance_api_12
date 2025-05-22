<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): Response
    {
        // Vérifier que l'utilisateur est authentifié
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié.',
            ], 401);
        }

        // Révoquer tous les jetons de l'utilisateur
        $user->tokens()->delete();

        // Déconnecter l'utilisateur
        Auth::guard('sanctum')->logout();

        return response()->json([
            'message' => 'Déconnexion réussie.',
        ], 200);
    }
}