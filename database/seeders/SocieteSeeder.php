<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Societe;
use Illuminate\Support\Facades\DB;

class SocieteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // On s'assure que la table est vide pour que le Factory commence à l'ID 1
        DB::table('societes')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crée 3 sociétés avec les ID 1, 2 et 3
        Societe::factory()->count(3)->create(); 
    }
}