<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — '.config('app.name'))
            ->greeting('Bonjour '.$notifiable->name.' !')
            ->line('Vous recevez cet email car une demande de réinitialisation de mot de passe a été effectuée pour votre compte sur '.config('app.name').'.')
            ->action('Réinitialiser mon mot de passe', $url)
            ->line('Ce lien expire dans '.$expire.' minutes.')
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, ignorez simplement cet email — votre mot de passe restera inchangé.');
    }
}
