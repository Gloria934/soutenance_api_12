<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index(): JsonResponse
    {
        $services = Service::withTrashed()->get();

        return response()->json([
            'success' => true,
            'data' => $services,
            'message' => 'Services retrieved successfully'
        ], 200);
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:services,email|max:255',
            'prix_rdv' => 'nullable|numeric|min:0',
            'nom_medecin' => 'nullable|string',
            'heure_ouverture' => 'nullable|date_format:H:i',
            'heure_fermeture' => 'nullable|date_format:H:i|after:heure_ouverture',
            'duree_moy_rdv' => 'nullable|string|max:50',
            'sous_rdv' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $service = Service::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Service created successfully'
        ], 201);
    }

    /**
     * Display the specified service.
     */
    public function show($id): JsonResponse
    {
        $service = Service::withTrashed()->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Service retrieved successfully'
        ], 200);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $service = Service::withTrashed()->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:services,email,' . $id . '|max:255',
            'nom_medecin' => 'nullable|string',
            'prix_rdv' => 'nullable|numeric|min:0',
            'heure_ouverture' => 'nullable|date_format:H:i',
            'heure_fermeture' => 'nullable|date_format:H:i|after:heure_ouverture',
            'duree_moy_rdv' => 'nullable|string|max:50',
            'sous_rdv' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $service->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Service updated successfully'
        ], 200);
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($id): JsonResponse
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ], 200);
    }

    /**
     * Restore a soft-deleted service.
     */
    public function restore($id): JsonResponse
    {
        $service = Service::onlyTrashed()->find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found or not deleted'
            ], 404);
        }

        $service->restore();

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Service restored successfully'
        ], 200);
    }

    public function rendezVousPourServicePrecis()
    {
        $user = Auth::guard('api')->user();
        if ($user->hasRole('service_medical')) {
            $rdvs = RendezVous::where('service_id', $user->service_voulu)->whereNull('specialiste_id')->with('patient', 'service')->whereNull('date_rdv')->get();
            return response()->json([
                'success' => true,
                'rdvs' => $rdvs,
                'message' => 'endez-vous récupérés avec succès'
            ], 200);
        }
    }

    public function editerRendezVous(Request $request)
    {
        $rdv = RendezVous::findOrFail($request->appointment_id);
        if ($rdv) {
            $rdv->date_rdv = $request->date_rdv;
            $rdv->save();

            return response()->json([
                'success' => true,
                'data' => $rdv,
                'message' => 'Rendez-vous modifié avec succès'
            ], 200);

        }
    }

}