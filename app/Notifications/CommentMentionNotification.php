<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentMentionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Comment $comment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->comment->loadMissing('post');
        $author = $this->comment->name;
        $postTitle = $this->comment->post->title;
        $url = route('posts.show', $this->comment->post).'#comments';

        return (new MailMessage)
            ->subject($author.' vous a mentionné sur '.config('app.name'))
            ->greeting('Bonjour '.$notifiable->name.' !')
            ->line($author.' vous a tagué (@mention) dans un commentaire sur l\'article « '.$postTitle.' ».')
            ->line('« '.Str::limit($this->comment->body, 200).' »')
            ->action('Voir le commentaire', $url)
            ->line('Vous pouvez répondre directement sous l\'article.');
    }
}
