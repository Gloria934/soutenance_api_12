<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Veuillez fournir votre adresse email.',
            'email.email' => 'L\'adresse email fournie n\'est pas valide.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Impossible d\'envoyer le lien de réinitialisation. Veuillez vérifier votre adresse email.',
            ], 400); // 400 = Bad Request
        }

        return response()->json([
            'message' => 'Un lien de réinitialisation de mot de passe a été envoyé à votre adresse email.',
        ]);
    }
}
