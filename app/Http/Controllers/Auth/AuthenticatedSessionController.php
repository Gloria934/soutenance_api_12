<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'telephone' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);

            if (!Auth::attempt($request->only('telephone', 'password'))) {
                return response()->json([
                    'success' => false,
                    'error' => 'authentication_failed',
                    'message' => trans('auth.failed'),
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'validation_error',
                'message' => 'Les données fournies sont invalides',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'server_error',
                'message' => 'Une erreur serveur est survenue',
                'details' => $e->getMessage(),
            ], 500);
        }
    }





    public function verifyUserNumber(Request $request): JsonResponse
    {
        try {
            $user = User::where('telephone', $request->telephone)->first();
            if ($user) {
                // Generate a password reset token
                $token = Password::createToken($user);

                return response()->json([
                    'message' => 'Utilisateur Existant.',
                    'user' => $user,
                    'reset_token' => $token,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Cet utilisateur n\'est pas enregistré.'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'server_error',
                'message' => 'Une erreur est survenue lors de la vérification.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'unauthenticated',
                    'message' => 'Non authentifié',
                ], 401);
            }

            $user->tokens()->delete();
            Auth::guard('sanctum')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'server_error',
                'message' => 'Une erreur est survenue lors de la déconnexion',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}