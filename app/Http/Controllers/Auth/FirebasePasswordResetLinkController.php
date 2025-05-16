<?php

// ce controller va effectuer une demande de réinitialisation 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use App\Models\User;

class FirebasePasswordResetLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $auth = app('firebase.auth');
            $auth->sendPasswordResetLink($request->email);
            
            // Optionnel : Log dans Laravel
            User::where('email', $request->email)->update([
                'password_reset_requested_at' => now()
            ]);

            return response()->json(['status' => 'reset-link-sent']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
