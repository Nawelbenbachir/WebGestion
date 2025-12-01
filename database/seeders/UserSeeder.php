<?php

namespace Database\Seeders;

use Illuminate\support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Societe; 
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        //  DÉBUT : Désactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // S'assurer que les utilisateurs existants sont nettoyés
        // Cette ligne fonctionne maintenant
        DB::table('users')->truncate();
        
        //  FIN : Réactiver les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
        

        // Création de l'utilisateur Admin, lié explicitement à la première société
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
            'societe_id' => null, 
            'last_active_societe_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        //  Création d'un deuxième utilisateur standard, lié explicitement à la deuxième société
        DB::table('users')->insert([
            'name' => 'User Standard',
            'email' => 'user@example.com',
            'password' => Hash::make('123456789'),
            'role' => 'user',
            'societe_id' => null, 
            'last_active_societe_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //  Générateur de fausses données (10 utilisateurs)
        User::factory()->count(10)->create([
            'societe_id' => null, 
            'last_active_societe_id' => null,
        ]);
        
    }
}