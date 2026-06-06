<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class BlogSettings
{
    protected string $settingsFile;

    public function __construct()
    {
        $this->settingsFile = storage_path('app/blog_settings.json');
    }

    public function all(): array
    {
        return Cache::remember('blog_settings', 3600, function () {
            if (!File::exists($this->settingsFile)) {
                return $this->defaults();
            }

            return array_merge($this->defaults(), json_decode(File::get($this->settingsFile), true) ?? []);
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function postsPerPage(): int
    {
        return (int) $this->get('posts_per_page', 9);
    }

    public function commentsAutoApprove(): bool
    {
        return (bool) $this->get('comments_auto_approve', true);
    }

    public function update(array $settings): void
    {
        $data = array_merge($this->all(), array_intersect_key($settings, array_flip(array_keys($this->defaults()))));
        $data['updated_at'] = now()->toDateTimeString();

        File::put($this->settingsFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        Cache::forget('blog_settings');
    }

    protected function defaults(): array
    {
        return [
            'blog_name' => config('app.name', 'KerpheX'),
            'blog_description' => 'Blog personnel — idées, explorations et réflexions.',
            'blog_keywords' => 'blog, kerphex, articles, tech',
            'comments_auto_approve' => true,
            'posts_per_page' => 9,
            'email_notifications' => false,
        ];
    }
}
