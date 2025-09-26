<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Fourniture;
use App\Models\FournituresPatient;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;




class FournitureController extends Controller
{
    // Liste toutes les catégories
    public function index()
    {
        $fournitures = Fourniture::all();
        return response()->json([
            'status' => 200,
            'fournitures' => $fournitures
        ]);
    }

    // Crée une nouvelle catégorie
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'quantite' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',



        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 210,
                'errors' => $validator->messages()
            ], 210);
        }
        try {
            // Vérifiez si le fichier est reçu
            if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun fichier image valide reçu.',
                ], 213);
            }


            // Handle image upload
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('fournitures', $imageName, 'public'); // Stores in storage/app/public/fournitures
            $fourniture->image = 'storage/' . $path; // Path relative to public/storage



        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la fourniture: ' . $e->getMessage(),
            ], 500);
        }

        $fourniture = Fourniture::create([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'quantite' => $request->quantite,
            'image' => $imagePath,



        ]);

        return response()->json([
            'status' => 201,
            'message' => 'fourniture créé avec succès .',
            'fourniture' => $fourniture
        ], 201);
    }


    public function achatFourniture(Request $request)
    {
        $user = Auth::guard('api')->user();

        try {


            // Validate request data
            $validated = $request->validate([
                'fourniture_id' => ['required', 'numeric', 'min:0'],
                'montant' => ['required', 'numeric', 'min:0'],
                'quantite' => ['required', 'numeric', 'min:0'],
            ]);

            // Log incoming request
            Log::info('Incoming fourniture request', [
                'data' => $request->all(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            // Use database transaction to ensure data consistency
            return DB::transaction(function () use ($validated, $user) {
                // Update Fourniture Patient
                FournituresPatient::create([
                    'patient_id' => $user->id,
                    'montant' => $validated['montant'],
                    'fourniture_id' => $validated['fourniture_id'],
                    'code_fourniture' => "FRT-" . $this->generateCode(),
                    'quantite' => $validated['quantite'],
                    'livre' => false,
                ]);

                // Décrémentation de la quantité disponible de la  fourniture
                $fourniture = Fourniture::findOrFail($validated['fourniture_id']);
                if ($fourniture) {
                    $fourniture->quantite -= $validated['quantite'];
                }
                $fourniture->save();


                Log::info('Enregistrement terminé');

                return response()->json([
                    'message' => 'Achat effectué avec succès',
                ], 200);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error in achatFourniture', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 210);

        } catch (\Exception $e) {
            Log::error('Unexpected error in achatFourniture', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour',
                'error' => $e->getMessage()
            ], 208);
        }
    }
    public static function generateCode(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }
    // Affiche une catégorie spécifique
    public function show($id)
    {
        $fourniture = Fourniture::find($id);
        if ($fourniture) {
            return response()->json([
                'status' => 200,
                'fourniture' => $fourniture
            ]);
        } else {
            return response()->json([
                'status' => 210,
                'message' => 'fourniture non trouvé'
            ], 210);
        }
    }

    // Met à jour une catégorie
    public function update(Request $request, $id)
    {
        $fourniture = Fourniture::find($id);
        if (!$fourniture) {
            return response()->json([
                'status' => 210,
                'message' => 'fourniture non trouvé'
            ], 210);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'quantite' => 'required|numeric',

            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',


        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $fourniture->update([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'quantite' => $request->quantite,

        ]);

        return response()->json([
            'status' => 200,
            'message' => 'fourniture mis à jour avec succès',
            'fourniture' => $fourniture
        ], 200);
    }

    // Supprime une catégorie
    public function destroy($id)
    {
        $fourniture = Fourniture::find($id);
        if (!$fourniture) {
            return response()->json([
                'status' => 210,
                'message' => 'fourniture non trouvé'
            ], 210);
        }

        $fourniture->delete();
        return response()->json([
            'status' => 200,
            'message' => 'fourniture supprimé avec succès.'
        ]);
    }

    public function getUserFourniture()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $fournitures_patients = FournituresPatient::where('patient_id', $user->id)->where('livre', false)->with('fournitures')->get();
            if ($fournitures_patients) {
                return response()->json([
                    'message' => "succès",
                    "fournitures_patients" => $fournitures_patients,
                ], 200);
            }

        } else {
            return response()->json([
                'message' => "Cet utilisateur n'existe pas.",

            ], 210);
        }
    }
}
