<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;


class CustomVerifyEmail extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Indique que la notification sera envoyée par email
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Construction du message
        return (new MailMessage)
                    ->subject('Vérification de votre adresse email')
                    ->greeting('Bonjour ' . $notifiable->prenom . ',') // Utilisation du prénom
                    ->line('Merci de vous être inscrit sur notre plateforme. Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email.')
                    ->action('Vérifier mon adresse email', $this->verificationUrl($notifiable)) // Lien de vérification
                    ->line('Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer cet email.')
                    ->salutation('Cordialement, l\'équipe de notre application');
    }

    /**
     * Obtenez l'URL de vérification.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', // Nom de la route de vérification
            now()->addMinutes(60), // Validité du lien de vérification (ici 1 heure)
            ['id' => $notifiable->getKey()]
        );
    }
}
