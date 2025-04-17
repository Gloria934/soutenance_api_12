<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

use App\Models\Patient;

class AuthController extends Controller
{
    // ✅ Enregistrement
    

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Rôle par défaut : patient
        $user->assignRole('patient');

        // Création du patient avec code généré automatiquement
        $user->patient()->create([
            'code_patient' => Patient::generatePatientCode(),
            // ajoute ici d'autres champs si nécessaires
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'user'    => $user,
            'role'    => $user->getRoleNames(),
            'token'   => $token,
        ]);
    }

    

    // ✅ Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token'   => $token,
            'user'    => $user,
            'role'    => $user->getRoleNames()
        ]);
    }

    // ✅ Déconnexion
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }

    // ✅ Récupérer l’utilisateur connecté
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'roles' => $request->user()->getRoleNames()
        ]);
    }

    //fonction d'affichage d'infos de l'utilisateur connecté, lorsqu'il s'agit d'un patient

    /*
    public function me(Request $request)
{
    $user = $request->user();
    $roles = $user->getRoleNames();

    $extraData = [];

    if ($roles->contains('patient') && $user->patient) {
        $extraData['code_patient'] = $user->patient->code_patient;
    }

    return response()->json([
        'user'  => $user,
        'roles' => $roles,
        ...$extraData
    ]);
}
*/
}
