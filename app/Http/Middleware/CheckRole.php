<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        if ($role && $user->role !== $role) {
            return match($user->role) {
                'etudiant' => redirect('/etudiant/dashboard'),
                'entreprise' => redirect('/entreprise/dashboard'),
                default => redirect('/login')
            };
        }

        return $next($request);
    }
}