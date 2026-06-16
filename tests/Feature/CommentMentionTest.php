<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentMentionNotification;
use App\Services\CommentMentionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CommentMentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_with_mention_notifies_tagged_user(): void
    {
        Notification::fake();

        $author = User::factory()->create(['name' => 'Parfait', 'email' => 'parfaitgbedo18@gmail.com']);
        $mentioned = User::factory()->create(['name' => 'Akim', 'email' => 'akim.test@gmail.com']);
        $post = Post::create([
            'user_id' => $author->id,
            'title' => 'Article test',
            'slug' => 'article-test',
            'content' => 'Contenu test',
            'published' => true,
        ]);

        $this->actingAs($author)
            ->post(route('comments.store', $post), [
                'body' => 'Bravo @akim pour ton commentaire !',
            ])
            ->assertRedirect();

        $comment = Comment::first();
        $this->assertNotNull($comment);
        $this->assertTrue($comment->mentionedUsers->contains('id', $mentioned->id));

        Notification::assertSentTo($mentioned, CommentMentionNotification::class);
        Notification::assertNotSentTo($author, CommentMentionNotification::class);
    }

    public function test_mention_is_highlighted_in_formatted_body(): void
    {
        $service = app(CommentMentionService::class);
        $formatted = $service->formatBody('Salut @akim !');

        $this->assertStringContainsString('comment-mention', $formatted);
        $this->assertStringContainsString('@akim', $formatted);
    }

    public function test_user_can_update_own_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Article test',
            'slug' => 'article-test-update',
            'content' => 'Contenu',
            'published' => true,
        ]);
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'body' => 'Ancien texte',
            'approved' => true,
        ]);

        $this->actingAs($user)
            ->patch(route('comments.update', [$post, $comment]), ['body' => 'Texte modifié'])
            ->assertRedirect(route('posts.show', $post).'#comments');

        $this->assertSame('Texte modifié', $comment->fresh()->body);
    }

    public function test_duplicate_comment_within_one_minute_is_blocked(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Article dup',
            'slug' => 'article-dup',
            'content' => 'Contenu',
            'published' => true,
        ]);

        $payload = ['body' => 'Commentaire unique'];

        $this->actingAs($user)->post(route('comments.store', $post), $payload);
        $this->actingAs($user)->post(route('comments.store', $post), $payload);

        $this->assertSame(1, Comment::where('post_id', $post->id)->count());
    }
}
