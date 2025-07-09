<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $roles = Role::all();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $roles,
    //         'message' => 'Rôles récupérés avec succès.'
    //     ], 200);
    // }
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json([
            'success' => true,
            'data' => $roles,
            'message' => 'Rôles récupérés avec succès.'
        ], 200);
    }

    // Récupérer les rôles et permissions d'un utilisateur spécifique
    public function getUserRolesAndPermissions($userId)
    {
        $user = User::findOrFail($userId);
        $roles = $user->roles()->pluck('name');
        $permissions = $user->getAllPermissions()->pluck('name');
        return response()->json([
            'success' => true,
            'data' => [
                'roles' => $roles,
                'permissions' => $permissions,
            ],
            'message' => 'Rôles et permissions de l\'utilisateur récupérés avec succès.'
        ], 200);
    }

    // Mettre à jour le rôle d'un utilisateur
    public function updatePersonnelRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
            'service_id' => 'nullable|integer|exists:services,id',
        ]);

        $user = User::findOrFail($userId);
        $user->syncRoles([$request->role]);

        // Mettre à jour le service_id si fourni
        if ($request->role === 'service_medical' && $request->service_id) {
            $user->service_id = $request->service_id;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Rôle mis à jour avec succès.'
        ], 200);
    }

    // Attribuer des permissions à un utilisateur
    public function assignPermissions(Request $request, $userId)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user = User::findOrFail($userId);
        $user->syncPermissions($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permissions attribuées avec succès.'
        ], 200);
    }

    // Récupérer toutes les permissions disponibles
    public function getPermissions()
    {
        $permissions = Permission::all()->pluck('name');
        return response()->json([
            'success' => true,
            'data' => $permissions,
            'message' => 'Permissions récupérées avec succès.'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
