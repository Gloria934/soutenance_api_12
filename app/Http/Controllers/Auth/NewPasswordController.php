<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'email' => ['nullable', 'string', 'email'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'token' => ['nullable', 'string'],
            'sms_code' => ['nullable', 'string', 'size:6'],
        ]);

        // Vérifier qu'au moins un identifiant est fourni
        if (!$request->filled('email') && !$request->filled('telephone')) {
            throw ValidationException::withMessages([
                'email' => ['Un e-mail ou un numéro de téléphone est requis.'],
                'telephone' => ['Un e-mail ou un numéro de téléphone est requis.'],
            ]);
        }

        // Vérifier qu'un jeton ou un code SMS est fourni
        if (!$request->filled('token') && !$request->filled('sms_code')) {
            throw ValidationException::withMessages([
                'token' => ['Un jeton ou un code SMS est requis.'],
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

        // Vérifier le jeton ou le code SMS
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->orWhere('telephone', $user->telephone)
            ->first();

        if (!$resetRecord) {
            throw ValidationException::withMessages([
                'email' => $request->filled('email') ? ['Aucune demande de réinitialisation trouvée pour cet e-mail.'] : null,
                'telephone' => $request->filled('telephone') ? ['Aucune demande de réinitialisation trouvée pour ce numéro de téléphone.'] : null,
            ]);
        }

        $isValid = false;

        // Vérification du jeton (pour l'e-mail)
        if ($request->filled('token')) {
            $status = Password::reset(
                [
                    'email' => $user->email,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'token' => $request->token,
                ],
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                $isValid = true;
            }
        }

        // Vérification du code SMS
        if ($request->filled('sms_code') && $resetRecord->sms_code === $request->sms_code) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
            $isValid = true;
        }

        // Si la réinitialisation est valide, connecter l'utilisateur et générer un jeton
        if ($isValid) {
            \DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->orWhere('telephone', $user->telephone)
                ->delete();

            // Connecter automatiquement l'utilisateur
            Auth::login($user);

            // Générer un jeton d'authentification
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Mot de passe réinitialisé avec succès. Utilisateur connecté.',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        throw ValidationException::withMessages([
            'token' => $request->filled('token') ? [trans('passwords.token')] : null,
            'sms_code' => $request->filled('sms_code') ? ['Code SMS invalide.'] : null,
        ]);
    }
}