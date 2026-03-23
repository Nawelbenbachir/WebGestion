<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Societe;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('societe_user')->truncate();
        DB::table('societes')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //  CRÉER L'ADMIN PROPRIÉTAIRE ──
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => Hash::make('123456789'),
            'role'     => 'admin',
        ]);

        //  CRÉER UN USER STANDARD ──
        $userStandard = User::create([
            'name'     => 'User Standard',
            'email'    => 'user@example.com',
            'password' => Hash::make('123456789'),
            'role'     => 'user',
        ]);

        // CRÉER 5 USERS ALÉATOIRES ──
        $autresUsers = User::factory()->count(5)->create(['role' => 'user']);

        //  CRÉER 2 SOCIÉTÉS DONT L'ADMIN EST PROPRIÉTAIRE ──
        $societe1 = Societe::create([
            'code_societe'    => 'ANC001',
            'nom_societe'     => 'Atelier Numérique Conseil',
            'siret'           => '12345678901234',
            'email'           => 'contact@anc.fr',
            'telephone'       => '0123456789',
            'adresse1'        => '12 rue de la Paix',
            'code_postal'     => '75001',
            'ville'           => 'Paris',
            'proprietaire_id' => $admin->id,
            'journal_ventes'      => 'VT',
            'journal_reglements'  => 'BQ',
            'compte_ventes'       => '707000',
            'compte_tva'          => '445710',
            'racine_compte_client'=> '411',
            'compte_banque'       => '512000',
        ]);

        $societe2 = Societe::create([
            'code_societe'    => 'ANC002',
            'nom_societe'     => 'Atelier Numérique Conseil - Agence Lyon',
            'siret'           => '98765432109876',
            'email'           => 'lyon@anc.fr',
            'telephone'       => '0456789012',
            'adresse1'        => '5 rue de la République',
            'code_postal'     => '69001',
            'ville'           => 'Lyon',
            'proprietaire_id' => $admin->id,
            'journal_ventes'      => 'VT',
            'journal_reglements'  => 'BQ',
            'compte_ventes'       => '707000',
            'compte_tva'          => '445710',
            'racine_compte_client'=> '411',
            'compte_banque'       => '512000',
        ]);

        // ──  TABLE PIVOT : rôles corrects ──

        // Admin = propriétaire des deux sociétés
        $admin->societes()->attach($societe1->id, ['role_societe' => 'admin']);
        $admin->societes()->attach($societe2->id, ['role_societe' => 'admin']);

        // User standard = simple user sur société 1
        $userStandard->societes()->attach($societe1->id, ['role_societe' => 'user']);

        // 2 users aléatoires sur société 1
        $autresUsers->take(2)->each(function ($user) use ($societe1) {
            $user->societes()->attach($societe1->id, ['role_societe' => 'user']);
        });

        // 2 autres users aléatoires sur société 2
        $autresUsers->skip(2)->take(2)->each(function ($user) use ($societe2) {
            $user->societes()->attach($societe2->id, ['role_societe' => 'user']);
        });

        // 1 user admin sur société 2
        $autresUsers->last()->societes()->attach($societe2->id, ['role_societe' => 'admin']);

        //  MAJ last_active_societe_id ──
        $admin->update(['last_active_societe_id' => $societe1->id]);
        $userStandard->update(['last_active_societe_id' => $societe1->id]);
    }
}