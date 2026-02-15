<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Societe;
use App\Models\EnTeteDocument;
use App\Models\Reglement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class ReglementSeeder extends Seeder
{
    public function run(): void
    {
        $societes = Societe::all();

        foreach ($societes as $societe) {
            // On ne prend que les factures payées qui n'ont pas encore de règlement
            $documents = EnTeteDocument::where('societe_id', $societe->id)
                ->where('statut', 'paye')
                ->whereDoesntHave('reglements')
                ->get();

            if ($documents->isEmpty()) continue;

            $sequence = 1;
            $annee = Carbon::now()->format('Y');
            $mois = Carbon::now()->format('m');
            $format = $societe->format_numero_document ?? 'simple';

            DB::transaction(function () use ($societe, $documents, $sequence, $annee, $mois, $format) {
                foreach ($documents as $document) {
                    
                    // Génération d'un numéro UNIQUE par document
                    $numeroReglement = ($format === 'mensuel') 
                        ? sprintf('REG-%s-%s-%03d', $annee, $mois, $sequence)
                        : sprintf('REG-%s-%03d', $annee, $sequence);

                    Reglement::create([
                        'societe_id'        => $societe->id,
                        'numero_reglement'  => $numeroReglement,
                        'date_reglement'    => now(),
                        'document_id'       => $document->id,
                        'montant'           => $document->total_ttc,
                        'mode_reglement' => Arr::random(['chèque', 'virement', 'espèces', 'carte bancaire']),
                    ]);

                    $sequence++; 
                }
            });
        }
    }
}