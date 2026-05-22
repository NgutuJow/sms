<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccountantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has accountant or admin role
        if (!$user->hasRole('accountant') && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized. Accountant access required.');
        }

        return $next($request);
    }
}
