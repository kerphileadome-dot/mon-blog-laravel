<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class AdminSession
{
    public static function active(): bool
    {
        $user = Auth::guard('admin')->user();

        return Auth::guard('admin')->check()
            && $user !== null
            && $user->isAdmin();
    }

    public static function clearUnlessAdminEmail(?string $email): void
    {
        if ($email === null || ! in_array(strtolower($email), config('blog.admin_emails', []), true)) {
            Auth::guard('admin')->logout();
        }
    }
}
