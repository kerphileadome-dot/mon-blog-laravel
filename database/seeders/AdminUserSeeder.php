<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = config('blog.admin_emails')[0] ?? 'kerphilesaint@gmail.com';

        if (!\App\Models\User::where('email', $adminEmail)->exists()) {
            $admin = new \App\Models\User();
            $admin->name = 'Kerphile Admin';
            $admin->email = $adminEmail;
            $admin->password = bcrypt('Franklinblog20?');
            $admin->role = 'admin';
            $admin->email_verified_at = now();
            $admin->blocked = false;
            $admin->save();
        }

        foreach (array_slice(config('blog.admin_emails', []), 1) as $email) {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                $user->update(['role' => 'admin', 'blocked' => false]);
            }
        }
    }
}
