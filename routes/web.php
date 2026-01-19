<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\EnTeteDocumentController; // C'est celui-ci qu'on utilise
use App\Http\Controllers\LigneDocumentController;
use App\Http\Controllers\ReglementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    
    // Alias pour le Dashboard
    Route::get('/tableau-de-bord', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // La route index générique qui gère ?type=facture
    Route::get('documents', [EnTeteDocumentController::class, 'index'])->name('documents.index');

    Route::prefix('factures')->group(function () {
        Route::get('/', [EnTeteDocumentController::class, 'index'])->defaults('type', 'facture')->name('factures.index');
        Route::get('/create', [EnTeteDocumentController::class, 'create'])->defaults('type', 'facture')->name('factures.create');
        Route::get('factures/{id}/destroy', [EnTeteDocumentController::class, 'destroy'])->name('factures.destroy');
        Route::get('/factures/{id}/edit', [EnTeteDocumentController::class, 'edit'])->name('facture.edit');
        Route::get('/factures/{id}/pdf', [EnTeteDocumentController::class, 'downloadPdf'])->name('facture.pdf');
        Route::get('/{id}', [EnTeteDocumentController::class, 'show'])->defaults('type', 'facture')->name('factures.show');
    });

    Route::prefix('devis')->group(function () {
        Route::get('/', [EnTeteDocumentController::class, 'index'])->defaults('type', 'devis')->name('devis.index');
        Route::get('/create', [EnTeteDocumentController::class, 'create'])->defaults('type', 'devis')->name('devis.create');
        Route::get('devis/{id}/destroy', [EnTeteDocumentController::class, 'destroy'])->name('devis.destroy');
        Route::get('/devis/{id}/edit', [EnTeteDocumentController::class, 'edit'])->name('devis.edit');
        Route::get('/{id}', [EnTeteDocumentController::class, 'show'])->defaults('type', 'devis')->name('devis.show');
    });

    Route::prefix('avoirs')->group(function () {
        Route::get('/', [EnTeteDocumentController::class, 'index'])->defaults('type', 'avoir')->name('avoirs.index');
        Route::get('/create', [EnTeteDocumentController::class, 'create'])->defaults('type', 'avoir')->name('avoirs.create');
        Route::get('avoirs/{id}/destroy', [EnTeteDocumentController::class, 'destroy'])->name('avoirs.destroy');
        Route::get('/avoirs/{id}/edit', [EnTeteDocumentController::class, 'edit'])->name('avoirs.edit');
        Route::get('/{id}', [EnTeteDocumentController::class, 'show'])->defaults('type', 'avoir')->name('avoirs.show');
    }); 

    // Actions de transformation
    Route::post('/documents/{id}/transformF', [EnTeteDocumentController::class, 'transformerEnFacture'])->name('documents.transform');
    Route::post('/documents/{id}/transformA', [EnTeteDocumentController::class, 'transformerEnAvoir'])->name('documents.transformToAvoir');

    // --- AUTRES RESOURCES ---
    
    Route::resource('user', UserController::class)->except(['show']);
    Route::resource('clients', ClientController::class)->except(['show']);
    Route::resource('produits', ProduitController::class);
    Route::resource('societes', SocieteController::class);
    
    Route::resource('documents', EnTeteDocumentController::class)->except(['index']);

    Route::resource('reglements', ReglementController::class);
    Route::resource('parametres', ParametreController::class);

    // Profil Utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/societe/update-selection', [SocieteController::class, 'updateSelection'])->name('societe.update');
});

require __DIR__.'/auth.php';