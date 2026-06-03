<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenue sur ' . config('app.name') . ' ! 🎉')
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Bienvenue sur mon blog personnel ! 🌟')
            ->line('Merci de vous être inscrit(e). Vous pouvez maintenant :')
            ->line('✓ Lire tous mes articles')
            ->line('✓ Commenter et partager vos réflexions')
            ->line('✓ Liker vos articles préférés')
            ->line('✓ Sauvegarder vos favoris')
            ->action('Découvrir les articles', route('posts.index'))
            ->line('À très bientôt sur le blog !')
            ->salutation('Cordialement, ' . config('app.name'));
    }
}
