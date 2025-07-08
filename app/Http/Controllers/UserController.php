<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Exception;
use Spatie\Permission\Models\Role;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\SimpleNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\OneSignal;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $validatedData = $request->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:20'],
                'genre' => ['nullable', 'string'],
                'date_naissance' => ['nullable', 'string'],
                'npi' => ['nullable', 'string'],
                'role_voulu' => ['nullable', 'string'],
                'service_voulu' => ['nullable', 'string'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'device_token' => ['nullable', 'string'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Création de l'utilisateur
            $user = User::create([
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'],
                'telephone' => $validatedData['telephone'],
                'genre' => $validatedData['genre'] ?? null,
                'date_naissance' => $validatedData['date_naissance'] ?? null,
                'npi' => $validatedData['npi'] ?? null,
                'role_voulu' => $validatedData['role_voulu'] ?? null,
                'service_voulu' => $validatedData['service_voulu'] ?? null,
                'email' => $validatedData['email'],
                'device_token' => $validatedData['device_token'] ?? null,
                'password' => Hash::make($validatedData['password']),
            ]);

            if ($user->role_voulu != null) {
                // Créer une notification pour l'administrateur
                SimpleNotification::create([
                    'personnel_sante_id' => $user->id,
                    'type' => 'personnel_registration',
                    'status' => 'pending',
                ]);
            }



            // Assignation du rôle

            // else {
            //     // Temporarily assign a pending role or no role until admin approval
            //     $user->assignRole('pending_personnel');
            // }

            if (!User::role('admin')->exists()) {
                $user->assignRole('admin');
            } elseif ($user->role_voulu == null) {
                $user->assignRole('patient');
                $user->code_patient = "PAT-$user->id";
                $user->save();
            } else {
                $user->assignRole('pending'); // Rôle temporaire jusqu'à approbation
                // $this->sendNotificationToAdmin($user);
                $admin = User::role('admin')->select('device_token')->first();
                // \Log::error('Le code de l\'admin:  ' . $admin->device_token);
                // $this->sendOtpViaOneSignal($admin->device_token);
                $this->sendOtpViaOneSignal($admin->device_token);


            }

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
                'role' => $user->getRoleNames()->first(),
            ], 201);
        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
