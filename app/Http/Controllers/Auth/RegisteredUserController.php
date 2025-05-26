<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:20'],
                'genre' => ['required', 'string',],
                'date_naissance' => ['nullable', 'string'],
                'id_personnel' => ['nullable', 'string'],
                'role_voulu' => ['nullable', 'integer', 'max:10'],
                'email' => ['required', 'string', 'email', 'max:255',],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Création de l'utilisateur
            $user = User::create([
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'],
                'telephone' => $validatedData['telephone'],
                'genre' => $validatedData['genre'],
                'date_naissance' => $validatedData['date_naissance'] ?? null,
                'id_personnel' => $validatedData['id_personnel'] ?? null,
                'role_voulu' => $validatedData['role_voulu'] ?? null,
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Déclenchement de l'événement Registered
            event(new Registered($user));

            // Connexion de l'utilisateur
            Auth::login($user);

            // Génération du token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Réponse réussie
            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => $user,
                'token' => $token,
            ], 201);

        }
        // catch (ValidationException $e) {
        //     // Erreurs de validation
        //     return response()->json([
        //         'message' => 'Erreur de validation',
        //         'errors' => $e->errors(),
        //     ], 422);
        // }
        catch (Exception $e) {
            // Autres erreurs (ex. base de données, serveur, etc.)
            \Log::error('Erreur lors de l\'inscription: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'inscription',
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 500);
        }
    }
}