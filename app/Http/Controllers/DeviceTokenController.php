<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DeviceTokenController extends Controller
{
    /**
     * Met à jour le device_token d'un utilisateur.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDeviceToken(Request $request): JsonResponse
    {
        try {
            // Valider les données entrantes
            $validatedData = $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'device_token' => ['required', 'string'],
            ]);

            // Récupérer l'utilisateur par son ID
            $user = User::findOrFail($validatedData['user_id']);

            // Mettre à jour le device_token
            $user->device_token = $validatedData['device_token'];
            $user->save();

            // Retourner une réponse de succès
            return response()->json([
                'message' => 'Device token mis à jour avec succès',
                'user_id' => $user->id,
                'device_token' => $user->device_token,
            ], 200);

        } catch (ValidationException $e) {
            // Gérer les erreurs de validation
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Gérer les autres erreurs (ex. utilisateur non trouvé, problème de base de données)
            Log::error('Erreur lors de la mise à jour du device token: ' . $e->getMessage(), [
                'user_id' => $request->input('user_id'),
                'exception' => $e,
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour du device token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}