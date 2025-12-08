<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EnTeteDocument;
use App\Models\LigneDocument;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EnTeteDocument::factory(50)->create()->each(function (EnTeteDocument $document) {
            
            //  Créer les lignes en utilisant la LigneDocumentFactory
            $lignes = LigneDocument::factory(rand(1, 5))->create([
                'document_id' => $document->id, // Lier les lignes à l'en-tête
            ]);

            //  Calculer les totaux à partir des lignes créées
            $total_ttc_doc = $lignes->sum('total_ttc');
            // Si la LigneDocumentFactory ne stocke pas le HT/TVA séparément, on doit recalculer ici
            // Mais pour simplifier, si total_ttc est calculé dans la Factory:
            
            $total_ht_doc = $lignes->sum(function ($ligne) {
                // Pour obtenir le HT à partir du TTC (en supposant 20% TVA)
                return $ligne->total_ttc / (1 + ($ligne->taux_tva / 100));
            });
            
            $total_tva_doc = $total_ttc_doc - $total_ht_doc;

            //  Mettre à jour l'En-tête
            $document->update([
                'total_ht' => round($total_ht_doc, 2),
                'total_tva' => round($total_tva_doc, 2),
                'total_ttc' => round($total_ttc_doc, 2),
                'solde' => ($document->statut == 'paye') ? 0.00 : round($total_ttc_doc, 2),
            ]);
        });
    
    }
}
