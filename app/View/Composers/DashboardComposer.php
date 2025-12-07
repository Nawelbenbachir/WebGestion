<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\EnTeteDocument;
use Carbon\Carbon;
use App\Models\Reglement;

class DashboardComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // On récupère l'ID de la société active à partir de la session.
        $currentSocieteId = session('current_societe_id'); 

        // Si aucune société n'est sélectionnée  
        // on passe des valeurs par défaut pour éviter des erreurs.
        if (!$currentSocieteId) {
            $view->with([
                'monthly_ca' => 0,
                'pending_quotes_count' => 0,
                'unpaid_total' => 0,
                'new_clients_count' => 0,
            ]);
            return;
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        //  Chiffre d'Affaires du Mois 
        $monthly_ca = Reglement::whereHas('document', function ($query) use ($currentSocieteId) {
                            $query->where('societe_id', $currentSocieteId)
                                  ->where('type_document', 'F');
                        })
                        ->whereBetween('date_reglement', [$startOfMonth, $endOfMonth])
                        ->sum('montant');
        // Devis en attente 
        $pending_quotes_count = EnTeteDocument::where('societe_id', $currentSocieteId)
                                    ->where('type_document', 'D')
                                     ->whereIn('statut',['brouillon', 'envoye'])
                                     ->count();

        // Total des Factures pas encore payées
        $unpaid_total = EnTeteDocument::where('societe_id', $currentSocieteId)
                                ->where('type_document', 'F')
                               ->whereIn('statut',['brouillon', 'envoye'])
                               ->sum('solde');

        //  Nouveaux Clients (Mois) 
        $new_clients_count = Client::where('id_societe', $currentSocieteId)
                                   ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                                   ->count();
        // Récupération des dernières factures 
        $latestInvoices = EnTeteDocument::where('societe_id', $currentSocieteId)
                                    ->where('type_document', 'F')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5) // Limite l'affichage à 5 lignes
                                    ->get();

        // On transmet les variables à la vue
        $view->with([
            'monthly_ca' => $monthly_ca,
            'pending_quotes_count' => $pending_quotes_count,
            'unpaid_total' => $unpaid_total,
            'new_clients_count' => $new_clients_count,
            'latestInvoices' => $latestInvoices,
        ]);
    }
}