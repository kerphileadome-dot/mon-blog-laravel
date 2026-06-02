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
        // Vérifier si le compte admin existe déjà
        $adminEmail = 'kerphileadome@gmail.com';

        if (\App\Models\User::where('email', $adminEmail)->exists()) {
            // Compte existe déjà, on ne fait rien
            return;
        }

        // Créer le compte admin
        \App\Models\User::create([
            'name' => 'Kerphile',
            'email' => $adminEmail,
            'password' => bcrypt('Blogperso20?'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
