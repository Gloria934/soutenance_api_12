<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
<<<<<<< HEAD
     *
     * @return void
     */
    public function register()
=======
    public function register(): void
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
    {
        //
<<<<<<< HEAD
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
=======
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
>>>>>>> 52264222fe4f6359aa16adf4e0a08ebf53ee3ee1
    }
}
