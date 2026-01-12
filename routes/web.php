<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\EnTeteDocumentController;
use App\Http\Controllers\LigneDocumentController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Toutes les routes nécessitant une connexion
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // --- Routes Spécifiques (À placer AVANT les resources pour éviter les 404/conflits) ---
    
    // Action de transformation de document
    Route::post('/documents/{id}/transform', [EnTeteDocumentController::class, 'transformToInvoice'])
        ->name('documents.transform');

    // Mise à jour de la société sélectionnée
    Route::post('/societe/update-selection', [SocieteController::class, 'updateSelection'])
        ->name('societe.update');

    // --- Resources ---
    
    Route::resource('user', UserController::class)->except(['show']);
    Route::resource('clients', ClientController::class)->except(['show']);
    Route::resource('produits', ProduitController::class);
    Route::resource('societes', SocieteController::class);
    Route::resource('documents', EnTeteDocumentController::class);
    Route::resource('reglements', ReglementController::class);
    Route::resource('parametres', ParametreController::class);

    // Profil Utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes d'authentification (Breeze, Jetstream, etc.)
require __DIR__.'/auth.php';