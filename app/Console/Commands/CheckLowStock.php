<?php

namespace App\Console\Commands;
use App\Models\PharmaceuticalProduct;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check-low';
    protected $description = 'Check medicaments with stock below 10 and send Firebase notifications';

    public function handle(): void
    {
        $lowStockMedicaments = PharmaceuticalProduct::where('stock', '<', 10)->get();

        if ($lowStockMedicaments->isEmpty()) {
            $this->info('No medicaments with low stock found.');
            return;
        }

        $users = User::whereNotNull('device_token')->get();
        $messaging = Firebase::messaging();

        foreach ($lowStockMedicaments as $medicament) {
            $cacheKey = "low_stock_notified_{$medicament->id}";
            if (!Cache::has($cacheKey)) {
                foreach ($users as $user) {
                    $message = CloudMessage::withTarget('token', $user->device_token)
                        ->withNotification(Notification::create(
                            title: 'Alerte de Stock Faible',
                            body: "Le stock de {$medicament->nom_produit} est inférieur à 10 (actuel: {$medicament->stock})."
                        ))
                        ->withData([
                            'medicament_id' => (string) $medicament->id,
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        ]);

                    try {
                        $messaging->send($message);
                        $this->info("Notification sent to user {$user->id} for {$medicament->nom_produit}");
                    } catch (\Exception $e) {
                        $this->error("Failed to send notification to user {$user->id}: {$e->getMessage()}");
                    }
                }

                Cache::put($cacheKey, true, now()->addHours(24));
            }
        }

        $this->info('Low stock check completed. Notified for ' . $lowStockMedicaments->count() . ' medicaments.');
    }
}