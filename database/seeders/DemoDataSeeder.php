<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Données de démo visiteurs désactivées — les comptes réels s'inscrivent via Gmail.
     */
    public function run(): void
    {
        $this->command->info('DemoDataSeeder ignoré : pas de comptes visiteurs fictifs.');
    }
}
