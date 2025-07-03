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

    public function fetchAllPersonnel()
    {
        $personnels = User::whereNotNull('role_voulu')->get();
        $personnels = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'patient', 'pending']);
        })->with('roles:name')->get();
        // $personnels = User::with('roles:name')->get();

        $personnels = User::whereNotNull('role_voulu')->get();
        $personnels = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'pending', 'patient']);
        })->with('roles:name')->get();
        if ($personnels != null) {
            return response()->json([
                'message' => 'Réussie',
                'personnels' => $personnels,
            ], 200);
        }
    }
    public function scanUser()
    {
        $dani = User::with('roles:name')->get();
        return response()->json(
            [
                'utilisateur' => $dani,
            ],
            200

        );
    }
    // Les membres du personnel sont des gens qui 

    // public function fetchAllPersonnel()
    // {
    //     try {
    //         $personnels = User::whereDoesntHave('roles', function ($query) {
    //             $query->whereIn('name', ['admin', 'patient', 'pending']);
    //         })->with('roles:name')->get();

    //         // Synchroniser le champ role avec roles
    //         foreach ($personnels as $user) {
    //             $user->role = $user->roles->isNotEmpty() ? $user->roles->first()->name : null;
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => $personnels, // Utiliser 'data' au lieu de '$personnels'
    //             'message' => 'Personnels récupérés avec succès.'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la récupération des personnels.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

}
