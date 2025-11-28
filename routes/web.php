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

Route::get('/', function () {
    return redirect()->route('login');
});
Route::resource('user', UserController::class)->except([
    'show'
]);

Route::resource('clients', ClientController::class)->except(['show']);

Route::resource('produits', ProduitController::class);

Route::resource('societes', SocieteController::class);
Route::post('/societe/update-selection', [SocieteController::class, 'updateSelection'])
    ->name('societe.update')
    ->middleware('auth');
    
Route::resource('documents',EnTeteDocumentController::class);


Route::resource('reglements', ReglementController::class);

Route::resource('parametres', ParametreController::class);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
