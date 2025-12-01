<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Societe;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        UserSeeder::class,
        SocieteSeeder::class, 
        ClientSeeder::class, 
        ProduitSeeder::class
        ]);

         // Récupérer les entités fraîchement créées
        $admin = User::where('email', 'admin@example.com')->first();
        $standardUser = User::where('email', 'user@example.com')->first();
        $societeA = Societe::find(1);
        $societeB = Societe::find(2);

        // Cette étape n'est nécessaire que si les utilisateurs initiaux existent.
        if ($admin && $standardUser && $societeA && $societeB) {
            
            //  Rattacher les Sociétés Principales (societe_id) aux utilisateurs initiaux
            $admin->societe_id = $societeA->id;
            $admin->last_active_societe_id = $societeA->id;
            $admin->save();

            $standardUser->societe_id = $societeB->id;
            $standardUser->last_active_societe_id = $societeB->id;
            $standardUser->save();

            //  Populer la table pivot 'societe_user' pour l'Admin et l'Utilisateur Standard
            // L'admin est membre de la Société A avec le rôle 'admin'
            $admin->societes()->attach($societeA->id, ['role_societe' => 'admin']);
            
            // L'utilisateur standard est membre de la Société B avec le rôle 'user'
            $standardUser->societes()->attach($societeB->id, ['role_societe' => 'user']);
        }


        //  Populer la table pivot 'societe_user' pour les utilisateurs Factory
        $users = User::all();
        $societeIds = Societe::pluck('id')->toArray();
        
        foreach ($users as $user) {
            // S'assurer que chaque utilisateur est membre d'au moins 1 à 3 sociétés aléatoires
            $societesToAttach = (new \Illuminate\Support\Collection($societeIds))
                                    ->shuffle()
                                    ->take(rand(1, 3))
                                    ->unique()
                                    ->toArray();
                                    
            if ($user->societe_id && !in_array($user->societe_id, $societesToAttach)) {
                $societesToAttach[] = $user->societe_id;
            }

            foreach (array_unique($societesToAttach) as $societeId) {
                // Évite d'attacher à nouveau les utilisateurs déjà gérés au point B
                if (!$user->societes->contains($societeId)) { 
                    $user->societes()->attach($societeId, [
                        'role_societe' => 'user', 
                    ]);
                }
            }
        }
        
    }
}
