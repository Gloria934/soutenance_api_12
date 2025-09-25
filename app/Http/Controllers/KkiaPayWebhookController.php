<?php
// app/Http/Controllers/KkiaPayWebhookController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RendezVous; // Assurez-vous d'avoir ce modèle
use App\Enums\StatutEnum;
use Illuminate\Support\Facades\Log;

class KkiaPayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info("Début de la fonction de mise à jour du statut de la consultation");
        // 1. VÉRIFICATION DE LA SIGNATURE (TRÈS IMPORTANT)
        $privateKey = config(
            'services.kkiapay.private_key'
        ); // Stockez votre clé privée dans les configs
        $signature = $request->header(
            'X-Kkiapay-Signature'
        );

        $computedSignature = hash_hmac('sha256', $request
            ->getContent(), $privateKey);

        if (!hash_equals($computedSignature, $signature)) {
            // Signature invalide, requête non authentique
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature'
            ], 401);
        }

        // 2. La signature est valide, on traite les données
        $data = $request->all();
        $transactionId = $data['transactionId'];
        $status = $data['status']; // 'SUCCESS', 'FAILED',etc.

        // Récupérez l'ID de la consultation que vous avez envoyé à KkiaPay
        // via le champ "metadata" lors de l'initialisation du paiement.
        $consultationId = $data['metadata'][
            'consultation_id'] ?? null;

        if ($consultationId) {
            if ($status === 'SUCCESS') {
                RendezVous::findOrFail($consultationId)->update(['statut' => StatutEnum::CONFIRME->value]);
            }
            // $consultation = RendezVous::find(
            //     $consultationId
            // );

            // if ($consultation && $status === 'SUCCESS') {
            //     // 3. Mettre à jour le statut dans votre base de données
            //     $consultation->statut = 'confirme';
            //     $consultation->transaction_id =
            //         $transactionId; // Sauvegardez l'ID de transaction
            //     $consultation->save();
            // }
        }
        Log::info("Fin de la fonction de mise à jour du statut de la consultation");

        // 4. Répondre à KkiaPay pour accuser réception
        return response()->json(['status' => 'success']);
    }
}


