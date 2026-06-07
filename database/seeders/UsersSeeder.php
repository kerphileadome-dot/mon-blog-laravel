<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Les comptes visiteurs sont créés uniquement via l'inscription (@gmail.com).
     * Seul AdminUserSeeder crée le compte administrateur.
     */
    public function run(): void
    {
        $this->command->info('Aucun visiteur fictif : inscription Gmail uniquement.');
    }
}
