<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Utilisateur 1
        User::create([
            'name' => 'Jean-Baptiste Kpossou',
            'email' => 'jeanbaptiste.kpossou@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'visitor',
            'blocked' => false,
            'email_verified_at' => now(),
        ]);

        // Utilisateur 2
        User::create([
            'name' => 'Marie Adjovi',
            'email' => 'marie.adjovi@yahoo.fr',
            'password' => Hash::make('password123'),
            'role' => 'visitor',
            'blocked' => false,
            'email_verified_at' => now(),
        ]);

        // Utilisateur 3
        User::create([
            'name' => 'Thomas Dossou',
            'email' => 'thomas.dossou@outlook.com',
            'password' => Hash::make('password123'),
            'role' => 'visitor',
            'blocked' => false,
            'email_verified_at' => now(),
        ]);

        $this->command->info('3 utilisateurs créés avec succès!');
    }
}
