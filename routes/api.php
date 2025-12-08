<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatistiqueController; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('stat-vente/produit/{idProduit}/{annee}/{mois}', [StatistiqueController::class, 'ventesParProduitEtMois']);


Route::get('stat-vente/produit/{idProduit}', [StatistiqueController::class, 'ventesParProduitGlobal']);


Route::get('stat-vente/periode/{annee}/{mois}', [StatistiqueController::class, 'ventesParPeriodeGlobal']);