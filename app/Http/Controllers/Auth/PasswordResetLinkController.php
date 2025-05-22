<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'email' => ['nullable', 'string', 'email'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ]);

        // Vérifier qu'au moins un champ est fourni
        if (!$request->filled('email') && !$request->filled('telephone')) {
            throw ValidationException::withMessages([
                'email' => ['Un e-mail ou un numéro de téléphone est requis.'],
                'telephone' => ['Un e-mail ou un numéro de téléphone est requis.'],
            ]);
        }

        // Trouver l'utilisateur par e-mail ou téléphone
        $user = null;
        if ($request->filled('email')) {
            $user = User::where('email', $request->email)->first();
        } elseif ($request->filled('telephone')) {
            $user = User::where('telephone', $request->telephone)->first();
        }

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => $request->filled('email') ? [trans('passwords.user')] : null,
                'telephone' => $request->filled('telephone') ? ['Aucun utilisateur trouvé avec ce numéro de téléphone.'] : null,
            ]);
        }

        // Envoi du lien de réinitialisation par e-mail si l'e-mail est fourni
        $emailStatus = null;
        if ($request->filled('email')) {
            $emailStatus = Password::sendResetLink(['email' => $user->email]);
        }

        // Générer un code d'authentification pour SMS si le téléphone est fourni ou disponible
        $smsStatus = false;
        $smsCode = null;
        if ($user->telephone) {
            $smsCode = Str::random(6); // Code à 6 caractères
            $token = Str::random(60); // Jeton pour la réinitialisation

            // Stocker le jeton et le code SMS dans password_reset_tokens
            \DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'token' => hash('sha256', $token),
                    'sms_code' => $smsCode,
                    'created_at' => now(),
                ]
            );

            $smsStatus = $this->sendSmsCode($user->telephone, $smsCode);
        }

        // Vérifier les statuts des envois
        if (($emailStatus == Password::RESET_LINK_SENT || !$request->filled('email')) && ($smsStatus || !$user->telephone)) {
            return response()->json([
                'message' => 'Réinitialisation demandée avec succès.',
                'sms_code' => $smsCode, // À supprimer en production
            ], 200);
        }

        // Gérer les erreurs
        $errors = [];
        if ($emailStatus && $emailStatus != Password::RESET_LINK_SENT) {
            $errors['email'] = [trans($emailStatus)];
        }
        if ($user->telephone && !$smsStatus) {
            $errors['sms'] = ['Échec de l\'envoi du code par SMS.'];
        }

        throw ValidationException::withMessages($errors);
    }

    /**
     * Envoyer un code d'authentification par SMS.
     *
     * @param string $phoneNumber
     * @param string $code
     * @return bool
     */
    protected function sendSmsCode($phoneNumber, $code): bool
    {
        try {
            // Exemple avec Twilio (remplacer par Firebase si nécessaire)
            $client = new \Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.auth_token')
            );

            $client->messages->create($phoneNumber, [
                'from' => config('services.twilio.phone_number'),
                'body' => "Votre code de réinitialisation est : $code",
            ]);

            \Log::info("Code $code envoyé au numéro $phoneNumber via Twilio.");
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du SMS : ' . $e->getMessage());
            return false;
        }
    }
}