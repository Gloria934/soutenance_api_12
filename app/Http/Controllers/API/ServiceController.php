<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Storage;
use App\Models\Service;



class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     //liste des services
    public function index()
    {
        $services = Service::all(); 
        return response()->json($services);
    }


    /**
     * Store a newly created resource in storage.
     */
    //cas d'utilisation: ajouter un service par l'admin
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email|unique:services,email',
            'profile_illustratif' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'prix_rdv' => 'nullable|numeric',
            'heure_ouverture' => 'nullable|date_format:H:i',
            'heure_fermeture' => 'nullable|date_format:H:i',
            'duree_moy_rdv' => 'nullable|date_format:H:i',
            'sous_rdv' => 'required|boolean',
        ]);
    
        $cheminImage = null;
    
        if ($request->hasFile('profile_illustratif')) {
            $cheminImage = $request->file('profile_illustratif')->store('services', 'public');
        }
    
        $service = Service::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'profile_illustratif' => $cheminImage,
            'prix_rdv' => $request->prix_rdv,
            'heure_ouverture' => $request->heure_ouverture,
            'heure_fermeture' => $request->heure_fermeture,
            'duree_moy_rdv' => $request->duree_moy_rdv,
            'sous_rdv' => $request->sous_rdv,
            'admin_id' =>Auth::id() , // admin connecté
        ]);
    
        return response()->json([
            'message' => 'Service créé avec succès.',
            'service' => $service,
            'image_url' => $cheminImage ? asset('storage/' . $cheminImage) : null,
        ]);
    }
    

    
    /**
     * Display the specified resource.
     */

    //afficher infos d'un service en particulier

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }
    

    /**
     * Update the specified resource in storage.
     */

     //cas d'utilisation: modifier service
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
    
        $request->validate([
            'nom' => 'nullable|string|max:255',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email|unique:services,email,' . $id,
            'profile_illustratif' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'prix_rdv' => 'nullable|numeric',
            'heure_ouverture' => 'nullable|date_format:H:i',
            'heure_fermeture' => 'nullable|date_format:H:i',
            'duree_moy_rdv' => 'nullable|date_format:H:i',
            'sous_rdv' => 'nullable|boolean',
        ]);
    
        if ($request->hasFile('profile_illustratif')) {
            $chemin = $request->file('profile_illustratif')->store('services', 'public');
            $service->profile_illustratif = $chemin;
        }
    
        // Ne pas autoriser la modification de l'admin_id
        $service->fill($request->except(['profile_illustratif', 'admin_id']));
        $service->save();
    
        return response()->json([
            'message' => 'Service mis à jour avec succès.',
            'service' => $service,
            'image_url' => $service->profile_illustratif ? asset('storage/' . $service->profile_illustratif) : null,
        ]);
    }
     

    /**
     * Remove the specified resource from storage.
     */
    //désactiver un service

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete(); // suppression logique
        return response()->json([
            'message' => 'Service désactivé avec succès.'
        ]);
    }


    //réactiver un service 

    public function restore($id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        $service->restore();
        return response()->json([
            'message' => 'Service réactivé avec succès.',
            'service' => $service
        ]);
    }


}
