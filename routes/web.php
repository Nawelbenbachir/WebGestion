<?php

use Illuminate\Support\Facades\Route;

//Génère automatiquement toutes les routes du controller ClientController
Route::resource('clients', ClientController::class);

Route::resource('produits', ProduitController::class);

Route::resource('societes', SocieteController::class);

Route::resource('documents',EnTeteDocumentController::class);

Route::resource('lignes', LigneDocumentController::class);

Route::resource('reglements', ReglementController::class);

Route::get('/', function () {
    return view('welcome');
});
