<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatistiqueController; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//  STATISTIQUES PAR PRODUIT ET PÉRIODE (Route la plus spécifique)
Route::get('stat/produit/{idProduit}/{annee}/{mois}', [StatistiqueController::class, 'ventesParProduitEtMois']);

//  STATISTIQUES GLOBALES PAR PRODUIT
Route::get('stat/produit/{idProduit}', [StatistiqueController::class, 'ventesParProduitGlobal']);

//  STATISTIQUES GLOBALES PAR PÉRIODE (Tous produits)
Route::get('stat/periode/{annee}/{mois}', [StatistiqueController::class, 'ventesParPeriodeGlobal']);
