<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RegistrationOtpService
{
    public function cacheKey(string $email): string
    {
        return 'registration_pending:'.hash('sha256', strtolower(trim($email)));
    }

    /**
     * @return array{otp: string, pending: array<string, mixed>}
     */
    public function start(string $name, string $email, string $plainPassword): array
    {
        $email = strtolower(trim($email));
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $pending = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($plainPassword),
            'otp_hash' => Hash::make($otp),
            'attempts' => 0,
        ];

        Cache::put(
            $this->cacheKey($email),
            $pending,
            now()->addMinutes((int) config('blog.registration_otp.expire_minutes', 15))
        );

        return ['otp' => $otp, 'pending' => $pending];
    }

    public function get(string $email): ?array
    {
        $data = Cache::get($this->cacheKey($email));

        return is_array($data) ? $data : null;
    }

    public function verify(string $email, string $otp): bool
    {
        $email = strtolower(trim($email));
        $pending = $this->get($email);

        if ($pending === null) {
            return false;
        }

        $maxAttempts = (int) config('blog.registration_otp.max_attempts', 5);

        if (($pending['attempts'] ?? 0) >= $maxAttempts) {
            $this->forget($email);

            return false;
        }

        if (! Hash::check($otp, $pending['otp_hash'])) {
            $pending['attempts'] = ($pending['attempts'] ?? 0) + 1;
            Cache::put(
                $this->cacheKey($email),
                $pending,
                now()->addMinutes((int) config('blog.registration_otp.expire_minutes', 15))
            );

            return false;
        }

        return true;
    }

    public function forget(string $email): void
    {
        Cache::forget($this->cacheKey($email));
    }

    /**
     * @return array{otp: string, pending: array<string, mixed>}|null
     */
    public function regenerateOtp(string $email): ?array
    {
        $email = strtolower(trim($email));
        $pending = $this->get($email);

        if ($pending === null) {
            return null;
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $pending['otp_hash'] = Hash::make($otp);
        $pending['attempts'] = 0;

        Cache::put(
            $this->cacheKey($email),
            $pending,
            now()->addMinutes((int) config('blog.registration_otp.expire_minutes', 15))
        );

        return ['otp' => $otp, 'pending' => $pending];
    }
}
