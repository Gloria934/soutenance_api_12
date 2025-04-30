<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        // ✅ 1. Validation avec messages personnalisés
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'L\'adresse email est obligatoire.',
            'email.email'       => 'L\'adresse email n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // ✅ 2. Limiter les tentatives de connexion (anti-brute-force)
        $key = Str::lower($request->email) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['message' => 'Trop de tentatives. Réessayez dans 1 minute.'], 429);
        }

        RateLimiter::hit($key, 60); // expire après 60 secondes

        // Recherche de l’utilisateur
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        // ✅ 3. Vérification si le compte est actif
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'Un lien de confirmation a été envoyé à votre email. Veuillez l’utiliser pour activer votre compte.'
            ], 403);
        }
        

        // ✅ 4. Vérifier si l’utilisateur a le droit d’accéder (optionnel)
        // Exemple : bloquer accès aux patients via cette API
        // if ($user->hasRole('patient')) {
        //     return response()->json(['message' => 'Accès non autorisé'], 403);
        // }

        // ✅ 5. Création du token
        $token = $user->createToken('auth_token')->plainTextToken;

        // ✅ 6. Renvoyer des informations supplémentaires (redirection)
        return response()->json([
            'message'       => 'Connexion réussie',
            'token'         => $token,
            'user'          => $user,
            'roles'         => $user->getRoleNames(),
            'code_patient'  => optional($user->patient)->code_patient,
            'redirect_url'  => $this->getRedirectUrl($user), // 👈 nouvelle méthode
        ]);
    }

    public function destroy(Request $request)
    {
        // ✅ Suppression du token actuel
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    // ✅ Méthode ajoutée pour déterminer la redirection selon le rôle
    private function getRedirectUrl(User $user)
    {
        if ($user->hasRole('admin')) {
            return '/admin/dashboard';
        }

        if ($user->hasRole('patient')) {
            return '/patient/dashboard';
        }

        if ($user->hasRole('pharmacien')) {
            return '/pharmacien/home';
        }

        return '/';
    }
}
