<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnels = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'patient', 'pending']);
        })->with('roles:name')->get();

        return response()->json([
            'success' => true,
            'data' => $personnels,
            'message' => 'Personnels récupéré avec succès.'
        ], 200);
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|string']);
        $user = User::findOrFail($id);
        $user->syncRoles([$request->role]);
        return response()->json([
            'success' => true,
            'message' => 'Rôle mis à jour avec succès.'
        ], 200);
    }


}
