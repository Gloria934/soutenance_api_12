<?php

// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use App\Models\SimpleNotification;
use App\Models\User;
use Illuminate\Http\Request;

class SimpleNotificationController extends Controller
{
    public function index()
    {
        $notifications = SimpleNotification::with('personnelSante')->where('status', 'pending')->get();
        return response()->json([
            'success' => true,
            'data' => $notifications,
            'message' => 'Notifications récupérées avec succès.',
        ], 200);
    }

    public function accept(Request $request, $id)
    {
        $notification = SimpleNotification::findOrFail($id);
        $user = $notification->personnelSante;

        // Assigner le rôle demandé
        if ($user->role_voulu) {
            // $user->roles()->detash();
            $user->syncRoles([$user->role_voulu]);
            $user->save();
            $this->sendOtpViaOneSignal($user->device_token, "Votre demande a été approuvée avec succès...");
        }

        // Mettre à jour le statut de la notification
        $notification->status = 'accepted';
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Demande acceptée avec succès.',
        ], 200);
    }

    public function reject(Request $request, $id)
    {
        $notification = SimpleNotification::findOrFail($id);
        $notification->status = 'rejected';
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Demande rejetée avec succès.',
        ], 200);
    }

    public function destroy($id)
    {
        $notification = SimpleNotification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée avec succès.',
        ], 200);
    }

    private function sendOtpViaOneSignal(string $playerId, string $message)
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
            "contents" => ["en" => $message],
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