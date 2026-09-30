<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté
        if (!session()->has('utilisateur_id')) {
            return redirect()->route('connexion')
                ->with('error', 'Vous devez être connecté.');
        }

        // Vérifie si l'utilisateur est admin
        if (session()->get('utilisateur_role') !== 'admin') {
            return redirect()->route('accueil')
                ->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}