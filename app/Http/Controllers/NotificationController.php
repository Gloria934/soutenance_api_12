<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function saveDeviceToken(Request $request): JsonResponse
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->device_token = $request->device_token;
        $user->save();

        return response()->json(['message' => 'Device token saved successfully'], 200);
    }
}