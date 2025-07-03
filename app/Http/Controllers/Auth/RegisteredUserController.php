<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Berkayk\OneSignal\OneSignalClient;
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
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\OneSignal;


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

    public function nextId()
    {
        $nextId = User::all()->count() + 1;
        return response()->json([
            'message' => 'réussi',
            'nextId' => $nextId,
        ]);
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



    // public function sendOtpViaOneSignal(string $playerId)
    // {
    //     $url = "https://onesignal.com/api/v1/notifications";

    //     $headers = [
    //         "Authorization: Basic " . env("ONESIGNAL_REST_API_KEY"),
    //         "Content-Type: application/json"
    //     ];

    //     $data = [
    //         "app_id" => env("ONESIGNAL_APP_ID"),
    //         "include_player_ids" => [$playerId],
    //         "headings" => ["en" => "MEDIPAY"],
    //         "contents" => ["en" => "Une nouvelle inscription requiert votre attention..."],
    //         "priority" => 10,
    //     ];


    //     $ch = curl_init();

    //     curl_setopt_array($ch, [
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => json_encode($data),
    //         CURLOPT_HTTPHEADER => $headers,
    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_SSL_VERIFYHOST => false,
    //     ]);

    //     $response = curl_exec($ch);

    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //     curl_close($ch);


    //     return [
    //         'status' => $httpCode,
    //         'response' => $response
    //     ];
    // }

    // public function sendPushNotification(User $user, $title, $message)
    // {
    //     $playerId = $user->device_token;

    //     if (!$playerId) {
    //         return response()->json(['error' => 'Aucun player ID enregistré'], 400);
    //     }

    //     $fields = [
    //         'app_id' => env('ONESIGNAL_APP_ID'),
    //         'include_player_ids' => [$playerId],
    //         'headings' => ['en' => $title],
    //         'contents' => ['en' => $message],
    //     ];

    //     $client = new \GuzzleHttp\Client();
    //     $response = $client->post('https://onesignal.com/api/v1/notifications', [
    //         'headers' => [
    //             'Content-Type' => 'application/json',
    //             'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
    //         ],
    //         'json' => $fields,
    //     ]);

    //     $body = json_decode($response->getBody(), true);
    //     \Log::info('OneSignal Response', $body);
    //     return $body;
    // }

    private function sendOtpViaOneSignal(string $playerId)
    {
        $url = "https://onesignal.com/api/v1/notifications";

        $apiKey = env("ONESIGNAL_REST_API_KEY");
        $appId = env("ONESIGNAL_APP_ID");

        $headers = [
            "Authorization: Basic " . $apiKey,

            "Content-Type: application/json"
        ];

        $data = [
            "app_id" => $appId,
            "include_player_ids" => [$playerId],
            "headings" => ["en" => "mediPay"],
            "contents" => ["en" => "Une nouvelle inscription requiert votre attention..."],
            "priority" => 10,
        ];

        \Log::info('OneSignal Notification - Data to send', $data);
        \Log::info('OneSignal Notification - Headers', $headers);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            \Log::error('OneSignal Notification - CURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        \Log::info('OneSignal Notification - HTTP Code', ['httpCode' => $httpCode]);
        \Log::info('OneSignal Notification - Response', ['response' => $response]);

        return [
            'status' => $httpCode,
            'response' => $response
        ];
    }



}
