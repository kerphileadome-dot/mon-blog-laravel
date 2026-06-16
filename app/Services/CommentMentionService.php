<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentMentionNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CommentMentionService
{
    public function extractHandles(string $body): array
    {
        preg_match_all('/@([\p{L}\p{N}_.-]+)/u', $body, $matches);

        $handles = array_map('strtolower', $matches[1] ?? []);

        return array_values(array_unique($handles));
    }

    /**
     * @return Collection<int, User>
     */
    public function resolveUsers(array $handles): Collection
    {
        if ($handles === []) {
            return collect();
        }

        $visitors = User::query()
            ->where('role', 'visitor')
            ->where('blocked', false)
            ->get();

        $resolved = collect();

        foreach ($handles as $handle) {
            $user = $visitors->first(fn (User $user) => $this->matchesHandle($user, $handle));

            if ($user !== null && ! $resolved->contains('id', $user->id)) {
                $resolved->push($user);
            }
        }

        return $resolved;
    }

    public function syncMentions(Comment $comment, string $body): Collection
    {
        $handles = $this->extractHandles($body);
        $users = $this->resolveUsers($handles);

        $comment->mentionedUsers()->detach();

        foreach ($users as $user) {
            $handle = $this->primaryHandle($user);
            $comment->mentionedUsers()->attach($user->id, ['handle' => $handle]);
        }

        return $users;
    }

    public function notifyMentionedUsers(Comment $comment): void
    {
        if ($comment->mentions_notified_at !== null || ! $comment->approved) {
            return;
        }

        if ($this->mailNotConfigured()) {
            return;
        }

        $comment->loadMissing(['post', 'mentionedUsers', 'user']);

        $recipients = $comment->mentionedUsers
            ->reject(fn (User $user) => $user->id === $comment->user_id);

        if ($recipients->isEmpty()) {
            $comment->forceFill(['mentions_notified_at' => now()])->save();

            return;
        }

        foreach ($recipients as $user) {
            Notification::send($user, new CommentMentionNotification($comment));
        }

        $comment->forceFill(['mentions_notified_at' => now()])->save();
    }

    public function processNewComment(Comment $comment, string $body, bool $approved): void
    {
        $this->syncMentions($comment, $body);

        if ($approved) {
            $this->notifyMentionedUsers($comment);
        }
    }

    public function formatBody(string $body): string
    {
        $escaped = e($body);

        return preg_replace(
            '/@([\p{L}\p{N}_.-]+)/u',
            '<span class="comment-mention">@$1</span>',
            $escaped
        ) ?? $escaped;
    }

    public function primaryHandle(User $user): string
    {
        $firstName = Str::before(trim($user->name), ' ');

        return Str::lower($firstName !== '' ? $firstName : Str::before($user->email, '@'));
    }

    protected function matchesHandle(User $user, string $handle): bool
    {
        $handle = Str::lower($handle);
        $candidates = array_filter([
            Str::lower(Str::before($user->email, '@')),
            Str::lower(Str::before(trim($user->name), ' ')),
            Str::lower(str_replace(' ', '', $user->name)),
            Str::lower(str_replace(' ', '.', $user->name)),
        ]);

        return in_array($handle, $candidates, true);
    }

    protected function mailNotConfigured(): bool
    {
        $mailer = config('mail.default');

        if (in_array($mailer, ['log', 'array'], true)) {
            return app()->environment('production');
        }

        if ($mailer === 'smtp') {
            return blank(config('mail.mailers.smtp.username'))
                || blank(config('mail.mailers.smtp.password'));
        }

        return false;
    }
}
