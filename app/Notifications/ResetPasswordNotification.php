<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Réinitialisation de ton mot de passe — MelanoGeek')
            ->greeting('Bonjour !')
            ->line('Tu as demandé à réinitialiser le mot de passe de ton compte MelanoGeek.')
            ->action('Réinitialiser mon mot de passe', $this->resetUrl($notifiable))
            ->line('Ce lien expirera dans 60 minutes.')
            ->line('Si tu n\'as pas demandé cette réinitialisation, ignore cet email — ton mot de passe reste inchangé.');
    }
}
