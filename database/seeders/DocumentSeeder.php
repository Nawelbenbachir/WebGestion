<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnTeteDocument;
use App\Models\LigneDocument;
use App\Models\Societe;
use App\Models\Client;
use App\Models\Produit; // Ajoute l'import du modèle Produit
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $societes = Societe::all();

        if ($societes->isEmpty()) {
            $this->command->warn("Aucune société trouvée.");
            return;
        }

        foreach ($societes as $societe) {
            // Récupérer les produits de CETTE société pour les utiliser dans les lignes
            $produitsDeLaSociete = Produit::where('id_societe', $societe->id)->get();

            if ($produitsDeLaSociete->isEmpty()) {
                $this->command->warn("La société {$societe->nom} n'a pas de produits. On passe.");
                continue;
            }

            $types = ['F', 'D', 'A'];

            foreach ($types as $type) {
                for ($i = 1; $i <= 3; $i++) {
                    $client = Client::where('id_societe', $societe->id)
                                    ->inRandomOrder()
                                    ->first();

                    if (!$client) {
                        $this->command->error("Aucun client pour la société {$societe->id}.");
                        continue;
                    }

                    DB::transaction(function () use ($societe, $type, $client, $produitsDeLaSociete) {
                        $annee = Carbon::now()->format('Y');
                        $dernierCompte = EnTeteDocument::where('societe_id', $societe->id)
                            ->where('type_document', $type)
                            ->whereYear('date_document', $annee)
                            ->count();

                        $sequence = $dernierCompte + 1;
                        $codeDocument = sprintf('%s-%s-%02d-%04d', $type, $annee, $societe->id, $sequence);

                        // 1. Création de l'en-tête
                        $document = EnTeteDocument::create([
                            'societe_id'    => $societe->id,
                            'code_document' => $codeDocument,
                            'type_document' => $type,
                            'adresse'       => $client->adresse1,
                            'code_postal'   => $client->code_postal,
                            'ville'         => $client->ville,
                            'telephone'     => $client->telephone,
                            'email'         => $client->email,
                            'date_document' => now(),
                            'client_id'     => $client->id,
                            'total_ht'      => 0,
                            'total_tva'     => 0,
                            'total_ttc'     => 0,
                            'statut'        => Arr::random(['brouillon', 'envoye', 'paye']),
                        ]);

                        $totalHTGlobal = 0;
                        $totalTVAGlobal = 0;

                        // 2. Création manuelle des lignes (SANS FACTORY)
                        $nbLignes = rand(2, 5);
                        for ($j = 0; $j < $nbLignes; $j++) {
                            $produit = $produitsDeLaSociete->random();
                            $quantite = rand(1, 10);
                            $prixHT = $produit->prix_vente_ht ?? 10.00;
                            $tauxTva = 20.0; // Ou $produit->taux_tva si tu l'as

                            $ligneHT = $prixHT * $quantite;
                            $ligneTVA = $ligneHT * ($tauxTva / 100);

                            LigneDocument::create([
                                'document_id'      => $document->id,
                                'produit_id'       => $produit->id,
                                'description'      => $produit->description ?? "Produit " . $produit->code_produit,
                                'quantite'         => $quantite,
                                'prix_unitaire_ht' => $prixHT,
                                'taux_tva'         => $tauxTva,
                                'total_ttc'        => round($ligneHT + $ligneTVA, 2),
                            ]);

                            $totalHTGlobal += $ligneHT;
                            $totalTVAGlobal += $ligneTVA;
                        }

                        // 3. Mise à jour des totaux de l'en-tête
                        $document->update([
                            'total_ht'  => round($totalHTGlobal, 2),
                            'total_tva' => round($totalTVAGlobal, 2),
                            'total_ttc' => round($totalHTGlobal + $totalTVAGlobal, 2),
                            'solde'     => round($totalHTGlobal + $totalTVAGlobal, 2),
                        ]);
                    });
                }
            }
        }

        $this->command->info("Seeder terminé avec succès et données cohérentes !");
    }
}