<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmails = config('blog.admin_emails', ['kerphilesaint@gmail.com']);
        $primaryEmail = $adminEmails[0] ?? 'kerphilesaint@gmail.com';

        if (!User::where('email', $primaryEmail)->exists()) {
            User::create([
                'name' => 'Kerphile Admin',
                'email' => $primaryEmail,
                'password' => bcrypt('Franklinblog20?'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'blocked' => false,
            ]);

            $this->command->info("Compte admin créé : {$primaryEmail}");
        }

        foreach ($adminEmails as $email) {
            $updated = User::where('email', $email)->update([
                'role' => 'admin',
                'blocked' => false,
            ]);

            if ($updated) {
                $this->command->info("Rôle admin confirmé : {$email}");
            }
        }

        $demoted = User::where('role', 'admin')
            ->whereNotIn('email', $adminEmails)
            ->update(['role' => 'visitor']);

        if ($demoted > 0) {
            $this->command->info("{$demoted} ancien(s) admin(s) repassé(s) visiteur.");
        }
    }
}
