<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetSuccessNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre mot de passe a été changé avec succès')
            ->greeting('Bonjour ' . ($notifiable->prenom ?? 'Utilisateur') . ' 👋')
            ->line('Nous vous informons que votre mot de passe a été changé avec succès.')
            ->line('Si vous n\'êtes pas à l\'origine de cette modification, merci de contacter notre équipe de support.')
            ->salutation('Cordialement, L\'équipe Support');
    }
}
