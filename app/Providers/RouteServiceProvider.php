<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Le chemin vers lequel l'utilisateur sera redirigé après login ou inscription
     */
    public const HOME = '/dashboard';

    /**
     * Redirection personnalisée en fonction du rôle
     * (utilisable dans tes contrôleurs si tu veux une logique fine)
     */
    public static function redirectTo(): string
    {
        $user = auth()->user();

        return match ($user->role) {
            'entreprise' => '/entreprise/dashboard',
            'etudiant' => '/etudiant/dashboard',
            default => '/dashboard', // fallback
        };
    }

    /**
     * Définition des groupes de routes de l'application.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
