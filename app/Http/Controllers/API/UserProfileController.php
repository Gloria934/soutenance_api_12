<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function update(Request $request)
{
    $user = $request->user();

    $request->validate([
        'image_profile' => 'nullable|image|max:2048',
        'name' => 'sometimes|string|max:255',
        // tu peux ajouter d’autres champs modifiables ici
    ]);

    if ($request->hasFile('image_profile')) {
        $path = $request->file('image_profile')->store('profiles', 'public');
        $user->image_profile = $path;
    }

    if ($request->filled('name')) {
        $user->name = $request->name;
    }

    $user->save();

    return response()->json([
        'message' => 'Profil mis à jour avec succès',
        'user' => $user,
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
