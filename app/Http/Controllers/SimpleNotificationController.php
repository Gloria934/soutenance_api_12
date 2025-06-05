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
            $user->assignRole([$user->role_voulu]);
            $user->save();
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
}