<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnTeteDocument;
use App\Models\LigneDocument;
use App\Models\Societe;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class DocumentSeeder extends Seeder
{
    /**
     * Exécute le seeder avec génération de lignes et calcul des totaux.
     */
    public function run(): void
    {
        $societes = Societe::all();

        if ($societes->isEmpty()) {
            $this->command->warn("Aucune société trouvée.");
            return;
        }

        foreach ($societes as $societe) {
            $types = ['F', 'D', 'A'];

            foreach ($types as $type) {
                for ($i = 1; $i <= 3; $i++) {
                    
                    // Utilisation d'une transaction pour garantir l'intégrité
                    DB::transaction(function () use ($societe, $type) {
                        
                        $annee = Carbon::now()->format('Y');
                        
                        // Calcul du numéro séquentiel
                        $dernierCompte = EnTeteDocument::where('societe_id', $societe->id)
                            ->where('type_document', $type)
                            ->whereYear('date_document', $annee)
                            ->count();

                        $sequence = $dernierCompte + 1;
                        $codeDocument = sprintf('%s-%s-%02d-%04d', $type, $annee, $societe->id, $sequence);

                        // 1. Création de l'en-tête (totaux à 0 par défaut)
                        $document = EnTeteDocument::create([
                            'societe_id'    => $societe->id,
                            'code_document' => $codeDocument,
                            'type_document' => $type,
                            'date_document' => now(),
                            'client_id'     => 1,
                            'total_ht'      => 0,
                            'total_tva'     => 0,
                            'total_ttc'     => 0,
                           'statut'        => Arr::random(['brouillon', 'envoye', 'paye']),

                        ]);
;

                        $totalHTGlobal = 0;
                        $totalTVAGlobal = 0;

                        //  Génération des lignes 
                        $nbLignes = rand(2, 5);
                        for ($j = 1; $j <= $nbLignes; $j++) {
                            $quantite = rand(1, 10);
                            $prixUnitaireHT = rand(50, 500);
                            $tauxTVA = 20.00; 
                            
                            $montantHT = $quantite * $prixUnitaireHT;
                            $montantTVA = ($montantHT * $tauxTVA) / 100;
                            $totalTTC = $montantHT + $montantTVA;

                            LigneDocument::create([
                                'document_id'      => $document->id, // Selon migration
                                'produit_id'       => null,          // Optionnel selon migration
                                'description'      => "Prestation ou produit de test n°$j", // Selon migration
                                'prix_unitaire_ht' => $prixUnitaireHT, // Selon migration
                                'taux_tva'         => $tauxTVA,
                                'quantite'         => $quantite,
                                'total_ttc'        => $totalTTC,       // Selon migration
                                'commentaire'      => rand(0, 1) ? "Commentaire aléatoire pour la ligne $j" : null,
                            ]);

                            $totalHTGlobal += $montantHT;
                            $totalTVAGlobal += $montantTVA;
                        }
                          //  Mise à jour de l'en-tête avec les sommes calculées
                        $document->update([
                            'total_ht'  => $totalHTGlobal,
                            'total_tva' => $totalTVAGlobal,
                            'total_ttc' => $totalHTGlobal + $totalTVAGlobal,
                        ]);
                    });
                }
                
            }
        }

        $this->command->info("Seeder terminé : Documents et lignes générés avec totaux calculés.");
    }
}