<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SyncFromProductionCommand extends Command
{
    protected $signature = 'blog:sync-from-production
                            {--url= : URL de production}
                            {--token= : Jeton SYNC_EXPORT_TOKEN}
                            {--db-only : Importer uniquement la base SQLite}';

    protected $description = 'Copie la base, les paramètres et les fichiers publics depuis la production vers le local';

    public function handle(): int
    {
        $baseUrl = rtrim($this->option('url') ?: config('blog.production_url', ''), '/');
        $token = $this->option('token') ?: config('blog.sync_export_token');

        if ($baseUrl === '') {
            $this->error('URL de production manquante. Définissez PRODUCTION_URL dans .env ou utilisez --url=');

            return self::FAILURE;
        }

        if (blank($token)) {
            $this->error('Jeton manquant. Définissez SYNC_EXPORT_TOKEN dans .env (même valeur que sur Railway).');

            return self::FAILURE;
        }

        $this->info("Synchronisation depuis {$baseUrl}...");

        $headers = [
            'X-Sync-Token' => $token,
            'Accept' => '*/*',
        ];

        if (! $this->importDatabase($baseUrl, $headers)) {
            return self::FAILURE;
        }

        if ($this->option('db-only')) {
            $this->finalize();

            return self::SUCCESS;
        }

        $this->importSettings($baseUrl, $headers);
        $this->importStorage($baseUrl, $headers);
        $this->finalize();

        $this->info('Synchronisation terminée : le local reflète la production.');

        return self::SUCCESS;
    }

    private function importDatabase(string $baseUrl, array $headers): bool
    {
        $this->line('  → Base SQLite...');

        $response = Http::timeout(120)
            ->withHeaders($headers)
            ->get("{$baseUrl}/internal/sync/database");

        if ($response->status() === 404) {
            $this->error('Export indisponible (404). Sur Railway : ajoutez SYNC_EXPORT_TOKEN, redéployez, puis relancez.');

            return false;
        }

        if (! $response->successful()) {
            $this->error('Échec téléchargement base : HTTP '.$response->status());

            return false;
        }

        $target = database_path('database.sqlite');
        $backup = database_path('database.sqlite.backup-'.now()->format('Ymd-His'));

        if (File::exists($target)) {
            File::copy($target, $backup);
            $this->line("    Sauvegarde locale : {$backup}");
        }

        File::put($target, $response->body());
        $size = number_format(File::size($target) / 1024, 1);
        $this->info("    Base importée ({$size} Ko)");

        return true;
    }

    private function importSettings(string $baseUrl, array $headers): void
    {
        $this->line('  → Paramètres blog...');

        $response = Http::timeout(30)
            ->withHeaders($headers)
            ->get("{$baseUrl}/internal/sync/settings");

        if (! $response->successful()) {
            $this->warn('    Paramètres non récupérés (HTTP '.$response->status().')');

            return;
        }

        File::put(storage_path('app/blog_settings.json'), $response->body());
        $this->info('    blog_settings.json importé');
    }

    private function importStorage(string $baseUrl, array $headers): void
    {
        $this->line('  → Fichiers storage/public...');

        $manifest = Http::timeout(30)
            ->withHeaders($headers)
            ->get("{$baseUrl}/internal/sync/storage-manifest");

        if (! $manifest->successful()) {
            $this->warn('    Manifeste storage non récupéré');

            return;
        }

        $files = $manifest->json('files', []);
        $imported = 0;

        foreach ($files as $file) {
            $relative = $file['path'] ?? null;
            if (! is_string($relative) || $relative === '') {
                continue;
            }

            $download = Http::timeout(60)
                ->withHeaders($headers)
                ->get("{$baseUrl}/internal/sync/storage/{$relative}");

            if (! $download->successful()) {
                $this->warn("    Ignoré : {$relative}");

                continue;
            }

            $destination = storage_path('app/public/'.$relative);
            File::ensureDirectoryExists(dirname($destination));
            File::put($destination, $download->body());
            $imported++;
        }

        $this->info("    {$imported} fichier(s) importé(s)");
    }

    private function finalize(): void
    {
        $this->callSilent('config:clear');
        $this->callSilent('cache:clear');
        $this->callSilent('view:clear');

        if (! File::exists(public_path('storage'))) {
            $this->callSilent('storage:link');
        }
    }
}
