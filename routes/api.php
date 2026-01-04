<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatistiqueController; 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EnteteDocumentController;


    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//plus de sanctum car redirige automatiquement vers page login

        Route::get('/stat-vente/produit/{id}', [StatistiqueController::class, 'ventesParProduitGlobal'])->middleware('auth:sanctum');
        Route::get('/stat-vente/periode/{annee}/{mois}', [StatistiqueController::class, 'ventesParPeriodeGlobal'])->middleware('auth:sanctum');
        Route::get('/stat-vente/produit/{id}/{annee}/{mois}', [StatistiqueController::class, 'ventesParProduitEtMois'])->middleware('auth:sanctum');


     // On peut passer la societe_id en paramètre de requête (?societe_id=X)
    Route::get('documents', [EnteteDocumentController::class, 'index'])->middleware('auth:sanctum');
    
    // Route pour voir UN document spécifique (avec ses lignes)
    Route::get('documents/{id}', [EnteteDocumentController::class, 'show'])->middleware('auth:sanctum');