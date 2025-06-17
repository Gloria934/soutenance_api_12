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
use Spatie\Permission\Models\Role;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\SimpleNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    protected $messaging;
    public function __construct()
    {
        // $credentials = config('firebase.credentials.file');
        // dd($credentials); // Affiche la valeur pour débogage
        $credentials = base_path(config('firebase.projects.default.credentials.file'));

        $firebase = (new Factory)
            ->withServiceAccount($credentials)
            ->createMessaging();
        $this->messaging = $firebase;
    }

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
                $user->code_patient == PatientCodeGenerator::generatePatientCode();
            } else {
                $user->assignRole('pending'); // Rôle temporaire jusqu'à approbation
                $this->sendNotificationToAdmin($user);
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
     * Send notification to all admin users about new personnel registration.
     *
     * @param User $user
     * @return void
     */
    protected function sendNotificationToAdmin(User $user): void
    {
        try {
            // Récupérer l'unique admin avec le rôle 'admin'
            $adminUser = User::role('admin')->first();

            // Vérifier si un admin existe et s'il a un device_token
            if (!$adminUser || !$adminUser->device_token) {
                \Log::info('Aucun admin trouvé ou aucun device_token disponible pour envoyer la notification.');
                return;
            }

            // Préparer le message de notification
            $notification = Notification::create(
                'Nouvelle inscription de personnel',
                "Un nouveau personnel ({$user->nom} {$user->prenom}) s'est inscrit pour le service {$user->service_voulu}. Veuillez examiner."
            );

            // Envoyer la notification à l'admin
            $message = CloudMessage::withTarget('token', $adminUser->device_token)
                ->withNotification($notification)
                ->withData([
                    'user_id' => (string) $user->id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'service_voulu' => $user->service_voulu,
                    'type' => 'new_personnel_registration',
                ]);

            $this->messaging->send($message);
            \Log::info('Notification envoyée à l\'admin', [
                'admin_id' => $adminUser->id,
                'user_id' => $user->id,
                'service_voulu' => $user->service_voulu,
            ]);

        } catch (Exception $e) {
            \Log::error('Erreur lors de l\'envoi de la notification Firebase: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id,
            ]);
        }
    }

    public function countAdmin(): JsonResponse
    {
        $admin = 0;
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('admin')) {
                $admin++;
            }
        }
        return response()->json(
            [
                'admins' => $admin,
            ]
        );
    }

    public function checkRole($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'role' => $user->getRoleNames()->first(),
        ], 200);
    }
}

class PatientCodeGenerator
{
    /**
     * Génère un nouveau code patient unique au format PAT-XXXXXX.
     * Utilise une chaîne alphanumérique aléatoire pour éviter toute limite pratique.
     *
     * @return string Nouveau code patient (ex. PAT-A1B2C3)
     * @throws \Exception Si impossible de générer un code unique après plusieurs tentatives
     */
    public static function generatePatientCode(): string
    {
        return DB::transaction(function () {
            $maxAttempts = 10; // Nombre maximum de tentatives pour éviter une boucle infinie
            $codeLength = 6; // Longueur de la partie aléatoire (6 caractères)
            $prefix = 'PAT-'; // Préfixe du code

            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                // Générer une chaîne aléatoire de 6 caractères (lettres majuscules et chiffres)
                $randomPart = Str::random($codeLength);
                $randomPart = strtoupper(preg_replace('/[^A-Z0-9]/', '', $randomPart)); // Assurer A-Z, 0-9
                if (strlen($randomPart) < $codeLength) {
                    // Si la chaîne est trop courte (peu probable), compléter avec des chiffres
                    $randomPart .= str_pad(mt_rand(0, pow(10, $codeLength - strlen($randomPart)) - 1), $codeLength - strlen($randomPart), '0', STR_PAD_LEFT);
                }
                $newCode = $prefix . substr($randomPart, 0, $codeLength);

                // Vérifier si le code est unique
                if (!User::where('code_patient', $newCode)->exists()) {
                    return $newCode;
                }
            }

            throw new \Exception('Impossible de générer un code patient unique après ' . $maxAttempts . ' tentatives.');
        });
    }
}