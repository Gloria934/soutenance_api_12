<?php
namespace App\Http\Controllers;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['patient', 'medicamentsPrescrits.medicament.dci'])->get();
        return response()->json([
            'success' => true,
            'prescriptions' => $prescriptions
        ], 200);
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_id' => 'required|exists:services,id',
            'montant' => 'required|numeric',
            'date_ordonnance' => 'required|date',
            'medicaments_prescrits' => 'required|array',
            'medicaments_prescrits.*.medicament_id' => 'required|exists:pharmaceutical_products,id',
            'medicaments_prescrits.*.posologie' => 'required|string',
            'medicaments_prescrits.*.duree' => 'required|string',
            'medicaments_prescrits.*.quantite' => 'required|string',
            'medicaments_prescrits.*.substitution_autorisee' => 'required|boolean',
            'medicaments_prescrits.*.statut' => 'required|boolean',
            'medicaments_prescrits.*.avis' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'patient_id' => $validated['patient_id'],
            'service_id' => $validated['service_id'],
            'montant' => $validated['montant'],
            'date_ordonnance' => $validated['date_ordonnance'],
        ]);

        foreach ($validated['medicaments_prescrits'] as $medicament) {
            $prescription->medicamentsPrescrits()->create($medicament);
        }

        return response()->json([
            'success' => true,
            'prescription' => $prescription->load('medicamentsPrescrits.medicament'),
        ], 201);
    }

    public function getPatients()
    {
        return response()->json([
            'success' => true,
            'patients' => \App\Models\Patient::all(),
        ], 200);
    }

    public function getServices()
    {
        return response()->json([
            'success' => true,
            'services' => \App\Models\Service::all(),
        ], 200);
    }


    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete();
        return response()->json([
            'success' => true,
            'message' => 'Ordonnance supprimée'
        ], 200);
    }

    public function deletePaid()
    {
        Prescription::whereHas('medicamentsPrescrits', function ($query) {
            $query->where('status', true);
        }, '=', function ($query) {
            $query->count();
        })->delete();
        return response()->json([
            'success' => true,
            'message' => 'Ordonnances payées supprimées'
        ], 200);
    }

    public function updateMedicament(Request $request, $id, $medicamentId)
    {
        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);

        $prescription = Prescription::findOrFail($id);
        $medicamentPrescrit = $prescription->medicamentsPrescrits()->where('medicament_id', $medicamentId)->firstOrFail();
        $medicamentPrescrit->status = $validated['status'];
        $medicamentPrescrit->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour'
        ], 200);
    }
}