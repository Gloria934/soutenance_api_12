<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors()));
    }


    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        // Vérifier que le nombre de tentatives n'est pas dépassé
        $this->ensureIsNotRateLimited();

        // Vérifier l'email
        $user = User::where('email', $this->input('email'))->first();

        if (!$user) {
            throw new HttpResponseException(response()->json([
                "error" => 'L\'adresse email saisie est invalide. Veuillez vérifier et réessayer.'
            ]));
        } else {
            // Connexion avec les informations de la request
            if (! Auth::attempt(
                $this->only('email', 'password'),
                $this->boolean('remember')
            )) {

                // Incrémenter le compteur de tentatives de connexions
                RateLimiter::hit($this->throttleKey());

                // Message d'erreur concernat le password si la connexion échoue
                throw new HttpResponseException(
                    response()->json([
                        "error" => 'Veuillez vérifier votre mot de passe et réessayer.'
                    ])
                );
            }

            // Reinitialiser le compteur de tentatives
            RateLimiter::clear($this->throttleKey());
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        // Bloccage de la connexion si trop de tentatives par minute
        event(new Lockout($this));

        // Récupération du nombre de secondes de deésactivation de la connexion
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw new HttpResponseException(
            response()->json([
                'error' => 'Trop de tentatives de connexion. Veuillez réessayer dans ' .  ceil($seconds / 60) . ' minute(s) et ' . $seconds . ' secondes.'
            ])
        );
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
