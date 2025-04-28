<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Le lien de vérification n’est pas valide.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email déjà vérifié.']);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        // Optionnel : générer un token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Email vérifié avec succès.',
            'token' => $token,
            'user' => $user
        ]);
    }
}
