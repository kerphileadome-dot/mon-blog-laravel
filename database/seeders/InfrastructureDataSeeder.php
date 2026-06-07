<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InfrastructureDataSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('sessions')->count() === 0) {
            $this->seedSessions();
        }

        if (DB::table('cache')->count() === 0) {
            $this->seedCache();
        }

        if (DB::table('password_reset_tokens')->count() === 0) {
            $this->seedPasswordResetTokens();
        }

        if (DB::table('jobs')->count() === 0) {
            $this->seedJobs();
        }

        if (DB::table('failed_jobs')->count() === 0) {
            $this->seedFailedJobs();
        }

        if (DB::table('cache_locks')->count() === 0) {
            $this->seedCacheLocks();
        }

        if (DB::table('job_batches')->count() === 0) {
            $this->seedJobBatches();
        }

        $this->command->info('Tables système remplies :');
        $this->command->info('  - sessions : '.DB::table('sessions')->count());
        $this->command->info('  - cache : '.DB::table('cache')->count());
        $this->command->info('  - password_reset_tokens : '.DB::table('password_reset_tokens')->count());
        $this->command->info('  - jobs : '.DB::table('jobs')->count());
        $this->command->info('  - failed_jobs : '.DB::table('failed_jobs')->count());
        $this->command->info('  - cache_locks : '.DB::table('cache_locks')->count());
        $this->command->info('  - job_batches : '.DB::table('job_batches')->count());
    }

    protected function seedSessions(): void
    {
        $now = time();
        $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) KerpheX-Demo/1.0';

        $users = User::orderBy('id')->get();

        foreach ($users as $user) {
            DB::table('sessions')->insert([
                'id'             => Str::random(40),
                'user_id'        => $user->id,
                'ip_address'     => $user->role === 'admin' ? '127.0.0.1' : '192.168.1.'.(10 + $user->id),
                'user_agent'     => $agent,
                'payload'        => base64_encode(serialize([
                    '_token' => Str::random(40),
                    '_flash' => ['old' => [], 'new' => []],
                ])),
                'last_activity'  => $now - random_int(60, 3600),
            ]);
        }

        // Session visiteur anonyme (non connecté)
        DB::table('sessions')->insert([
            'id'            => Str::random(40),
            'user_id'       => null,
            'ip_address'    => '192.168.1.99',
            'user_agent'    => $agent,
            'payload'       => base64_encode(serialize([
                '_token' => Str::random(40),
                '_flash' => ['old' => [], 'new' => []],
            ])),
            'last_activity' => $now - 120,
        ]);
    }

    protected function seedCache(): void
    {
        $expiration = now()->addDay()->timestamp;

        $entries = [
            'kerphex:stats:home' => json_encode([
                'total_posts' => 4,
                'total_views' => 574,
                'cached_at'   => now()->toIso8601String(),
            ], JSON_UNESCAPED_UNICODE),
            'kerphex:settings' => json_encode([
                'comments_auto_approve' => true,
                'site_tagline'          => 'Blog professionnel KerpheX',
            ], JSON_UNESCAPED_UNICODE),
            'kerphex:categories' => json_encode(['Sport', 'Santé', 'Technologie', 'Politique'], JSON_UNESCAPED_UNICODE),
        ];

        foreach ($entries as $key => $value) {
            DB::table('cache')->insert([
                'key'        => $key,
                'value'      => $value,
                'expiration' => $expiration,
            ]);
        }
    }

    protected function seedPasswordResetTokens(): void
    {
        $visitor = User::where('role', 'visitor')->orderBy('id')->skip(1)->first()
            ?? User::where('role', 'visitor')->first();

        if (!$visitor) {
            return;
        }

        DB::table('password_reset_tokens')->insert([
            'email'      => $visitor->email,
            'token'      => Hash::make('demo-reset-token-'.Str::random(8)),
            'created_at' => now()->subMinutes(15),
        ]);
    }

    protected function seedJobs(): void
    {
        $payload = json_encode([
            'uuid'          => (string) Str::uuid(),
            'displayName'   => 'App\\Notifications\\ResetPasswordNotification',
            'job'           => 'Illuminate\\Queue\\CallQueuedHandler@call',
            'maxTries'      => null,
            'maxExceptions' => null,
            'failOnTimeout' => false,
            'backoff'       => null,
            'timeout'       => null,
            'retryUntil'    => null,
            'data'          => [
                'commandName' => 'Illuminate\\Notifications\\SendQueuedNotifications',
                'command'     => serialize(new \stdClass()),
            ],
        ]);

        DB::table('jobs')->insert([
            'queue'        => 'default',
            'payload'      => $payload,
            'attempts'     => 0,
            'reserved_at'  => null,
            'available_at' => time(),
            'created_at'   => time(),
        ]);
    }

    protected function seedFailedJobs(): void
    {
        DB::table('failed_jobs')->insert([
            'uuid'       => (string) Str::uuid(),
            'connection' => 'database',
            'queue'      => 'default',
            'payload'    => json_encode(['job' => 'demo', 'data' => ['email' => 'demo@example.com']]),
            'exception'  => 'Illuminate\\Queue\\MaxAttemptsExceededException: Démo — tâche email expirée (donnée fictive pour phpMyAdmin).',
            'failed_at'  => now()->subHours(2),
        ]);
    }

    protected function seedCacheLocks(): void
    {
        DB::table('cache_locks')->insert([
            'key'        => 'kerphex:lock:demo',
            'owner'      => 'seeder-demo',
            'expiration' => now()->addHour()->timestamp,
        ]);
    }

    protected function seedJobBatches(): void
    {
        DB::table('job_batches')->insert([
            'id'              => (string) Str::uuid(),
            'name'            => 'Envoi notifications démo',
            'total_jobs'      => 3,
            'pending_jobs'    => 0,
            'failed_jobs'     => 0,
            'failed_job_ids'  => '[]',
            'options'         => json_encode(['queue' => 'default']),
            'cancelled_at'    => null,
            'created_at'      => time() - 3600,
            'finished_at'     => time() - 3500,
        ]);
    }
}
