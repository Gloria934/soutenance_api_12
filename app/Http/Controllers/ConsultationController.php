<?php

namespace App\Http\Controllers;

use App\Enums\StatutEnum;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        $consultations = RendezVous::whereNull('date_rdv')->where('patient_id',$user->id)->with('patient', 'service')->get();

        return response()->json([
            'message'=>'succès',
            'consultations'=>$consultations,
        ],200);
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

     public function updateConsultation(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'consultation_id' => ['required', 'numeric', 'min:0'],
            ]);

            // Log incoming request
            Log::info('Incoming updateConsultation request', [
                'data' => $request->all(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            // Use database transaction to ensure data consistency
            return DB::transaction(function () use ($validated) {
                // Update Ordonnance
                RendezVous::findOrFail($validated['consultation_id'])->update(['statut' => StatutEnum::CONFIRME->value]);
                // $consultation->statut = 
                // $ordonnance->save();

                Log::info('Consultation updated successfully');

                return response()->json([
                    'message' => 'Mise à jour effectuée avec succès',
                ], 200);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error in updateConsultation', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Unexpected error in updateConsultation', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }
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
