<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'code_patient' => ['nullable', 'string', 'max:40'],
            'genre' => ['required', 'string', 'in:homme,femme,autre'],
            'date_naissance' => ['nullable', 'date'],
            'id_personnel' => ['nullable', 'string', 'max:255'],
            'role_voulu' => ['nullable', 'integer', 'max:10'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([

            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'code_patient' => $request->code_patient,
            'genre' => $request->genre,
            'date_naissance' => $request->date_naissance,
            'id_personnel' => $request->id_personnel,
            'role_voulu' => $request->role_voulu,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Connecter l'utilisateur et générer un jeton d'authentification
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}