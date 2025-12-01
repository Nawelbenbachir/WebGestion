<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View; 
use App\Models\Societe;
use App\Models\User;

use App\View\Composers\SocieteComposer; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager ces données uniquement avec la vue de navigation
        // View::composer('layouts.navigation', function ($view) {
        //     if (Auth::check()) {
        //         $user = Auth::user();
                
        //         // Assurez-vous d'avoir les relations ownedSocietes et societes dans User.php !
        //         $availableSocietes = $user->ownedSocietes->merge($user->societes);

        //         $view->with('availableSocietes', $availableSocietes->unique('id'));
        //         $view->with('currentSocieteId', session('current_societe_id'));

        //     } else {
        //         // Si non connecté, s'assurer que les variables existent
        //         $view->with('availableSocietes', collect());
        //         $view->with('currentSocieteId', null);
        //     }
        // });
          View::composer('components.navigation', SocieteComposer::class);
    }
}