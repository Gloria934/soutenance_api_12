<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request): Response|\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        // 1. Assigner le rôle patient
        $user->assignRole('patient');

        // 2. Créer le code patient via une fonction personnalisée
        $codePatient = $this->generatePatientCode();

        // 3. Création d’un enregistrement Patient lié à cet utilisateur
        $user->patient()->create([
            'patient_code' => $codePatient
        ]);

        // 4. Envoi de l'email de vérification
        event(new Registered($user));
        $user->sendEmailVerificationNotification();

        // 5. Connexion automatique
        Auth::login($user);

        // 6. Création d’un token pour l’API (utile côté Flutter)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 7. Réponse JSON à retourner au client
        return response()->json([
            'message'       => 'Inscription réussie',
            'token'         => $token,
            'user'          => $user,
            'roles'         => $user->getRoleNames(),
            'patient_code'  => $codePatient,
        ]);
    }

   
}
