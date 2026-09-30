<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCustom
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté
        if (!session()->has('utilisateur_id')) {
            return redirect()->route('connexion')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return $next($request);
    }
}