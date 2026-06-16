<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $otp,
        public string $userName,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expire = (int) config('blog.registration_otp.expire_minutes', 15);

        return (new MailMessage)
            ->subject('Code de confirmation — '.config('app.name'))
            ->greeting('Bonjour '.$this->userName.' !')
            ->line('Vous avez demandé la création d\'un compte sur '.config('app.name').'.')
            ->line('Votre code de confirmation : **'.$this->otp.'**')
            ->line('Ce code expire dans '.$expire.' minutes.')
            ->line('Si vous n\'êtes pas à l\'origine de cette inscription, ignorez cet email.');
    }
}
