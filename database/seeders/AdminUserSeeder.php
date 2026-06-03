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
        // Créer le compte admin principal
        $adminEmail = 'kerphilesaint@gmail.com';

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

        // S'assurer que kerphileadome@gmail.com est admin aussi (pour Google OAuth)
        $googleEmail = 'kerphileadome@gmail.com';
        $googleUser = \App\Models\User::where('email', $googleEmail)->first();

        if ($googleUser) {
            $googleUser->update(['role' => 'admin', 'blocked' => false]);
        }
    }
}
