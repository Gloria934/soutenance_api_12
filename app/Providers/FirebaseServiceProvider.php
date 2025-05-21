<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Enregistre le service Firebase Auth dans le container
        $this->app->singleton(FirebaseAuth::class, function ($app) {
            return (new Factory)
                ->withServiceAccount(storage_path('firebase/credentials.json'))
                ->createAuth();
        });
    }

    public function boot()
    {
        // Rien à faire ici pour Firebase
    }
}
